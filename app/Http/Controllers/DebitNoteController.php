<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\DebitNote;
use App\Models\Utility;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;

class DebitNoteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $bills = Bill::with(['vender'])->get();

        return view('debitNote.index', compact('bills'));

    }

    public function create($bill_id)
    {
        $billDue = Bill::where('id', $bill_id)->first();

        return view('debitNote.create', compact('billDue', 'bill_id'));

    }

    public function store(Request $request, $bill_id)
    {
        $validator = \Validator::make(
            $request->all(), [
                'amount' => 'required|numeric',
                'date' => 'required',
            ]
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }
        $billDue = Bill::where('id', $bill_id)->first();

        if($request->amount > $billDue->getDue())
        {
            Toastr::error('Maximum ' . Utility::priceFormat($billDue->getDue()) . ' credit limit of this bill.','Error');
            return redirect()->back();
        }
        $bill               = Bill::where('id', $bill_id)->first();
        $debit              = new DebitNote();
        $debit->bill        = $bill_id;
        $debit->vendor      = $bill->vender_id;
        $debit->date        = $request->date;
        $debit->amount      = $request->amount;
        $debit->description = $request->description;
        $debit->save();

        Utility::updateUserBalance('vendor', $bill->vender_id, $request->amount, 'credit');

        Toastr::success('Debit Note successfully created.','Success');
        return redirect()->back();

    }


    public function edit($bill_id, $debitNote_id)
    {
        $debitNote = DebitNote::find($debitNote_id);
        return view('debitNote.edit', compact('debitNote'));

    }


    public function update(Request $request, $bill_id, $debitNote_id)
    {

            $validator = \Validator::make(
                $request->all(), [
                    'amount' => 'required|numeric',
                    'date' => 'required',
                ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $billDue = Bill::where('id', $bill_id)->first();
            if($request->amount > $billDue->getDue())
            {
                Toastr::error('Maximum ' . Utility::priceFormat($billDue->getDue()) . ' credit limit of this bill.','Error');
                return redirect()->back();
            }

            $debit = DebitNote::find($debitNote_id);

            Utility::updateUserBalance('vendor', $billDue->vender_id, $debit->amount, 'debit');

            $debit->date        = $request->date;
            $debit->amount      = $request->amount;
            $debit->description = $request->description;
            $debit->save();

            Utility::updateUserBalance('vendor', $billDue->vender_id, $request->amount, 'credit');

            Toastr::success('Debit Note successfully updated.','Success');
            return redirect()->back();

    }

    public function destroy($bill_id, $debitNote_id)
    {
        $debitNote = DebitNote::find($debitNote_id);
        $debitNote->delete();
        Utility::updateUserBalance('vendor', $debitNote->vendor, $debitNote->amount, 'debit');

        Toastr::success('Debit Note successfully deleted.','Success');
        return redirect()->back();

    }

    public function customCreate()
    {
            $bills = Bill::get()->pluck('bill_id', 'id');

            return view('debitNote.custom_create', compact('bills'));

    }

    public function customStore(Request $request)
    {
            $validator = \Validator::make(
                $request->all(), [
                    'bill' => 'required|numeric',
                    'amount' => 'required|numeric',
                    'date' => 'required',
                ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $bill_id = $request->bill;
            $billDue = Bill::where('id', $bill_id)->first();

            if($request->amount > $billDue->getDue())
            {
                Toastr::error('Maximum ' . Utility::priceFormat($billDue->getDue()) . ' credit limit of this bill.','Error');
                return redirect()->back();
            }
            $bill               = Bill::where('id', $bill_id)->first();
            $debit              = new DebitNote();
            $debit->bill        = $bill_id;
            $debit->vendor      = $bill->vender_id;
            $debit->date        = $request->date;
            $debit->amount      = $request->amount;
            $debit->description = $request->description;
            $debit->save();

            Utility::updateUserBalance('vendor', $bill->vender_id, $request->amount, 'credit');

            Toastr::success('Debit Note successfully created.','Success');
            return redirect()->back();

    }


    public function getbill(Request $request)
    {

        $bill = Bill::where('id', $request->bill_id)->first();
        echo json_encode($bill->getDue());
    }
}
