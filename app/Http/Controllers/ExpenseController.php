<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\Bill;
use App\Models\BillAccount;
use App\Models\BillPayment;
use App\Models\BillProduct;
use App\Models\ChartOfAccount;
use App\Models\Customer;
use App\Models\CustomField;
use App\Models\Employee;
use App\Models\Expense;
use App\Models\ProductService;
use App\Models\ProductServiceCategory;
use App\Models\Project;
use App\Models\StockReport;
use App\Models\Utility;
use App\Models\ActivityLog;
use App\Models\Vender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Brian2694\Toastr\Facades\Toastr;

class ExpenseController extends Controller
{

    function billNumber()
    {
        $latest = Bill::where('type' ,'=' ,'Bill')->latest()->first();
        if(!$latest)
        {
            return 1;
        }

        return $latest->bill_id + 1;
    }

    function expenseNumber()
    {
        $latest = Bill::where('type' ,'=' ,'Expense')->latest()->first();
        if(!$latest)
        {
            return 1;
        }

        return $latest->bill_id + 1;
    }

    public function employee(Request $request)
    {

        $employee = Employee::where('id', '=', $request->id)->first();

        return view('expense.employee_detail', compact('employee'));
    }

    public function vender(Request $request)
    {
        $vender = Vender::where('id', '=', $request->id)->first();

        return view('expense.vender_detail', compact('vender'));
    }
    public function customer(Request $request)
    {
        $customer = Customer::where('id', '=', $request->id)->first();
        return view('expense.customer_detail', compact('customer'));
    }

    public function product(Request $request)
    {
        $data['product']     = $product = ProductService::find($request->product_id);
        $data['unit']        = !empty($product->unit) ? $product->unit->name : '';
        $data['taxRate']     = $taxRate = !empty($product->tax_id) ? $product->taxRate($product->tax_id) : 0;
        $data['taxes']       = !empty($product->tax_id) ? $product->tax($product->tax_id) : 0;
        $salePrice           = $product->purchase_price;
        $quantity            = 1;
        $taxPrice            = ($taxRate / 100) * ($salePrice * $quantity);
        $data['totalAmount'] = ($salePrice * $quantity);

        return json_encode($data);
    }

    public function index(Request $request)
    {
            $vender = Vender::get()->pluck('name', 'id');
            $vender->prepend('Select Vendor', '');

            $category     = ProductServiceCategory::whereNotIn('type', ['product & service', 'income',])
                ->get()->pluck('name', 'id');
            $category->prepend('Select Category', '');

            $status = Bill::$statues;

            $query = Bill::where('type', '=', 'Expense');

            if(!empty($request->vender))
            {
                $query->where('vender_id', '=', $request->vender);
            }
            if(count(explode('to', $request->bill_date)) > 1)
            {
                $date_range = explode(' to ', $request->bill_date);
                $query->whereBetween('bill_date', $date_range);
            }
            elseif(!empty($request->bill_date))
            {
                $date_range = [$request->date , $request->bill_date];
                $query->whereBetween('bill_date', $date_range);
            }

            if(!empty($request->category))
            {
                $query->where('category_id', '=', $request->category);
            }


            $expenses = $query->with(['category'])->get();

            return view('expense.index', compact('expenses', 'vender', 'status','category'));
    }

    public function create($Id)
    {
            $customFields = CustomField::where('module', '=', 'bill')->get();
            $category     = ProductServiceCategory::whereNotIn('type', ['product & service', 'income',])
                ->get()->pluck('name', 'id');
            $category->prepend('Select Category', '');

            $expense_number = Utility::expenseNumberFormat($this->expenseNumber());

            // $employees        = Employee::get()->pluck('name', 'id');
            // $employees->prepend('Select Employee', '');

            $customers = Customer::get()->pluck('name', 'id');
            $customers->prepend('Select Customer', '');

            $venders     = Vender::get()->pluck('name', 'id');
            $venders->prepend('Select Vender', '');

            $product_services = ProductService::get()->pluck('name', 'id');
            $product_services->prepend('Select Item', '');

            $chartAccounts = ChartOfAccount::select(\DB::raw('CONCAT(code, " - ", name) AS code_name, id'))
                ->get()->pluck('code_name', 'id');
            $chartAccounts->prepend('Select Account', '');

            $accounts   = BankAccount::select('*', \DB::raw("CONCAT(bank_name,' ',holder_name) AS name"))
                        ->get()->pluck('name', 'id');


            return view('expense.create', compact('customers','venders', 'expense_number', 'product_services', 'category', 'customFields', 'Id','chartAccounts','accounts'));

    }


    public function store(Request $request)
    {
            $validator = \Validator::make(
                $request->all(), [
                    'payment_date' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages3 = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages3->first());
            }

            if (!empty($request->items) && empty($request->items[0]['item']) && empty($request->items[0]['chart_account_id']) && empty($request->items[0]['amount']))
            {
                $itemValidator = \Validator::make(
                    $request->all(), [
                        'item' => 'required'
                    ]
                );
                if ($itemValidator->fails()) {
                    $messages1 = $itemValidator->getMessageBag();
                    return redirect()->back()->with('error', $messages1->first());
                }
            }

            if (!empty($request->items) && empty($request->items[0]['chart_account_id'])  && !empty($request->items[0]['amount']) )
            {
                $accountValidator = \Validator::make(
                    $request->all(), [
                        'chart_account_id' => 'required'
                    ]
                );
                if ($accountValidator->fails()) {
                    $messages2 = $accountValidator->getMessageBag();
                    return redirect()->back()->with('error', $messages2->first());
                }

            }

            $expense                 = new Bill();
            $expense->bill_id        = $this->expenseNumber();
            if($request->type == 'Employee')
            {
                $expense->vender_id      = $request->employee_id;
            }
            elseif ($request->type == 'customer')
            {
                $expense->vender_id      = $request->customer_id;
            }
            else
            {
                $expense->vender_id      = $request->vender_id;
            }
            $expense->bill_date      = $request->payment_date;
            $expense->status         = 4;
            $expense->type           = 'Expense';
            $expense->user_type      = $request->type;
            $expense->due_date       = $request->payment_date;
            $expense->category_id    = !empty($request->category_id) ? $request->category_id :0;
            $expense->order_number   = 0;
            $expense->created_by     = \Auth::user()->id;
            $expense->save();

            $products = $request->items;


            $total_amount=0;

            for($i = 0; $i < count($products); $i++)
            {
                if(!empty($products[$i]['item']))
                {
                    $expenseProduct              = new BillProduct();
                    $expenseProduct->bill_id     = $expense->id;
                    $expenseProduct->product_id  = $products[$i]['item'];
                    $expenseProduct->quantity    = $products[$i]['quantity'];
                    $expenseProduct->tax         = $products[$i]['tax'];
                    $expenseProduct->discount    = $products[$i]['discount'];
                    $expenseProduct->price       = $products[$i]['price'];
                    $expenseProduct->description = $products[$i]['description'];
                    $expenseProduct->save();
                }

                $expenseTotal=0;
                if(!empty($products[$i]['chart_account_id'])){
                    $expenseAccount                    = new BillAccount();
                    $expenseAccount->chart_account_id  = $products[$i]['chart_account_id'];
                    $expenseAccount->price             = $products[$i]['amount'];
                    $expenseAccount->description       = $products[$i]['description'];
                    $expenseAccount->type              = 'Bill';
                    $expenseAccount->ref_id            = $expense->id;
                    $expenseAccount->save();
                    $expenseTotal= $expenseAccount->price;
                }


                //inventory management (Quantity)
                if(!empty($expenseProduct))
                {
                    Utility::total_quantity('plus',$expenseProduct->quantity,$expenseProduct->product_id);
                }

                //Product Stock Report
                if(!empty($products[$i]['item']))
                {
                    $type='bill';
                    $type_id = $expense->id;
                    $description=$products[$i]['quantity'].'  '.__('quantity purchase in bill').' '. Utility::expenseNumberFormat($expense->bill_id);
                    Utility::addProductStock( $products[$i]['item'],$products[$i]['quantity'],$type,$description,$type_id);
                    $total_amount += ($expenseProduct->quantity * $expenseProduct->price)+$expenseTotal ;

                }

            }

            $expensePayment                 = new BillPayment();
            $expensePayment->bill_id        =  $expense->id;
            $expensePayment->date           = $request->payment_date;
            $expensePayment->amount         = $request->totalAmount;
            $expensePayment->account_id     = $request->account_id;
            $expensePayment->payment_method = 0;
            $expensePayment->reference      = 'NULL';
            $expensePayment->description    ='NULL';
            $expensePayment->add_receipt    = 'NULL';
            $expensePayment->save();

            if(!empty($request->chart_account_id))
            {

                $expenseaccount= ProductServiceCategory::find($request->category_id);
                $chart_account = ChartOfAccount::find($expenseaccount->chart_account_id);
                $expenseAccount                    = new BillAccount();
                $expenseAccount->chart_account_id  = $chart_account['id'];
                $expenseAccount->price             = $total_amount;
                $expenseAccount->description       = $request->description;
                $expenseAccount->type              = 'Bill Category';
                $expenseAccount->ref_id            = $expense->id;
                $expenseAccount->save();
            }

            Toastr::success('Expense successfully created.','Success');
            return redirect()->route('expense.index', $expense->id);

    }

    public function show($ids)
    {
        try {
            $id       = Crypt::decrypt($ids);
        } catch (\Throwable $th) {
            Toastr::error('Expense Not Found.','Error');
            return redirect()->back();
        }

        $id   = Crypt::decrypt($ids);

        $expense = Bill::find($id);



        if(!empty($expense))
        {
            $expensePayment = BillPayment::where('bill_id', $expense->id)->first();

            if($expense->user_type == 'Employee')
            {
                $user      = $expense->employee;
            }
            elseif ($expense->user_type == 'customer')
            {
                $user      = $expense->customer;
            }
            else
            {
                $user      = $expense->vender;
            }



            $item      = $expense->items;
            $accounts  = $expense->accounts;
            $items     = [];
            if(!empty($item) && count($item) > 0)
            {
                foreach ($item as $k=>$val)
                {
                    if(!empty($accounts[$k]))
                    {
                        $val['chart_account_id']=$accounts[$k]['chart_account_id'];
                        $val['account_id']=$accounts[$k]['id'];
                        $val['amount']=$accounts[$k]['price'];
                    }
                    $items[]=$val;
                }
            }
            else{

                foreach ($accounts as $k=>$val){
                    $val1['chart_account_id']=$accounts[$k]['chart_account_id'];
                    $val1['account_id']=$accounts[$k]['id'];
                    $val1['amount']=$accounts[$k]['price'];
                    $items[]=$val1;

                }
            }

            return view('expense.view', compact('expense', 'user', 'items', 'expensePayment'));
        }
        
    }

    public function items(Request $request)
    {
        $items = BillProduct::where('bill_id', $request->bill_id)->where('product_id', $request->product_id)->first();
        return json_encode($items);
    }

    public function edit($ids)
    {
            try {
                $id       = Crypt::decrypt($ids);
            } catch (\Throwable $th) {
                Toastr::error('Expense Not Found.','Error');
                return redirect()->back();
            }

            $id       = Crypt::decrypt($ids);
            $expense     = Bill::find($id);

            if(!empty($expense))
            {
                $category     = ProductServiceCategory::whereNotIn('type', ['product & service', 'income',])
                    ->get()->pluck('name', 'id');
                $category->prepend('Select Category', '');
                $expense_number      = Utility::expenseNumberFormat($expense->bill_id);

                $venders          = Vender::get()->pluck('name', 'id');
                $venders->prepend('Select Vendor', '');

                $customers = Customer::pluck('name', 'id');
                $customers->prepend('Select Customer', '');

                $product_services = ProductService::get()->pluck('name', 'id');


                $chartAccounts = ChartOfAccount::select(\DB::raw('CONCAT(code, " - ", name) AS code_name, id'))
                    ->get()->pluck('code_name', 'id');
                $chartAccounts->prepend('Select Account', '');

                $bank_Account   = BankAccount::select('*', \DB::raw("CONCAT(bank_name,' ',holder_name) AS name"))
                    ->get()->pluck('name', 'id');


                //for item and account show in repeater
                $item      = $expense->items;
                $accounts  = $expense->accounts;
                $items     = [];
                if(!empty($item) && count($item) > 0)
                {
                    foreach ($item as $k=>$val)
                    {
                        if(!empty($accounts[$k]))
                        {
                            $val['chart_account_id']=$accounts[$k]['chart_account_id'];
                            $val['account_id']=$accounts[$k]['id'];
                            $val['amount']=$accounts[$k]['price'];
                        }
                        $items[]=$val;
                    }
                }
                else{
                    foreach ($accounts as $k=>$val){
                        $val1['chart_account_id']=$accounts[$k]['chart_account_id'];
                        $val1['account_id']=$accounts[$k]['id'];
                        $val1['amount']=$accounts[$k]['price'];
                        $items[]=$val1;

                    }
                }


                return view('expense.edit', compact('customers','venders', 'product_services', 'expense', 'expense_number', 'category',
                    'bank_Account','chartAccounts','items'));
            }
            else{
                Toastr::error('Expense Not Found.','Error');
                return redirect()->back();
            }

    }

    public function update(Request $request, $id)
    {
            $expense = Bill::find($id);


                $validator = \Validator::make(
                    $request->all(),
                    [
                        'bill_date' => 'required',
                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->route('expense.index')->with('error', $messages->first());
                }
                $expense->vender_id      = $request->vender_id;

                if($request->type == 'Employee')
                {
                    $expense->vender_id      = $request->employee_id;
                }
                elseif ($request->type == 'customer')
                {
                    $expense->vender_id      = $request->customer_id;
                }
                else
                {
                    $expense->vender_id      = $request->vender_id;
                }

                $expense->bill_date      = $request->bill_date;
                $expense->due_date       = $request->bill_date;
                $expense->order_number   = 0;
                $expense->user_type      = $request->type;
                $expense->category_id    = $request->category_id;
                $expense->save();
                $products = $request->items;

                $total_amount=0;

                for ($i = 0; $i < count($products); $i++)
                {
                    $expenseProduct = BillProduct::find($products[$i]['id']);
                    if ($expenseProduct == null)
                    {
                        $expenseProduct             = new BillProduct();
                        $expenseProduct->bill_id    = $expense->id;

                        if(isset($products[$i]['items']) ) {
                            Utility::total_quantity('plus', $products[$i]['quantity'], $products[$i]['items']);
                        }

                        $updatePrice= ($products[$i]['price']*$products[$i]['quantity'])+($products[$i]['itemTaxPrice'])-($products[$i]['discount']);
                        Utility::updateUserBalance('vendor', $request->vender_id, $updatePrice, 'debit');

                    }
                    else{

                        Utility::total_quantity('minus',$expenseProduct->quantity,$expenseProduct->product_id);
                    }

                    if (isset($products[$i]['items'])) {
                        $expenseProduct->product_id = $products[$i]['items'];
                        $expenseProduct->quantity    = $products[$i]['quantity'];
                        $expenseProduct->tax         = $products[$i]['tax'];
                        $expenseProduct->discount    = $products[$i]['discount'];
                        $expenseProduct->price       = $products[$i]['price'];
                        $expenseProduct->description = $products[$i]['description'];
                        $expenseProduct->save();
                    }


                    $expenseTotal=0;
                    if(!empty($products[$i]['chart_account_id'])){
                        $expenseAccount = BillAccount::find($products[$i]['id']);

                        if ($expenseAccount == null) {
                            $expenseAccount                    = new BillAccount();
                            $expenseAccount->chart_account_id = $products[$i]['chart_account_id'];
                        }
                        else{
                            $expenseAccount->chart_account_id = $products[$i]['chart_account_id'];
                        }
                        $expenseAccount->price             = $products[$i]['amount'];
                        $expenseAccount->description       = $products[$i]['description'];
                        $expenseAccount->type              = 'Bill';
                        $expenseAccount->ref_id            = $expense->id;
                        $expenseAccount->save();
                        $expenseTotal= $expenseAccount->price;
                    }

                    if ($products[$i]['id']>0) {
                        Utility::total_quantity('plus',$products[$i]['quantity'],$expenseProduct->product_id);
                    }

                    //Product Stock Report
                    $type='bill';
                    $type_id = $expense->id;
                    StockReport::where('type','=','bill')->where('type_id','=',$expense->id)->delete();
                    $description=$products[$i]['quantity'].'  '.__(' quantity purchase in bill').' '. Utility::expenseNumberFormat($expense->bill_id);

                    if(isset($products[$i]['items']) ){
                        Utility::addProductStock( $products[$i]['items'],$products[$i]['quantity'],$type,$description,$type_id);
                    }

                    $total_amount += ($expenseProduct->quantity * $expenseProduct->price)+$expenseTotal ;

                }

                $expensePayment                 = new BillPayment();
                $expensePayment->bill_id        =  $expense->id;
                $expensePayment->date           = $request->bill_date;
                $expensePayment->amount         = $request->totalAmount;
                $expensePayment->account_id     = $request->account_id;
                $expensePayment->payment_method = 0;
                $expensePayment->reference      = 'NULL';
                $expensePayment->description    ='NULL';
                $expensePayment->add_receipt    = 'NULL';
                $expensePayment->save();

                if(!empty($request->chart_account_id))
                {
                    $expenseaccount= ProductServiceCategory::find($request->category_id);
                    $chart_account = ChartOfAccount::find($expenseaccount->chart_account_id);
                    $expenseAccount                    = new BillAccount();
                    $expenseAccount->chart_account_id  = $chart_account['id'];
                    $expenseAccount->price             = $total_amount;
                    $expenseAccount->description       = $request->description;
                    $expenseAccount->type              = 'Bill Category';
                    $expenseAccount->ref_id            = $expense->id;
                    $expenseAccount->save();
                }
                Toastr::success('Expense successfully updated.','Success');
                return redirect()->route('expense.index');


    }

    public function expense($expense_id)
    {

        $settings = Utility::settings();
        try {
            $expenseId       = Crypt::decrypt($expense_id);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', __('Bill Not Found.'));
        }
        $expenseId   = Crypt::decrypt($expense_id);

        $expense  = Bill::where('id', $expenseId)->first();
        $data  = DB::table('settings');
        $data  = $data->where('created_by', '=', $expense->created_by);
        $data1 = $data->get();

        foreach($data1 as $row)
        {
            $settings[$row->name] = $row->value;
        }

        $vendor = $expense->vender;

        $totalTaxPrice = 0;
        $totalQuantity = 0;
        $totalRate     = 0;
        $totalDiscount = 0;
        $taxesData     = [];
        $items         = [];

        foreach($expense->items as $product)
        {

            $item              = new \stdClass();
            $item->name        = !empty($product->product()) ? $product->product()->name : '';
            $item->quantity    = $product->quantity;
            $item->tax         = $product->tax;
            $item->discount    = $product->discount;
            $item->price       = $product->price;
            $item->description = $product->description;

            $totalQuantity += $item->quantity;
            $totalRate     += $item->price;
            $totalDiscount += $item->discount;

            $taxes     = Utility::tax($product->tax);
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

        $expense->itemData      = $items;
        $expense->totalTaxPrice = $totalTaxPrice;
        $expense->totalQuantity = $totalQuantity;
        $expense->totalRate     = $totalRate;
        $expense->totalDiscount = $totalDiscount;
        $expense->taxesData     = $taxesData;
        $expense->customField   = CustomField::getData($expense, 'bill');

        $logo         = asset(Storage::url('uploads/logo/'));
        $company_logo = Utility::getValByName('company_logo_dark');
        $settings_data = \App\Models\Utility::settingsById($expense->created_by);
        $expense_logo = $settings_data['bill_logo'];
        if(isset($expense_logo) && !empty($expense_logo))
        {
            $img = Utility::get_file('bill_logo/') . $expense_logo;
        }
        else{
            $img          = asset($logo . '/' . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-dark.png'));
        }

        if($expense)
        {
            $color      = '#' . $settings['bill_color'];
            $font_color = Utility::getFontColor($color);

            return view('bill.templates.' . $settings['bill_template'], compact('expense', 'color', 'settings', 'vendor', 'img', 'font_color'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }

    public function productDestroy(Request $request)
    {
        $expenseProduct=BillProduct::find($request->id);
        $expense=Bill::find($expenseProduct->bill_id);

        Utility::updateUserBalance('vendor', $expense->vender_id, $request->amount, 'credit');

        BillProduct::where('id', '=', $request->id)->delete();

        Toastr::success('Expense product successfully deleted.','Success');
        return redirect()->back();

    }

    public function destroy($id)
    {
            $expense= Bill::find($id);

            $expensepayments = $expense->payments;

            foreach($expensepayments as $key => $value)
            {
                Utility::bankAccountBalance($value->account_id, $value->amount, 'credit');

                $expensepayment = BillPayment::find($value->id)->first();
                $expensepayment->delete();
            }
            $expense->delete();

            if($expense->vender_id != 0 && $expense->status!=0)
            {
                Utility::updateUserBalance('vendor', $expense->vender_id, $expense->getDue(), 'credit');
            }
            BillProduct::where('bill_id', '=', $expense->id)->delete();

            BillAccount::where('ref_id', '=', $expense->id)->delete();

            Toastr::success('Expense successfully deleted.','Success');
            return redirect()->route('expense.index');


    }






}
