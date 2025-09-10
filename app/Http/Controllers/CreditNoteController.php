<?php

namespace App\Http\Controllers;

use App\Models\CreditNote;
use App\Models\Invoice;
use App\Models\Utility;
use App\Models\Customer;
use App\Models\Vender;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;

class CreditNoteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $invoices = Invoice::with(['customer'])->get();

        return view('creditNote.index', compact('invoices'));
    }

    public function create($invoice_id)
    {
        $invoiceDue = Invoice::where('id', $invoice_id)->first();

        return view('creditNote.create', compact('invoiceDue', 'invoice_id'));
    }

    public function store(Request $request, $invoice_id)
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
            $invoiceDue = Invoice::where('id', $invoice_id)->first();
            if($request->amount > $invoiceDue->getDue())
            {
                Toastr::error('Maximum ' . \App\Models\Utility::priceFormat($invoiceDue->getDue()) . ' credit limit of this invoice.','Error');
                return redirect()->back();
            }
            $invoice = Invoice::where('id', $invoice_id)->first();

            $credit              = new CreditNote();
            $credit->invoice     = $invoice_id;
            $credit->customer    = $invoice->customer_id;
            $credit->date        = $request->date;
            $credit->amount      = $request->amount;
            $credit->description = $request->description;
            $credit->save();

            Utility::updateUserBalance('customer', $invoice->customer_id, $request->amount, 'debit');

            Toastr::success('Credit Note successfully created.','Success');
            return redirect()->back();
    }


    public function edit($invoice_id, $creditNote_id)
    {
        $creditNote = CreditNote::find($creditNote_id);

        return view('creditNote.edit', compact('creditNote'));
    }


    public function update(Request $request, $invoice_id, $creditNote_id)
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

        $invoiceDue = Invoice::where('id', $invoice_id)->first();
        $credit = CreditNote::find($creditNote_id);
        if($request->amount > $invoiceDue->getDue()+$credit->amount)
        {
            Toastr::error('Maximum ' . \App\Models\Utility::priceFormat($invoiceDue->getDue()) . ' credit limit of this invoice.','Error');
            return redirect()->back();
        }

        Utility::updateUserBalance('customer', $invoiceDue->customer_id, $credit->amount, 'credit');

        $credit->date        = $request->date;
        $credit->amount      = $request->amount;
        $credit->description = $request->description;
        $credit->save();

        Utility::updateUserBalance('customer', $invoiceDue->customer_id, $request->amount, 'debit');

        Toastr::success('Credit Note successfully updated.','Success');
        return redirect()->back();
    }


    public function destroy($invoice_id, $creditNote_id)
    {
        $creditNote = CreditNote::find($creditNote_id);
        $creditNote->delete();

        Utility::updateUserBalance('customer', $creditNote->customer, $creditNote->amount, 'credit');

        Toastr::success('Credit Note successfully deleted.','Success');
        return redirect()->back();
    }

    public function customCreate()
    {
        $invoices = Invoice::get()->pluck('invoice_id', 'id');

        return view('creditNote.custom_create', compact('invoices'));
    }

    public function customStore(Request $request)
    {
        $validator = \Validator::make(
            $request->all(), [
                'invoice' => 'required|numeric',
                'amount' => 'required|numeric',
                'date' => 'required',
            ]
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }
        $invoice_id = $request->invoice;
        $invoiceDue = Invoice::where('id', $invoice_id)->first();

        if($request->amount > $invoiceDue->getDue())
        {
            Toastr::error('Maximum ' . \App\Models\Utility::priceFormat($invoiceDue->getDue()) . ' credit limit of this invoice.','Error');
            return redirect()->back();
        }
        $invoice             = Invoice::where('id', $invoice_id)->first();
        $credit              = new CreditNote();
        $credit->invoice     = $invoice_id;
        $credit->customer    = $invoice->customer_id;
        $credit->date        = $request->date;
        $credit->amount      = $request->amount;
        $credit->description = $request->description;
        $credit->save();

        Utility::updateUserBalance('customer', $invoice->customer_id, $request->amount, 'debit');

        Toastr::success('Credit Note successfully created.','Success');
        return redirect()->back();
    }

    public function getinvoice(Request $request)
    {
        $invoice = Invoice::where('id', $request->id)->first();

        echo json_encode($invoice->getDue());
    }

}
