<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\BillPayment;
use App\Models\Payment;
use App\Models\ProductServiceCategory;
use App\Models\Transaction;
use App\Models\Utility;
use App\Models\Vender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Brian2694\Toastr\Facades\Toastr;

class PaymentController extends Controller
{

    public function index(Request $request)
    {
            $vender = Vender::get()->pluck('name', 'id');
            $vender->prepend('Select Vendor', '');

            $account = BankAccount::get()->pluck('holder_name', 'id');
            $account->prepend('Select Account', '');

            $category = ProductServiceCategory::where('type', '=', 'expense')->get()->pluck('name', 'id');
            $category->prepend('Select Category', '');


            $query = Payment::query();

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

            if(!empty($request->vender))
            {
                $query->where('id', '=', $request->vender);
            }
            if(!empty($request->account))
            {
                $query->where('account_id', '=', $request->account);
            }

            if(!empty($request->category))
            {
                $query->where('category_id', '=', $request->category);
            }

            $payments = $query->with(['category','vender','bankAccount'])->get();

            return view('payment.index', compact('payments', 'account', 'category', 'vender'));

    }


    public function create()
    {
            $venders = Vender::get()->pluck('name', 'id');
            $venders->prepend('--', 0);
            $categories = ProductServiceCategory::where('type', '=', 'expense')->get()->pluck('name', 'id');
            $accounts   = BankAccount::select('*', \DB::raw("CONCAT(bank_name,' ',holder_name) AS name"))->get()->pluck('name', 'id');

            return view('payment.create', compact('venders', 'categories', 'accounts'));

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

            $payment                 = new Payment();
            $payment->date           = $request->date;
            $payment->amount         = $request->amount;
            $payment->account_id     = $request->account_id;
            $payment->vender_id      = $request->vender_id;
            $payment->category_id    = $request->category_id;
            $payment->payment_method = 0;
            $payment->reference      = $request->reference;
            if(!empty($request->add_receipt))
            {

                $fileName = time() . "_" . $request->add_receipt->getClientOriginalName();
                $payment->add_receipt = $fileName;
                $dir        = 'uploads/payment';
                $path = Utility::upload_file($request,'add_receipt',$fileName,$dir,[]);
                if($path['flag']==0){
                    return redirect()->back()->with('error', __($path['msg']));
                }
            }

            $payment->description    = $request->description;
            $payment->created_by     = \Auth::user()->id;
            $payment->save();

            $category            = ProductServiceCategory::where('id', $request->category_id)->first();
            $payment->payment_id = $payment->id;
            $payment->type       = 'Payment';
            $payment->category   = $category->name;
            $payment->user_id    = $payment->vender_id;
            $payment->user_type  = 'Vender';
            $payment->account    = $request->account_id;

            Transaction::addTransaction($payment);

            $vender          = Vender::where('id', $request->vender_id)->first();
            $payment         = new BillPayment();
            $payment->name   = !empty($vender) ? $vender['name'] : '' ;
            $payment->method = '-';
            $payment->date   = Utility::dateFormat($request->date);
            $payment->amount = Utility::priceFormat($request->amount);
            $payment->bill   = '';

            if(!empty($vender))
            {
                Utility::userBalance('vendor', $vender->id, $request->amount, 'debit');
            }

            Utility::bankAccountBalance($request->account_id, $request->amount, 'debit');

            Toastr::success('Payment successfully created.','Success');
            return redirect()->route('payment.index');
    }

    public function edit(Payment $payment)
    {
            $venders = Vender::get()->pluck('name', 'id');
            $venders->prepend('--', 0);
            $categories = ProductServiceCategory::get()->where('type', '=', 'expense')->pluck('name', 'id');

            $accounts = BankAccount::select('*', \DB::raw("CONCAT(bank_name,' ',holder_name) AS name"))->get()->pluck('name', 'id');

            return view('payment.edit', compact('venders', 'categories', 'accounts', 'payment'));

    }


    public function update(Request $request, Payment $payment)
    {
            $validator = \Validator::make(
                $request->all(), [
                                   'date' => 'required',
                                   'amount' => 'required',
                                   'account_id' => 'required',
                                   'vender_id' => 'required',
                                   'category_id' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $vender = Vender::where('id', $request->vender_id)->first();
            if(!empty($vender))
            {
                Utility::userBalance('vendor', $vender->id, $payment->amount, 'credit');
            }
            Utility::bankAccountBalance($payment->account_id, $payment->amount, 'credit');

            $payment->date           = $request->date;
            $payment->amount         = $request->amount;
            $payment->account_id     = $request->account_id;
            $payment->vender_id      = $request->vender_id;
            $payment->category_id    = $request->category_id;
            $payment->payment_method = 0;
            $payment->reference      = $request->reference;

            if(!empty($request->add_receipt))
            {

                if($payment->add_receipt)
                {
                    $path = storage_path('uploads/payment' . $payment->add_receipt);
                    if(file_exists($path))
                    {
                        \File::delete($path);
                    }
                }
                $fileName = time() . "_" . $request->add_receipt->getClientOriginalName();
                $payment->add_receipt = $fileName;
                $dir        = 'uploads/payment';
                $path = Utility::upload_file($request,'add_receipt',$fileName,$dir,[]);
                if($path['flag']==0){
                    Toastr::error($path['msg'],'Error');
                    return redirect()->back();
                }
            }

            $payment->description    = $request->description;
            $payment->save();

            $category            = ProductServiceCategory::where('id', $request->category_id)->first();
            $payment->category   = $category->name;
            $payment->payment_id = $payment->id;
            $payment->type       = 'Payment';
            $payment->account    = $request->account_id;
            Transaction::editTransaction($payment);

            if(!empty($vender))
            {
                Utility::userBalance('vendor', $vender->id, $request->amount, 'debit');
            }

            Utility::bankAccountBalance($request->account_id, $request->amount, 'debit');

            Toastr::success('Payment successfully updated.','Success');
            return redirect()->route('payment.index');

    }


    public function destroy(Payment $payment)
    {
        $payment->delete();
        $type = 'Payment';
        $user = 'Vender';
        Transaction::destroyTransaction($payment->id, $type, $user);

        if($payment->vender_id != 0)
        {
            Utility::userBalance('vendor', $payment->vender_id, $payment->amount, 'credit');
        }
        Utility::bankAccountBalance($payment->account_id, $payment->amount, 'credit');

        Toastr::success('Payment successfully deleted.','Success');
        return redirect()->route('payment.index');

    }
}
