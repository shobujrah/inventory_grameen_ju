<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\BankTransfer;
use App\Models\Utility;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;

class BankTransferController extends Controller
{

    public function index(Request $request)
    {
        $account = BankAccount::pluck('holder_name', 'id');
        $account->prepend('Select Account', '');

        $query = BankTransfer::query();

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

        if(!empty($request->f_account))
        {
            $query->where('from_account', '=', $request->f_account);
        }
        if(!empty($request->t_account))
        {
            $query->where('to_account', '=', $request->t_account);
        }
        $transfers = $query->with(['fromBankAccount','toBankAccount'])->get();

        return view('bank-transfer.index', compact('transfers', 'account'));

    }

    public function create()
    {
        $bankAccount = BankAccount::select('*', \DB::raw("CONCAT(bank_name,' ',holder_name) AS name"))->pluck('name', 'id');

        return view('bank-transfer.create', compact('bankAccount'));

    }

    public function store(Request $request)
    {
        $validator = \Validator::make(
            $request->all(), [
                                'from_account' => 'required|numeric',
                                'to_account' => 'required|numeric',
                                'amount' => 'required|numeric',
                                'date' => 'required',
                            ]
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $transfer                 = new BankTransfer();
        $transfer->from_account   = $request->from_account;
        $transfer->to_account     = $request->to_account;
        $transfer->amount         = $request->amount;
        $transfer->date           = $request->date;
        $transfer->payment_method = 0;
        $transfer->reference      = $request->reference;
        $transfer->description    = $request->description;
        $transfer->created_by     = \Auth::user()->id;
        $transfer->save();

        Utility::bankAccountBalance($request->from_account, $request->amount, 'debit');

        Utility::bankAccountBalance($request->to_account, $request->amount, 'credit');

        Toastr::success('Amount successfully transfered.','Success');
        return redirect()->route('bank-transfer.index');

    }
    public function show()
    {
        return redirect()->route('bank-transfer.index');
    }

    public function edit(BankTransfer $transfer,$id)
    {
        $transfer = BankTransfer::where('id',$id)->first();
        $bankAccount = BankAccount::select('*', \DB::raw("CONCAT(bank_name,' ',holder_name) AS name"))->pluck('name', 'id');

        return view('bank-transfer.edit', compact('bankAccount', 'transfer'));

    }

    public function update(Request $request, BankTransfer $transfer,$id)
    {
        $transfer = BankTransfer::find($id);
        $validator = \Validator::make(
        $request->all(), [
                            'from_account' => 'required|numeric',
                            'to_account' => 'required|numeric',
                            'amount' => 'required|numeric',
                            'date' => 'required',
                        ]
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        Utility::bankAccountBalance($transfer->from_account, $transfer->amount, 'credit');
        Utility::bankAccountBalance($transfer->to_account, $transfer->amount, 'debit');

        $transfer->from_account   = $request->from_account;
        $transfer->to_account     = $request->to_account;
        $transfer->amount         = $request->amount;
        $transfer->date           = $request->date;
        $transfer->payment_method = 0;
        $transfer->reference      = $request->reference;
        $transfer->description    = $request->description;
        $transfer->save();

        Utility::bankAccountBalance($request->from_account, $request->amount, 'debit');
        Utility::bankAccountBalance($request->to_account, $request->amount, 'credit');

        Toastr::success('Amount transfer successfully updated.','Success');
        return redirect()->route('bank-transfer.index');

    }


    public function destroy($id)
    {
        $bankTransfer = BankTransfer::find($id);

        $bankTransfer->delete();

        Utility::bankAccountBalance($bankTransfer->from_account, $bankTransfer->amount, 'credit');
        Utility::bankAccountBalance($bankTransfer->to_account, $bankTransfer->amount, 'debit');

        Toastr::success('Amount transfer successfully deleted.','Success');
        return redirect()->route('bank-transfer.index');

    }

}

