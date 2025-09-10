<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;
use Carbon\CarbonPeriod;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Utility extends Model
{

    public static function dateFormat($date)
    {
        return date('M j, Y', strtotime($date));
    }

    public static function currencySymbol()
    {
        return '৳';
    }

    public static function priceFormat($price)
    {
        return number_format($price, 2).'৳';
    }


    public static function priceFormatEng($price)
    {
        return number_format($price, 2).' BDT ';
    }


    public static function customerNumberFormat($number)
    {
        return '#CUST' . sprintf("%05d", $number);
    }

    public static function proposalNumberFormat($number)
    {
        return '#PROP' . sprintf("%05d", $number);
    }

    public static function invoiceNumberFormat($number)
    {
        return '#INVO' . sprintf("%05d", $number);
    }

    public static function venderNumberFormat($number)
    {
        return '#VEND' . sprintf("%05d", $number);
    }

    public static function billNumberFormat($number)
    {
        return '#BILL' . sprintf("%05d", $number);
    }

    public static function expenseNumberFormat($number)
    {
        return '#EXP' . sprintf("%05d", $number);
    }

    public static function journalNumberFormat($number)
    {
        return '#JUR' . sprintf("%05d", $number);
    }

    public static function countVenders()
    {
        return Vender::count();
    }

    public static function total_quantity($type, $quantity, $product_id)
    {

        $product      = ProductService::find($product_id);
        if(($product->type == 'product'))
        {
            $pro_quantity = $product->quantity;

            if($type == 'minus')
            {
                $product->quantity = $pro_quantity - $quantity;
            }
            else
            {
                $product->quantity = $pro_quantity + $quantity;


            }
            $product->save();
        }

    }

    public static function addProductStock($product_id, $quantity, $type, $description,$type_id)
    {

        $stocks             = new StockReport();
        $stocks->product_id = $product_id;
        $stocks->quantity	 = $quantity;
        $stocks->type = $type;
        $stocks->type_id = $type_id;
        $stocks->description = $description;
        $stocks->created_by =\Auth::user()->id;
        $stocks->save();
    }

    public static function bankAccountBalance($id, $amount, $type)
    {
        $bankAccount = BankAccount::find($id);
        if($bankAccount)
        {
            if($type == 'credit')
            {
                $oldBalance                   = $bankAccount->opening_balance;
                $bankAccount->opening_balance = $oldBalance + $amount;
                $bankAccount->save();
            }
            elseif($type == 'debit')
            {
                $oldBalance                   = $bankAccount->opening_balance;
                $bankAccount->opening_balance = $oldBalance - $amount;
                $bankAccount->save();
            }
        }

    }

    public static function updateUserBalance($users, $id, $amount, $type)
    {
        if($users == 'customer')
        {
            $user = Customer::find($id);
        }
        else
        {
            $user = Vender::find($id);
        }

        if(!empty($user))
        {
            if($type == 'credit')
            {
                $oldBalance    = $user->balance;
                $userBalance = $oldBalance - $amount;
                $user->balance = $userBalance;
                $user->save();
            }
            elseif($type == 'debit')
            {
                $oldBalance    = $user->balance;
                $userBalance = $oldBalance + $amount;
                $user->balance = $userBalance;
                $user->save();
            }
        }
    }

    public static function userBalance($users, $id, $amount, $type)
    {
        if($users == 'customer')
        {
            $user = Customer::find($id);
        }
        else
        {
            $user = Vender::find($id);
        }

        if(!empty($user))
        {
            if($type == 'credit')
            {
                $oldBalance    = $user->balance;
                $userBalance = $oldBalance + $amount;
                $user->balance = $userBalance;
                $user->save();
            }
            elseif($type == 'debit')
            {
                $oldBalance    = $user->balance;
                $userBalance = $oldBalance - $amount;
                $user->balance = $userBalance;
                $user->save();
            }
        }
    }

    public static function upload_file($request,$key_name,$name,$path,$custom_validation =[])
    {
        try{
                $max_size = "20480000000";
                $mimes =  "jpg,jpeg,png,xlsx,xls,csv,pdf";

                $file = $request->$key_name;

                $validation =[
                    'mimes:'.$mimes,
                    'max:'.$max_size,
                ];

                $validator = \Validator::make($request->all(), [
                    $key_name =>$validation
                ]);

                if($validator->fails()){

                    $res = [
                        'flag' => 0,
                        'msg' => $validator->messages()->first(),
                    ];

                    return $res;
                } else {

                    $name = $name;

                    $request->$key_name->move(storage_path('app/public/' . $path), $name);
                    $path = $path.$name;

                    $res = [
                        'flag' => 1,
                        'msg'  =>'success',
                        'url'  => $path
                    ];
                    return $res;
                }

        }catch(\Exception $e){

            $res = [
                'flag' => 0,
                'msg' => $e->getMessage(),
            ];
            return $res;
        }
    }

    public static function getAccountBalance($account_id,$start_date=null,$end_date=null)
    {
        if(!empty($start_date) && !empty($end_date))
        {
            $start = $start_date;
            $end   = $end_date;
        }
        else
        {
            $start = date('Y-01-01');
            $end   = date('Y-m-d', strtotime('+1 day'));
        }

        $invoice_product = ProductService::where('sale_chartaccount_id',$account_id)->get()->pluck('id');
        $invoiceData = InvoiceProduct::select(DB::raw('sum(price * quantity) as amount'));
        if(!empty($start_date) && !empty($end_date))
        {
            $invoiceData->where('created_at', '>=', $start);
            $invoiceData->where('created_at', '<=', $end);
        }
        $invoiceData=$invoiceData->whereIn('product_id',$invoice_product)->first();
        $invoiceAmount=!empty($invoiceData->amount)?$invoiceData->amount:0;

        $getAccount = BankAccount::where('chart_account_id',$account_id)->get()->pluck('id');

        $invoicePaymentAmount = InvoicePayment::whereIn('account_id',$getAccount);
        if(!empty($start_date) && !empty($end_date))
        {
            $invoicePaymentAmount->where('date', '>=', $start);
            $invoicePaymentAmount->where('date', '<=', $end);
        }
        $invoicePaymentAmount= $invoicePaymentAmount->sum('amount');

        $revenueAmount = Revenue::whereIn('account_id',$getAccount);
        if(!empty($start_date) && !empty($end_date))
        {
            $revenueAmount->where('date', '>=', $start);
            $revenueAmount->where('date', '<=', $end);
        }
        
        $revenueAmount= $revenueAmount->sum('amount');

        $bill_product = ProductService::where('expense_chartaccount_id',$account_id)->get()->pluck('id');
        $billData = BillProduct::select(DB::raw('sum(price * quantity) as amount'));
        if(!empty($start_date) && !empty($end_date))
        {
            $billData->where('created_at', '>=', $start);
            $billData->where('created_at', '<=', $end);
        }
        $billData=$billData->whereIn('product_id',$bill_product)->first();
        $billProductAmount=!empty($billData->amount)?$billData->amount:0;


        $billAmount = BillAccount::where('chart_account_id',$account_id);
        if(!empty($start_date) && !empty($end_date))
        {
            $billAmount->where('created_at', '>=', $start);
            $billAmount->where('created_at', '<=', $end);
        }
        $billAmount= $billAmount->sum('price');


        $billPaymentAmount= BillPayment::whereIn('account_id',$getAccount);
        if(!empty($start_date) && !empty($end_date))
        {
            $billPaymentAmount->where('date', '>=', $start);
            $billPaymentAmount->where('date', '<=', $end);
        }
        $billPaymentAmount= $billPaymentAmount->sum('amount');


        $paymentAmount = Payment::whereIn('account_id',$getAccount);
        if(!empty($start_date) && !empty($end_date))
        {
            $paymentAmount->where('date', '>=', $start);
            $paymentAmount->where('date', '<=', $end);
        }
        $paymentAmount= $paymentAmount->sum('amount');

        $journalCredit = JournalItem::select('journal_entries.journal_id', 'journal_entries.date as transaction_date', 'journal_items.*')
        ->leftjoin('journal_entries', 'journal_entries.id', 'journal_items.journal')->where('account', $account_id);
        $journalCredit->where('journal_items.created_at', '>=', $start);
        $journalCredit->where('journal_items.created_at', '<=', $end);
        $journalCredit = $journalCredit->sum('credit');

        $journalDebit = JournalItem::select('journal_entries.journal_id', 'journal_entries.date as transaction_date', 'journal_items.*')
        ->leftjoin('journal_entries', 'journal_entries.id', 'journal_items.journal')->where('account', $account_id);
        $journalDebit->where('journal_items.created_at', '>=', $start);
        $journalDebit->where('journal_items.created_at', '<=', $end);
        $journalDebit = $journalDebit->sum('debit');
        $balance   =  ($invoiceAmount + $invoicePaymentAmount + $revenueAmount  + $journalCredit) - ($journalDebit + $billProductAmount + $billAmount + $billPaymentAmount + $paymentAmount);
        return $balance;
    }

    public static function totalTaxRate($taxes)
    {
        $taxRateData = null;

        if($taxRateData == null)
        {
            $taxArr  = explode(',', $taxes);
            $taxRate = 0;
    
            foreach($taxArr as $tax)
            {
                $tax     = self::taxes($tax);
                $taxRate += !empty($tax->rate) ? $tax->rate : 0;
            }

            $taxRateData = $taxRate;
        }
        return $taxRateData;
    }

    public static function taxes($tax)
    {
        $taxes = null;
        if($taxes == null)
        {
            $tax = Tax::find($tax);
            $taxes = $tax;
        }
        return $taxes;
    }

    public static function getAccountData($account_id,$start_date=null,$end_date=null)
    {

        if(!empty($start_date) && !empty($end_date))
        {
            $start = $start_date;
            $end   = $end_date;
        }
        else
        {
                $start = date('Y-01-01');
                $end   = date('Y-m-d', strtotime('+1 day'));
        }

        //For Invoice Product Create
        $invoice_product = ProductService::where('sale_chartaccount_id',$account_id)->get()->pluck('id');
        $invoice = InvoiceProduct::whereIn('product_id',$invoice_product);
        if(!empty($start_date) && !empty($end_date))
        {
            $invoice->where('created_at', '>=', $start);
            $invoice->where('created_at', '<=', $end);
        }
        $invoice= $invoice->get();


        $getAccount = BankAccount::where('chart_account_id',$account_id)->get()->pluck('id');
        //For Invoice Payment
        $invoicePayment = InvoicePayment::whereIn('account_id',$getAccount);
        if(!empty($start_date) && !empty($end_date))
        {
            $invoicePayment->where('date', '>=', $start);
            $invoicePayment->where('date', '<=', $end);
        }
        $invoicePayment= $invoicePayment->get();

        //For Revenue
        $revenue = Revenue::whereIn('account_id',$getAccount);
        if(!empty($start_date) && !empty($end_date))
        {
            $revenue->where('date', '>=', $start);
            $revenue->where('date', '<=', $end);
        }
        $revenue= $revenue->get();

        //For Bill

        $bill_product = ProductService::where('expense_chartaccount_id',$account_id)->get()->pluck('id');
        $bill = BillProduct::whereIn('product_id',$bill_product);
        if(!empty($start_date) && !empty($end_date))
        {
            $bill->where('created_at', '>=', $start);
            $bill->where('created_at', '<=', $end);
        }
        $bill= $bill->get();

        $billData = BillAccount::where('chart_account_id',$account_id);
        if(!empty($start_date) && !empty($end_date))
        {
            $billData->where('created_at', '>=', $start);
            $billData->where('created_at', '<=', $end);
        }
        $billData= $billData->get();

        $billPayment= BillPayment::whereIn('account_id',$getAccount);
        if(!empty($start_date) && !empty($end_date))
        {
            $billPayment->where('date', '>=', $start);
            $billPayment->where('date', '<=', $end);
        }
        $billPayment= $billPayment->get();


        $payment = Payment::whereIn('account_id',$getAccount);
        if(!empty($start_date) && !empty($end_date))
        {
            $payment->where('date', '>=', $start);
            $payment->where('date', '<=', $end);
        }
        $payment= $payment->get();


        $journalItems = JournalItem::select('journal_entries.journal_id', 'journal_entries.date as transaction_date', 'journal_items.*')
        ->leftjoin('journal_entries', 'journal_entries.id', 'journal_items.journal')->where('account', $account_id);
        $journalItems->where('journal_items.created_at', '>=', $start);
        $journalItems->where('journal_items.created_at', '<=', $end);
        $journalItems = $journalItems->get();

        $data=[];
        $data['invoice'] = $invoice;
        $data['invoicepayment'] = $invoicePayment;
        $data['revenue'] = $revenue;
        $data['bill'] = $bill;
        $data['billdata'] = $billData;
        $data['billpayment'] = $billPayment;
        $data['payment'] = $payment;
        $data['journalItem'] = $journalItems;

        return $data;
    }
    

    public static function trialBalance($account_id, $start, $end)
    {
        $journalItem = JournalItem::select('chart_of_accounts.id', 'chart_of_accounts.code', 'chart_of_accounts.name', \DB::raw('sum(debit) as totalDebit'), \DB::raw('sum(credit) as totalCredit'))
        ->leftJoin('journal_entries', 'journal_entries.id', '=', 'journal_items.journal')
        ->leftJoin('chart_of_accounts', 'journal_items.account', '=', 'chart_of_accounts.id')
        ->leftJoin('chart_of_account_types', 'chart_of_accounts.type', '=', 'chart_of_account_types.id')
        ->where('chart_of_accounts.type', $account_id)
        ->where('chart_of_account_types.name', '!=', 'Assets')
        // ->where('chart_of_accounts.created_by', \Auth::user()->creatorId())
        ->where('journal_items.created_at', '>=', $start)
        ->where('journal_items.created_at', '<=', $end)
        ->groupBy('chart_of_accounts.id', 'chart_of_accounts.code', 'chart_of_accounts.name')
        ->get()
        ->toArray();
    

        $journalItemAssets = JournalItem::select('chart_of_accounts.id', 'chart_of_accounts.code', 'chart_of_accounts.name', \DB::raw('sum(debit + credit) as totalDebit'), \DB::raw('0.0 as totalCredit'))
        ->leftJoin('journal_entries', 'journal_entries.id', '=', 'journal_items.journal')
        ->leftJoin('chart_of_accounts', 'journal_items.account', '=', 'chart_of_accounts.id')
        ->leftJoin('chart_of_account_types', 'chart_of_accounts.type', '=', 'chart_of_account_types.id')
        ->where('chart_of_accounts.type', $account_id)
        ->where('chart_of_account_types.name', '=', 'Assets')
        // ->where('chart_of_accounts.created_by', \Auth::user()->creatorId())
        ->where('journal_items.created_at', '>=', $start)
        ->where('journal_items.created_at', '<=', $end)
        ->groupBy('chart_of_accounts.id', 'chart_of_accounts.code', 'chart_of_accounts.name') // Group by these columns
        ->get()
        ->toArray();
    

    
        $invoice = InvoiceProduct::select('chart_of_accounts.id', 'chart_of_accounts.code', 'chart_of_accounts.name', \DB::raw('0 as totalDebit'), \DB::raw('sum(price*invoice_products.quantity) as totalCredit'))
        ->leftJoin('product_services', 'product_services.id', '=', 'invoice_products.product_id')
        ->leftJoin('chart_of_accounts', 'product_services.sale_chartaccount_id', '=', 'chart_of_accounts.id')
        ->where('chart_of_accounts.type', $account_id)
        // ->where('chart_of_accounts.created_by', \Auth::user()->creatorId())
        ->where('invoice_products.created_at', '>=', $start)
        ->where('invoice_products.created_at', '<=', $end)
        ->groupBy('chart_of_accounts.id', 'chart_of_accounts.code', 'chart_of_accounts.name') // Group by these columns
        ->get()
        ->toArray();
    

        $invoicePayment = InvoicePayment::select('chart_of_accounts.id', 'chart_of_accounts.code', 'chart_of_accounts.name', \DB::raw('sum(amount) as totalDebit'), \DB::raw('0 as totalCredit'))
        ->leftJoin('bank_accounts', 'bank_accounts.id', '=', 'invoice_payments.account_id')
        ->leftJoin('chart_of_accounts', 'bank_accounts.chart_account_id', '=', 'chart_of_accounts.id')
        ->where('chart_of_accounts.type', $account_id)
        // ->where('chart_of_accounts.created_by', \Auth::user()->creatorId())
        ->where('invoice_payments.created_at', '>=', $start)
        ->where('invoice_payments.created_at', '<=', $end)
        ->groupBy('chart_of_accounts.id', 'chart_of_accounts.code', 'chart_of_accounts.name') // Group by these columns
        ->get()
        ->toArray();
    
    

        $revenue = Revenue::select('chart_of_accounts.id', 'chart_of_accounts.code', 'chart_of_accounts.name', \DB::raw('sum(amount) as totalDebit'), \DB::raw('0 as totalCredit'))
        ->leftJoin('bank_accounts', 'bank_accounts.id', '=', 'revenues.account_id')
        ->leftJoin('chart_of_accounts', 'bank_accounts.chart_account_id', '=', 'chart_of_accounts.id')
        ->where('chart_of_accounts.type', $account_id)
        // ->where('chart_of_accounts.created_by', \Auth::user()->creatorId())
        ->where('revenues.created_at', '>=', $start)
        ->where('revenues.created_at', '<=', $end)
        ->groupBy('chart_of_accounts.id', 'chart_of_accounts.code', 'chart_of_accounts.name') // Group by these columns
        ->get()
        ->toArray();
    

        $bill = BillProduct::select('chart_of_accounts.id', 'chart_of_accounts.code', 'chart_of_accounts.name', \DB::raw('sum(price*bill_products.quantity) as totalDebit'), \DB::raw('0 as totalCredit'))
        ->leftJoin('product_services', 'product_services.id', '=', 'bill_products.product_id')
        ->leftJoin('chart_of_accounts', 'product_services.expense_chartaccount_id', '=', 'chart_of_accounts.id')
        ->where('chart_of_accounts.type', $account_id)
        // ->where('chart_of_accounts.created_by', \Auth::user()->creatorId())
        ->where('bill_products.created_at', '>=', $start)
        ->where('bill_products.created_at', '<=', $end)
        ->groupBy('chart_of_accounts.id', 'chart_of_accounts.code', 'chart_of_accounts.name') // Group by these columns
        ->get()
        ->toArray();
    
        
        $billAccount = BillAccount::select('chart_of_accounts.id', 'chart_of_accounts.code', 'chart_of_accounts.name', \DB::raw('sum(price) as totalDebit'), \DB::raw('0 as totalCredit'))
        ->leftJoin('chart_of_accounts', 'bill_accounts.chart_account_id', '=', 'chart_of_accounts.id')
        ->where('chart_of_accounts.type', $account_id)
        // ->where('chart_of_accounts.created_by', \Auth::user()->creatorId())
        ->where('bill_accounts.created_at', '>=', $start)
        ->where('bill_accounts.created_at', '<=', $end)
        ->groupBy('chart_of_accounts.id', 'chart_of_accounts.code', 'chart_of_accounts.name') // Group by these columns
        ->get()
        ->toArray();
    

        $billPayment = BillPayment::select('chart_of_accounts.id', 'chart_of_accounts.code', 'chart_of_accounts.name', \DB::raw('sum(amount) as totalDebit'), \DB::raw('0 as totalCredit'))
        ->leftJoin('bank_accounts', 'bank_accounts.id', '=', 'bill_payments.account_id')
        ->leftJoin('chart_of_accounts', 'bank_accounts.chart_account_id', '=', 'chart_of_accounts.id')
        ->where('chart_of_accounts.type', $account_id)
        // ->where('chart_of_accounts.created_by', \Auth::user()->creatorId())
        ->where('bill_payments.created_at', '>=', $start)
        ->where('bill_payments.created_at', '<=', $end)
        ->groupBy('chart_of_accounts.id', 'chart_of_accounts.code', 'chart_of_accounts.name') // Group by these columns
        ->get()
        ->toArray();
    

        $payments = Payment::select('chart_of_accounts.id', 'chart_of_accounts.code', 'chart_of_accounts.name', \DB::raw('sum(amount) as totalDebit'), \DB::raw('0 as totalCredit'))
        ->leftJoin('bank_accounts', 'bank_accounts.id', '=', 'payments.account_id')
        ->leftJoin('chart_of_accounts', 'bank_accounts.chart_account_id', '=', 'chart_of_accounts.id')
        ->where('chart_of_accounts.type', $account_id)
        // ->where('chart_of_accounts.created_by', \Auth::user()->creatorId())
        ->where('payments.created_at', '>=', $start)
        ->where('payments.created_at', '<=', $end)
        ->groupBy('chart_of_accounts.id', 'chart_of_accounts.code', 'chart_of_accounts.name') // Group by these columns
        ->get()
        ->toArray();
    

        // if($billAccount != [])
        // {
        //     for ($i = 0; $i < count($invoicePayment); $i++) {
        //         $invoicePayment[$i]["totalDebit"] = (
        //             ($invoicePayment[$i]["totalDebit"]) - ($billAccount[$i]["totalDebit"])
        //         );
        //     }
        // }

        if($billPayment != [])
        {
            for ($i = 0; $i < count($invoicePayment); $i++) {
                $invoicePayment[$i]["totalDebit"] = (
                    ($invoicePayment[$i]["totalDebit"]) - ($billPayment[$i]["totalDebit"])
                );
            }
        }

        $total = array_merge($invoice , $journalItem ,$journalItemAssets,$revenue , $bill ,$billAccount , $payments, $invoicePayment);
        return $total;

    }


}
