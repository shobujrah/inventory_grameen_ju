<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\BillPayment;
use App\Models\ChartOfAccount;
use App\Models\CustomField;
use App\Models\InvoicePayment;
use App\Models\Payment;
use App\Models\Revenue;
use App\Models\Utility;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;

class BankAccountController extends Controller
{

    public function index()
    {
        $accounts = BankAccount::with(['chartAccount'])->get();

        return view('bankAccount.index', compact('accounts'));
    }

    public function create()
    {
        $chart_accounts = ChartOfAccount::select(\DB::raw('CONCAT(code, " - ", name) AS code_name, id'))
            ->get()->pluck('code_name', 'id');
        $chart_accounts->prepend('Select Account', '');
        $customFields = CustomField::where('module', '=', 'account')->get();

        return view('bankAccount.create', compact('customFields','chart_accounts'));
    }

    public function store(Request $request)
    {
        $validator = \Validator::make(
            $request->all(), [
                'holder_name' => 'required',
                'bank_name' => 'required',
                'account_number' => 'required',
                'opening_balance' => 'required',
                'contact_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/',
            ]
        );

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->route('bank-account.index')->with('error', $messages->first());
        }

        $account                  = new BankAccount();
        $account->chart_account_id = $request->chart_account_id;
        $account->holder_name     = $request->holder_name;
        $account->bank_name       = $request->bank_name;
        $account->account_number  = $request->account_number;
        $account->opening_balance = $request->opening_balance;
        $account->contact_number  = $request->contact_number;
        $account->bank_address    = $request->bank_address;
        $account->created_by      = \Auth::user()->id;
        $account->save();
        CustomField::saveData($account, $request->customField);

        Toastr::success('Bank Account Created!','Success');

        return redirect()->route('bank-account.index');

    }

    public function show()
    {
        return redirect()->route('bank-account.index');
    }


    public function edit(BankAccount $bankAccount)
    {

        $chart_accounts = ChartOfAccount::select(\DB::raw('CONCAT(code, " - ", name) AS code_name, id'))
            ->get()
            ->pluck('code_name', 'id');
        $chart_accounts->prepend('Select Account', '');

        $bankAccount->customField = CustomField::getData($bankAccount, 'account');
        $customFields             = CustomField::where('module', '=', 'account')->get();

        return view('bankAccount.edit', compact('bankAccount', 'customFields','chart_accounts'));

    }

    public function update(Request $request, BankAccount $bankAccount)
    {
        $validator = \Validator::make(
            $request->all(), [
                'holder_name' => 'required',
                'bank_name' => 'required',
                'account_number' => 'required',
                'opening_balance' => 'required',
                'contact_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/',
            ]
        );

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->route('bank-account.index')->with('error', $messages->first());
        }
        $bankAccount->chart_account_id = $request->chart_account_id;
        $bankAccount->holder_name     = $request->holder_name;
        $bankAccount->bank_name       = $request->bank_name;
        $bankAccount->account_number  = $request->account_number;
        $bankAccount->opening_balance = $request->opening_balance;
        $bankAccount->contact_number  = $request->contact_number;
        $bankAccount->bank_address    = $request->bank_address;
        $bankAccount->created_by      = \Auth::user()->id;
        $bankAccount->save();
        CustomField::saveData($bankAccount, $request->customField);

        Toastr::success('Bank Account Updated!','Success');
        return redirect()->route('bank-account.index');

    }


    public function destroy(BankAccount $bankAccount)
    {
        $revenue        = Revenue::where('account_id', $bankAccount->id)->first();
        $invoicePayment = InvoicePayment::where('account_id', $bankAccount->id)->first();
        $transaction    = Transaction::where('account', $bankAccount->id)->first();
        $payment        = Payment::where('account_id', $bankAccount->id)->first();
        $billPayment    = BillPayment::first();

        if(!empty($revenue) && !empty($invoicePayment) && !empty($transaction) && !empty($payment) && !empty($billPayment))
        {   
            Toastr::error('Please delete related record of this account.','Error');
            return redirect()->route('bank-account.index');
        }
        else
        {
            $bankAccount->delete();
            Toastr::success('Account successfully deleted.','Success');

            return redirect()->route('bank-account.index');
        }
    }
}
