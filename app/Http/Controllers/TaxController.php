<?php

namespace App\Http\Controllers;

use App\Models\BillProduct;
use App\Models\InvoiceProduct;
use App\Models\ProposalProduct;
use App\Models\Tax;
use Auth;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;

class TaxController extends Controller
{


    public function index()
    {
        $taxes = Tax::get();

        return view('taxes.index')->with('taxes', $taxes);
    }


    public function create()
    {
        return view('taxes.create');
    }

    public function store(Request $request)
    {
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required|max:20',
                                   'rate' => 'required|numeric',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $tax             = new Tax();
            $tax->name       = $request->name;
            $tax->rate       = $request->rate;
            $tax->created_by = \Auth::user()->id;
            $tax->save();

            Toastr::success('Tax rate successfully created.','Success');
            return redirect()->route('taxes.index');

    }

    public function show(Tax $tax)
    {
        return redirect()->route('taxes.index');
    }


    public function edit(Tax $tax)
    {
        return view('taxes.edit', compact('tax'));
    }


    public function update(Request $request, Tax $tax)
    {
        $validator = \Validator::make(
            $request->all(), [
                               'name' => 'required|max:20',
                               'rate' => 'required|numeric',
                           ]
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $tax->name = $request->name;
        $tax->rate = $request->rate;
        $tax->save();

        Toastr::success('Tax rate successfully updated.','Success');
        return redirect()->route('taxes.index');
    
    }

    public function destroy(Tax $tax)
    {
        $proposalData = ProposalProduct::whereRaw("find_in_set('$tax->id',tax)")->first();
        $billData     = BillProduct::whereRaw("find_in_set('$tax->id',tax)")->first();
        $invoiceData  = InvoiceProduct::whereRaw("find_in_set('$tax->id',tax)")->first();

        if(!empty($proposalData) || !empty($billData) || !empty($invoiceData))
        {
            Toastr::error('This tax is already assign to proposal or bill or invoice so please move or remove this tax related data.','Error');
            return redirect()->back();
        }

        $tax->delete();

        Toastr::success('Tax rate successfully deleted.','Success');
        return redirect()->route('taxes.index');

    }
}
