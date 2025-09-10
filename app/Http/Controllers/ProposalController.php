<?php

namespace App\Http\Controllers;

use App\Exports\ProposalExport;
use App\Models\ActivityLog;
use App\Models\Customer;
use App\Models\CustomField;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use App\Models\Milestone;
use App\Models\Products;
use App\Models\ProductService;
use App\Models\ProductServiceCategory;
use App\Models\Proposal;
use App\Models\ProposalProduct;
use App\Models\StockReport;
use App\Models\Task;
use App\Models\User;
use App\Models\Utility;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Brian2694\Toastr\Facades\Toastr;

class ProposalController extends Controller
{
    public function __construct()
    {

    }

    public function index(Request $request)
    {
        $customer = Customer::get()->pluck('name', 'id');

        $customer->prepend('All', '');

        $status = Proposal::$statues;

        $query = Proposal::query();

        if(!empty($request->customer))
        {
            $query->where('id', '=', $request->customer);
        }
        if(!empty($request->issue_date))
        {
            $date_range = explode('to', $request->issue_date);
            $query->whereBetween('issue_date', $date_range);
        }

        if(!empty($request->status))
        {
            $query->where('status', '=', $request->status);
        }
        $proposals = $query->with(['category'])->get();

        return view('proposal.index', compact('proposals', 'customer', 'status'));
    }

    public function create($customerId)
    {

        $customFields    = CustomField::where('module', '=', 'proposal')->get();
        $proposal_number = Utility::proposalNumberFormat($this->proposalNumber());
        $customers       = Customer::get()->pluck('name', 'id');
        $customers->prepend('Select Customer', '');
        $category = ProductServiceCategory::where('type', 'income')->get()->pluck('name', 'id');
        $category->prepend('Select Category', '');
        $product_services = ProductService::get()->pluck('name', 'id');
        $product_services->prepend('--', '');

        return view('proposal.create', compact('customers', 'proposal_number', 'product_services', 'category', 'customFields', 'customerId'));
    
    }

    public function customer(Request $request)
    {
        $customer = Customer::where('id', '=', $request->id)->first();

        return view('proposal.customer_detail', compact('customer'));
    }

    public function product(Request $request)
    {

        $data['product'] = $product = ProductService::find($request->product_id);

        $data['unit']    = (!empty($product->unit)) ? $product->unit->name : '';
        $data['taxRate'] = $taxRate = !empty($product->tax_id) ? $product->taxRate($product->tax_id) : 0;

        $data['taxes'] = !empty($product->tax_id) ? $product->tax($product->tax_id) : 0;

        $salePrice           = $product->sale_price;
        $quantity            = 1;
        $taxPrice            = ($taxRate / 100) * ($salePrice * $quantity);
        $data['totalAmount'] = ($salePrice * $quantity);

        return json_encode($data);
    }

    public function store(Request $request)
    {
            $validator = \Validator::make(
                $request->all(), [
                                   'customer_id' => 'required',
                                   'issue_date' => 'required',
                                   'category_id' => 'required',
                                   'items' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $status = Proposal::$statues;

            $proposal                 = new Proposal();
            $proposal->proposal_id    = $this->proposalNumber();
            $proposal->customer_id    = $request->customer_id;
            $proposal->status         = 0;
            $proposal->issue_date     = $request->issue_date;
            $proposal->category_id    = $request->category_id;
            $proposal->created_by     = \Auth::user()->id;
            $proposal->save();
            CustomField::saveData($proposal, $request->customField);
            $products = $request->items;

            for($i = 0; $i < count($products); $i++)
            {
                $proposalProduct              = new ProposalProduct();
                $proposalProduct->proposal_id = $proposal->id;
                $proposalProduct->product_id  = $products[$i]['item'];
                $proposalProduct->quantity    = $products[$i]['quantity'];
                $proposalProduct->tax         = $products[$i]['tax'];
//                $proposalProduct->discount    = isset($products[$i]['discount']) ? $products[$i]['discount'] : 0;
                $proposalProduct->discount    = $products[$i]['discount'];
                $proposalProduct->price       = $products[$i]['price'];
                $proposalProduct->description = $products[$i]['description'];
                $proposalProduct->save();
            }

            Toastr::success('Proposal successfully created.','Success');
            return redirect()->route('proposal.index', $proposal->id);

    }

    public function edit($ids)
    {
        try {
            $id              = Crypt::decrypt($ids);
        } catch (\Throwable $th) {
            Toastr::error('Proposal Not Found.','Error');
            return redirect()->back();
        }
        $id              = Crypt::decrypt($ids);
        $proposal        = Proposal::find($id);
        $proposal_number = Utility::proposalNumberFormat($proposal->proposal_id);
        $customers       = Customer::get()->pluck('name', 'id');
        $category        = ProductServiceCategory::where('type', 'income')->get()->pluck('name', 'id');
        $category->prepend('Select Category', '');
        $product_services = ProductService::get()->pluck('name', 'id');
        $proposal->customField = CustomField::getData($proposal, 'proposal');
        $customFields          = CustomField::where('module', '=', 'proposal')->get();

        $items = [];
        foreach($proposal->items as $proposalItem)
        {
            $itemAmount               = $proposalItem->quantity * $proposalItem->price;
            $proposalItem->itemAmount = $itemAmount;
            $proposalItem->taxes      = $this->tax($proposalItem->tax);
            $items[]                  = $proposalItem;
        }

        return view('proposal.edit', compact('customers', 'product_services', 'proposal', 'proposal_number', 'category', 'customFields', 'items'));

}

public function update(Request $request, Proposal $proposal)
{
    $validator = \Validator::make(
        $request->all(), [
                            'customer_id' => 'required',
                            'issue_date' => 'required',
                            'category_id' => 'required',
                            'items' => 'required',
                        ]
    );
    if($validator->fails())
    {
        $messages = $validator->getMessageBag();

        return redirect()->route('proposal.index')->with('error', $messages->first());
    }
    $proposal->customer_id    = $request->customer_id;
    $proposal->issue_date     = $request->issue_date;
    $proposal->category_id    = $request->category_id;
    $proposal->save();
    CustomField::saveData($proposal, $request->customField);
    $products = $request->items;

    for($i = 0; $i < count($products); $i++)
    {
        $proposalProduct = ProposalProduct::find($products[$i]['id']);
        if($proposalProduct == null)
        {
            $proposalProduct              = new ProposalProduct();
            $proposalProduct->proposal_id = $proposal->id;

        }

        if(isset($products[$i]['item']))
        {
            $proposalProduct->product_id = $products[$i]['item'];
        }

        $proposalProduct->quantity    = $products[$i]['quantity'];
        $proposalProduct->tax         = $products[$i]['tax'];
        $proposalProduct->discount    = $products[$i]['discount'];
        $proposalProduct->price       = $products[$i]['price'];
        $proposalProduct->description = $products[$i]['description'];
        $proposalProduct->save();
    }
    Toastr::success('Proposal successfully updated.','Success');
    return redirect()->route('proposal.index', $proposal->id);

    }

    function proposalNumber()
    {
        $latest = Proposal::latest()->first();
        if(!$latest)
        {
            return 1;
        }

        return $latest->proposal_id + 1;
    }

    public function show($ids)
    {
            try {
                $id       = Crypt::decrypt($ids);
            } catch (\Throwable $th) {
                Toastr::error('Proposal Not Found.','Error');
                return redirect()->back();
            }
            $id       = Crypt::decrypt($ids);
            $proposal = Proposal::find($id);

            $customer = $proposal->customer;
            $iteams   = $proposal->items;
            $status   = Proposal::$statues;

            $proposal->customField = CustomField::getData($proposal, 'proposal');
            $customFields          = CustomField::where('module', '=', 'proposal')->get();

            return view('proposal.view', compact('proposal', 'customer', 'iteams', 'status', 'customFields'));
            
    }

    public function destroy(Proposal $proposal)
    {
        $proposal->delete();
        ProposalProduct::where('proposal_id', '=', $proposal->id)->delete();

        Toastr::success('Proposal successfully deleted.','Success');
        return redirect()->route('proposal.index');
    }

    public function productDestroy(Request $request)
    {

        ProposalProduct::where('id', '=', $request->id)->delete();

        Toastr::success('Proposal product successfully deleted.','Success');
        return redirect()->back();
    }

    public function customerProposal(Request $request)
    {
        $status = Proposal::$statues;

        $query = Proposal::where('customer_id', '=', \Auth::user()->id)->where('status', '!=', '0');

        if(!empty($request->issue_date))
        {
            $date_range = explode(' - ', $request->issue_date);
            $query->whereBetween('issue_date', $date_range);
        }

        if(!empty($request->status))
        {
            $query->where('status', '=', $request->status);
        }
        $proposals = $query->get();

        return view('proposal.index', compact('proposals', 'status'));
    }

    public function customerProposalShow($ids)
    {
        $proposal_id = \Crypt::decrypt($ids);
        $proposal    = Proposal::where('id', $proposal_id)->first();

        $customer = $proposal->customer;
        $iteams   = $proposal->items;

        return view('proposal.view', compact('proposal', 'customer', 'iteams'));
    }

    public function sent($id)
    {
            $proposal            = Proposal::where('id', $id)->first();
            $proposal->send_date = date('Y-m-d');
            $proposal->status    = 1;
            $proposal->save();

            $customer           = Customer::where('id', $proposal->customer_id)->first();
            $proposal->name     = !empty($customer) ? $customer->name : '';
            $proposal->proposal = Utility::proposalNumberFormat($proposal->proposal_id);

            $proposalId    = Crypt::encrypt($proposal->id);
            $proposal->url = route('proposal.pdf', $proposalId);

            // Send Email
            $setings = Utility::settings();
            if($setings['proposal_sent'] == 1)
            {
                $customer           = Customer::where('id', $proposal->customer_id)->first();
                $proposal->name     = !empty($customer) ? $customer->name : '';
                $proposal->proposal = Utility::proposalNumberFormat($proposal->proposal_id);

                $proposalId    = Crypt::encrypt($proposal->id);
                $proposal->url = route('proposal.pdf', $proposalId);

                $proposalArr = [
                    'proposal_name' => $proposal->name,
                    'proposal_number' => $proposal->proposal,
                    'proposal_url' => $proposal->url,

                ];

                $resp = \App\Models\Utility::sendEmailTemplate('proposal_sent', [$customer->id => $customer->email], $proposalArr);
                
                Toastr::success('Proposal successfully sent.','Success');
                return redirect()->back();

            }

            Toastr::success('Proposal successfully sent.','Success');
            return redirect()->back();

    }

    public function resent($id)
    {
            $proposal = Proposal::where('id', $id)->first();

            $customer           = Customer::where('id', $proposal->customer_id)->first();
            $proposal->name     = !empty($customer) ? $customer->name : '';
            $proposal->proposal = Utility::proposalNumberFormat($proposal->proposal_id);

            $proposalId    = Crypt::encrypt($proposal->id);
            $proposal->url = route('proposal.pdf', $proposalId);

            // Send Email
            $setings = Utility::settings();
            if($setings['proposal_sent'] == 1)
            {
                $customer           = Customer::where('id', $proposal->customer_id)->first();
                $proposal->name     = !empty($customer) ? $customer->name : '';
                $proposal->proposal = Utility::proposalNumberFormat($proposal->proposal_id);

                $proposalId    = Crypt::encrypt($proposal->id);
                $proposal->url = route('proposal.pdf', $proposalId);

                $proposalArr = [
                    'proposal_name' => $proposal->name,
                    'proposal_number' => $proposal->proposal,
                    'proposal_url' => $proposal->url,

                ];

                $resp = \App\Models\Utility::sendEmailTemplate('proposal_sent', [$customer->id => $customer->email], $proposalArr);

                Toastr::success('Proposal successfully sent.','Success');
                return redirect()->back();

            }

            Toastr::success('Proposal successfully sent.','Success');
            return redirect()->back();

    }


    public function shippingDisplay(Request $request, $id)
    {
        $proposal = Proposal::find($id);

        if($request->is_display == 'true')
        {
            $proposal->shipping_display = 1;
        }
        else
        {
            $proposal->shipping_display = 0;
        }
        $proposal->save();

        Toastr::success('Shipping address status successfully changed.','Success');
        return redirect()->back();
    }

    public function duplicate($proposal_id)
    {
        $proposal                       = Proposal::where('id', $proposal_id)->first();
        $duplicateProposal              = new Proposal();
        $duplicateProposal->proposal_id = $this->proposalNumber();
        $duplicateProposal->customer_id = $proposal['customer_id'];
        $duplicateProposal->issue_date  = date('Y-m-d');
        $duplicateProposal->send_date   = null;
        $duplicateProposal->category_id = $proposal['category_id'];
        $duplicateProposal->status      = 0;
        $duplicateProposal->created_by  = $proposal['created_by'];
        $duplicateProposal->save();

        if($duplicateProposal)
        {
            $proposalProduct = ProposalProduct::where('proposal_id', $proposal_id)->get();
            foreach($proposalProduct as $product)
            {
                $duplicateProduct              = new ProposalProduct();
                $duplicateProduct->proposal_id = $duplicateProposal->id;
                $duplicateProduct->product_id  = $product->product_id;
                $duplicateProduct->quantity    = $product->quantity;
                $duplicateProduct->tax         = $product->tax;
                $duplicateProduct->discount    = $product->discount;
                $duplicateProduct->price       = $product->price;
                $duplicateProduct->save();
            }
        }
        Toastr::success('Proposal duplicated successfully.','Success');
        return redirect()->back();
    }

    public function convert($proposal_id)
    {
        $proposal             = Proposal::where('id', $proposal_id)->first();
        $proposal->is_convert = 1;
        $proposal->save();

        $convertInvoice              = new Invoice();
        $convertInvoice->invoice_id  = $this->invoiceNumber();
        $convertInvoice->customer_id = $proposal['customer_id'];
        $convertInvoice->issue_date  = date('Y-m-d');
        $convertInvoice->due_date    = date('Y-m-d');
        $convertInvoice->send_date   = null;
        $convertInvoice->category_id = $proposal['category_id'];
        $convertInvoice->status      = 0;
        $convertInvoice->created_by  = $proposal['created_by'];
        $convertInvoice->save();

        $proposal->converted_invoice_id = $convertInvoice->id;
        $proposal->save();

        if($convertInvoice)
        {
            $proposalProduct = ProposalProduct::where('proposal_id', $proposal_id)->get();
            foreach($proposalProduct as $product)
            {
                $duplicateProduct             = new InvoiceProduct();
                $duplicateProduct->invoice_id = $convertInvoice->id;
                $duplicateProduct->product_id = $product->product_id;
                $duplicateProduct->quantity   = $product->quantity;
                $duplicateProduct->tax        = $product->tax;
                $duplicateProduct->discount   = $product->discount;
                $duplicateProduct->price      = $product->price;
                $duplicateProduct->save();

                //inventory management (Quantity)
                Utility::total_quantity('minus',$duplicateProduct->quantity,$duplicateProduct->product_id);

                //Product Stock Report
                $type='invoice';
                $type_id = $convertInvoice->id;
                StockReport::where('type','=','invoice')->where('type_id' ,'=', $convertInvoice->id)->delete();
                $description= $duplicateProduct->quantity.''.__(' quantity sold in').' ' . Utility::proposalNumberFormat($proposal->proposal_id).' '.__('Proposal convert to invoice').' '.Utility::invoiceNumberFormat($convertInvoice->invoice_id);
                Utility::addProductStock( $duplicateProduct->product_id,$duplicateProduct->quantity,$type,$description,$type_id);
            }
        }

        Toastr::success('Proposal to invoice convert successfully.','Success');
        return redirect()->back();

    }

    public function statusChange(Request $request, $id)
    {
        $status           = $request->status;
        $proposal         = Proposal::find($id);
        $proposal->status = $status;
        $proposal->save();

        Toastr::success('Proposal status changed successfully.','Success');
        return redirect()->back();
    }

    public function previewProposal($template, $color)
    {
        $objUser  = \Auth::user();
        $settings = Utility::settings();
        $proposal = new Proposal();

        $customer                   = new \stdClass();
        $customer->email            = '<Email>';
        $customer->shipping_name    = '<Customer Name>';
        $customer->shipping_address = '<Address>';
        $customer->shipping_city    = '<City>';
        $customer->shipping_state   = '<State>';
        $customer->shipping_country = '<Country>';
        $customer->shipping_zip     = '<Zip>';
        $customer->shipping_phone   = '<Customer Phone Number>';
        $customer->billing_name     = '<Customer Name>';
        $customer->billing_address  = '<Address>';
        $customer->billing_city     = '<City>';
        $customer->billing_state    = '<State>';
        $customer->billing_country  = '<Country>';
        $customer->billing_zip      = '<Zip>';
        $customer->billing_phone    = '<Customer Phone Number>';

        $totalTaxPrice = 0;
        $taxesData     = [];

        $items = [];
        for($i = 1; $i <= 3; $i++)
        {
            $item           = new \stdClass();
            $item->name     = 'Item ' . $i;
            $item->quantity = 1;
            $item->tax      = 5;
            $item->discount = 50;
            $item->price    = 100;
            $item->unit     = 1;

            $taxes = [
                'Tax 1',
                'Tax 2',
            ];

            $itemTaxes = [];
            foreach($taxes as $k => $tax)
            {
                $taxPrice         = 10;
                $totalTaxPrice    += $taxPrice;
                $itemTax['name']  = 'Tax ' . $k;
                $itemTax['rate']  = '10 %';
                $itemTax['price'] = '$10';
                $itemTax['tax_price'] = 10;
                $itemTaxes[]      = $itemTax;
                if(array_key_exists('Tax ' . $k, $taxesData))
                {
                    $taxesData['Tax ' . $k] = $taxesData['Tax 1'] + $taxPrice;
                }
                else
                {
                    $taxesData['Tax ' . $k] = $taxPrice;
                }
            }
            $item->itemTax = $itemTaxes;
            $items[]       = $item;
        }

        $proposal->proposal_id = 1;
        $proposal->issue_date  = date('Y-m-d H:i:s');
        $proposal->due_date    = date('Y-m-d H:i:s');
        $proposal->itemData    = $items;

        $proposal->totalTaxPrice = 60;
        $proposal->totalQuantity = 3;
        $proposal->totalRate     = 300;
        $proposal->totalDiscount = 10;
        $proposal->taxesData     = $taxesData;
        $proposal->created_by     = $objUser->id;


        $proposal->customField = [];
        $customFields          = [];

        $preview    = 1;
        $color      = '#' . $color;
        $font_color = Utility::getFontColor($color);

        $logo         = asset(Storage::url('uploads/logo/'));
        $company_logo = Utility::getValByName('company_logo_dark');
        $proposal_logo = Utility::getValByName('proposal_logo');
        if(isset($proposal_logo) && !empty($proposal_logo))
        {
            $img = Utility::get_file('proposal_logo/') . $proposal_logo;
        }
        else{
            $img          = asset($logo . '/' . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-dark.png'));
        }


        return view('proposal.templates.' . $template, compact('proposal', 'preview', 'color', 'img', 'settings', 'customer', 'font_color', 'customFields'));
    }

    public function proposal($proposal_id)
    {

        $settings   = Utility::settings();
        $proposalId = Crypt::decrypt($proposal_id);
        $proposal   = Proposal::where('id', $proposalId)->first();

        $data  = DB::table('settings');
        $data  = $data->where('created_by', '=', $proposal->created_by);
        $data1 = $data->get();

        foreach($data1 as $row)
        {
            $settings[$row->name] = $row->value;
        }

        $customer = $proposal->customer;

        $items         = [];
        $totalTaxPrice = 0;
        $totalQuantity = 0;
        $totalRate     = 0;
        $totalDiscount = 0;
        $taxesData     = [];
        foreach($proposal->items as $product)
        {
            $item              = new \stdClass();
            $item->name        = !empty($product->product) ? $product->product->name : '';
            $item->quantity    = $product->quantity;
            $item->unit        = !empty($product->product) ? $product->product->unit_id : '';
            $item->tax         = $product->tax;
            $item->discount    = $product->discount;
            $item->price       = $product->price;
            $item->description = $product->description;

            $totalQuantity += $item->quantity;
            $totalRate     += $item->price;
            $totalDiscount += $item->discount;

            $taxes = Utility::tax($product->tax);

            $itemTaxes = [];
            if(!empty($item->tax))
            {
                foreach($taxes as $tax)
                {
                    $taxPrice      = Utility::taxRate($tax->rate, $item->price, $item->quantity,$item->discount);
                    $totalTaxPrice += $taxPrice;

                    $itemTax['name']  = $tax->name;
                    $itemTax['rate']  = $tax->rate . '%';
                    $itemTax['price'] = Utility::priceFormat($settings, $taxPrice);
                    $itemTax['tax_price'] =$taxPrice;
                    $itemTaxes[]      = $itemTax;


                    if(array_key_exists($tax->name, $taxesData))
                    {
                        $taxesData[$tax->name] = $taxesData[$tax->name] + $taxPrice;
                    }
                    else
                    {
                        $taxesData[$tax->name] = $taxPrice;
                    }

                }
                $item->itemTax = $itemTaxes;
            }
            else
            {
                $item->itemTax = [];
            }
            $items[] = $item;
        }

        $proposal->itemData      = $items;
        $proposal->totalTaxPrice = $totalTaxPrice;
        $proposal->totalQuantity = $totalQuantity;
        $proposal->totalRate     = $totalRate;
        $proposal->totalDiscount = $totalDiscount;
        $proposal->taxesData     = $taxesData;
        $proposal->customField   = CustomField::getData($proposal, 'proposal');
        $customFields            = [];
        if(!empty(\Auth::user()))
        {
            $customFields = CustomField::where('module', '=', 'proposal')->get();
        }

        $logo         = asset(Storage::url('uploads/logo/'));
        $company_logo = Utility::getValByName('company_logo_dark');
        $settings_data = \App\Models\Utility::settingsById($proposal->created_by);
        $proposal_logo = $settings_data['proposal_logo'];
        if(isset($proposal_logo) && !empty($proposal_logo))
        {
            $img = Utility::get_file('proposal_logo/') . $proposal_logo;
        }
        else{
            $img          = asset($logo . '/' . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-dark.png'));
        }

        if($proposal)
        {
            $color      = '#' . $settings['proposal_color'];
            $font_color = Utility::getFontColor($color);

            return view('proposal.templates.' . $settings['proposal_template'], compact('proposal', 'color', 'settings', 'customer', 'img', 'font_color', 'customFields'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }

    public function saveProposalTemplateSettings(Request $request)
    {
        $post = $request->all();
        unset($post['_token']);

        if(isset($post['proposal_template']) && (!isset($post['proposal_color']) || empty($post['proposal_color'])))
        {
            $post['proposal_color'] = "ffffff";
        }

        if($request->proposal_logo)
        {
            $dir = 'proposal_logo/';
            $proposal_logo = \Auth::user()->id . '_proposal_logo.png';
            $validation =[
                'mimes:'.'png',
                'max:'.'20480',
            ];
            $path = Utility::upload_file($request,'proposal_logo',$proposal_logo,$dir,$validation);

            if($path['flag']==0){
                return redirect()->back()->with('error', __($path['msg']));
            }
            $post['proposal_logo'] = $proposal_logo;
        }

        foreach($post as $key => $data)
        {
            \DB::insert(
                'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ', [
                    $data,
                    $key,
                    \Auth::user()->id,
                ]
            );
        }

        return redirect()->back()->with('success', __('Proposal Setting updated successfully'));
    }


    function invoiceNumber()
    {
        $latest = Invoice::latest()->first();
        if(!$latest)
        {
            return 1;
        }

        return $latest->invoice_id + 1;
    }

    public function items(Request $request)
    {
        $items = ProposalProduct::where('proposal_id', $request->proposal_id)->where('product_id', $request->product_id)->first();

        return json_encode($items);
    }

    public function invoiceLink($proposalID)
    {
        try {
            $id       = Crypt::decrypt($proposalID);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', __('Proposal Not Found.'));
        }
        $id             = Crypt::decrypt($proposalID);
        $proposal           =Proposal::find($id);
        $user_id        = $proposal->created_by;
        $user           = User::find($user_id);
        $customer = $proposal->customer;
        $iteams   = $proposal->items;
        $proposal->customField = CustomField::getData($proposal, 'proposal');
        $status   = Proposal::$statues;
        $customFields         = CustomField::where('module', '=', 'proposal')->get();

        return view('proposal.customer_proposal',compact('proposal','customer','iteams','customFields','status','user'));
    }

    public function export()
    {
        $name = 'proposal_' . date('Y-m-d i:h:s');
        $data = Excel::download(new ProposalExport(), $name . '.xlsx');  ob_end_clean();

        return $data;
    }

    public function tax($taxes)
    {
        $taxData = null;
        $proposal = new Proposal;
        if($taxData == null)
        {
            $taxArr = explode(',', $taxes);
            $taxes  = [];
            foreach($taxArr as $tax)
            {
                $taxes[] = $proposal->taxes($tax);
            }
            $taxData = $taxes;
        }

        return $taxData;
    }

}

