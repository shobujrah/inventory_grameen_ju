<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\Customer;
use App\Models\InvoicePayment;
use App\Models\ProductServiceCategory;
use App\Models\Revenue;
use App\Models\Transaction;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Brian2694\Toastr\Facades\Toastr;

class RevenueController extends Controller
{

    public function index(Request $request)
    {
        $customer = Customer::get()->pluck('name', 'id');
        $customer->prepend('Select Customer', '');

        $account = BankAccount::get()->pluck('holder_name', 'id');
        $account->prepend('Select Account', '');

        $category = ProductServiceCategory::where('type', '=','income')->get()->pluck('name', 'id');
        $category->prepend('Select Category', '');


        $query = Revenue::query();

        if(count(explode('to', $request->date)) > 1)
        {
            $date_range = explode(' to ', $request->date);
            $query->whereBetween('date', $date_range);
        }
        elseif(!empty($request->date))
        {
            $date_range = [$request->date , $request->date];
            $query->whereBetween('date', $date_range);
        }

        if(!empty($request->customer))
        {
            $query->where('customer_id', '=', $request->customer);
        }
        if(!empty($request->account))
        {
            $query->where('account_id', '=', $request->account);
        }

        if(!empty($request->category))
        {
            $query->where('category_id', '=', $request->category);
        }

        if(!empty($request->payment))
        {
            $query->where('payment_method', '=', $request->payment);
        }
        $revenues = $query->with(['category','customer','bankAccount'])->get();

        return view('revenue.index', compact('revenues', 'customer', 'account', 'category'));

    }


    public function create()
    {
        $customers = Customer::get()->pluck('name', 'id');
        $customers->prepend('--', 0);
        $categories = ProductServiceCategory::where('type', '=','income')->get()->pluck('name', 'id');
        $accounts   = BankAccount::select('*', \DB::raw("CONCAT(bank_name,' ',holder_name) AS name"))->get()->pluck('name', 'id');

        return view('revenue.create', compact('customers', 'categories', 'accounts'));

    }


    public function store(Request $request)
    {

            $validator = \Validator::make(
                $request->all(), [
                                   'date' => 'required',
                                   'amount' => 'required',
                                   'account_id' => 'required',
                                   'category_id' => 'required',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $revenue                 = new Revenue();
            $revenue->date           = $request->date;
            $revenue->amount         = $request->amount;
            $revenue->account_id     = $request->account_id;
            $revenue->customer_id    = $request->customer_id;
            $revenue->category_id    = $request->category_id;
            $revenue->payment_method = 0;
            $revenue->reference      = $request->reference;
            $revenue->description    = $request->description;
            if(!empty($request->add_receipt))
            {
                $fileName = time() . "_" . $request->add_receipt->getClientOriginalName();
                $revenue->add_receipt = $fileName;
                $dir        = 'uploads/revenue';
                $url = '';
                $path = Utility::upload_file($request,'add_receipt',$fileName,$dir,[]);
                if($path['flag']==0){
                    return redirect()->back()->with('error', __($path['msg']));
                }
            }

            $revenue->created_by     = \Auth::user()->id;
            $revenue->save();

            $category            = ProductServiceCategory::where('id', $request->category_id)->first();
            $revenue->payment_id = $revenue->id;
            $revenue->type       = 'Revenue';
            $revenue->category   = $category->name;
            $revenue->user_id    = $revenue->customer_id;
            $revenue->user_type  = 'Customer';
            $revenue->account    = $request->account_id;
            Transaction::addTransaction($revenue);

            $customer         = Customer::where('id', $request->customer_id)->first();
            $payment          = new InvoicePayment();
            $payment->name    = !empty($customer) ? $customer['name'] : '';
            $payment->date    = Utility::dateFormat($request->date);
            $payment->amount  = \App\Models\Utility::priceFormat($request->amount);
            $payment->invoice = '';

            if(!empty($customer))
            {
                Utility::userBalance('customer', $customer->id, $revenue->amount, 'credit');
            }

            Utility::bankAccountBalance($request->account_id, $revenue->amount, 'credit');

            Toastr::success('Revenue successfully created.','Success');
            return redirect()->route('revenue.index');

    }

    public function show()
    {
        return redirect()->route('revenue.index');
    }

    public function edit(Revenue $revenue)
    {
        $customers = Customer::get()->pluck('name', 'id');
        $customers->prepend('--', 0);
        $categories = ProductServiceCategory::where('type', '=', 'income')->get()->pluck('name', 'id');
        $accounts   = BankAccount::select('*', \DB::raw("CONCAT(bank_name,' ',holder_name) AS name"))->get()->pluck('name', 'id');

        return view('revenue.edit', compact('customers', 'categories', 'accounts', 'revenue'));

    }

    public function update(Request $request, Revenue $revenue)
    {
            $validator = \Validator::make(
                $request->all(), [
                                   'date' => 'required',
                                   'amount' => 'required',
                                   'account_id' => 'required',
                                   'category_id' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $customer = Customer::where('id', $request->customer_id)->first();

            if(!empty($customer))
            {
                Utility::userBalance('customer', $customer->id, $revenue->amount, 'debit');
            }

            Utility::bankAccountBalance($revenue->account_id, $revenue->amount, 'debit');

            $revenue->date           = $request->date;
            $revenue->amount         = $request->amount;
            $revenue->account_id     = $request->account_id;
            $revenue->customer_id    = $request->customer_id;
            $revenue->category_id    = $request->category_id;
            $revenue->payment_method = 0;
            $revenue->reference      = $request->reference;
            $revenue->description    = $request->description;
            if(!empty($request->add_receipt))
            {

                if($revenue->add_receipt)
                {
                    $path = storage_path('uploads/revenue/' . $revenue->add_receipt);
                    if(file_exists($path))
                    {
                        \File::delete($path);
                    }
                }
                $fileName = time() . "_" . $request->add_receipt->getClientOriginalName();
                $revenue->add_receipt = $fileName;
                $dir        = 'uploads/revenue';
                $path = Utility::upload_file($request,'add_receipt',$fileName,$dir,[]);
                if($path['flag']==0){
                    return redirect()->back()->with('error', __($path['msg']));
                }
            }

            $revenue->save();

            $category            = ProductServiceCategory::where('id', $request->category_id)->first();
            $revenue->category   = $category->name;
            $revenue->payment_id = $revenue->id;
            $revenue->type       = 'Revenue';
            $revenue->account    = $request->account_id;
            Transaction::editTransaction($revenue);

            if(!empty($customer))
            {
                Utility::userBalance('customer', $customer->id, $request->amount, 'credit');
            }

            Utility::bankAccountBalance($request->account_id, $request->amount, 'credit');

            Toastr::success('Revenue successfully updated.','Success');
            return redirect()->route('revenue.index');

    }

    public function destroy(Revenue $revenue)
    {
                $revenue->delete();
                $type = 'Revenue';
                $user = 'Customer';
                Transaction::destroyTransaction($revenue->id, $type, $user);

                if($revenue->customer_id != 0)
                {
                    Utility::userBalance('customer', $revenue->customer_id, $revenue->amount, 'debit');
                }

                Utility::bankAccountBalance($revenue->account_id, $revenue->amount, 'debit');

                Toastr::success('Revenue successfully deleted.','Success');
                return redirect()->route('revenue.index');

    }

}
