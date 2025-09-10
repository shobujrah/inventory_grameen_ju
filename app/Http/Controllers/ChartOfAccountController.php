<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use App\Models\ChartOfAccountSubType;
use App\Models\ChartOfAccountType;
use App\Models\User;
use App\Models\Utility;
use App\Models\JournalItem;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;

class ChartOfAccountController extends Controller
{

    public function index(Request $request)
    {
        if (!empty($request->start_date) && !empty($request->end_date)) {
            $start = $request->start_date;
            $end = $request->end_date;
        } else {
            $start = date('Y-01-01');
            $end = date('Y-m-d', strtotime('+1 day'));
        }
        $filter['startDateRange'] = $start;
        $filter['endDateRange'] = $end;

        $types = ChartOfAccountType::get();

        $chartAccounts = [];
        foreach($types as $type)
        {
            $accounts = ChartOfAccount::where('type', $type->id)->get();
            $chartAccounts[$type->name] = $accounts;

        }

        return view('chartOfAccount.index', compact('chartAccounts', 'types' , 'filter'));
    }

    public function create()
    {
        $types = ChartOfAccountType::get()->pluck('name', 'id');
        $types->prepend('Select Account Type', 0);

        return view('chartOfAccount.create', compact('types'));
    }


    public function store(Request $request)
    {
            // $validator = \Validator::make(
            //     $request->all(), [
            //                        'name' => 'required',
            //                        'type' => 'required',
            //                        'code' => 'required|integer|unique:chart_of_accounts,code',
            //                    ]
            // );
            // if($validator->fails())
            // {
            //     $messages = $validator->getMessageBag();

            //     return redirect()->back()->with('error', $messages->first());
            // } 

            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'type' => 'required',
                    'code' => 'required|integer|unique:chart_of_accounts,code',
                ],
                [
                    'code.unique' => 'Please enter unique code.',
                ]
            );
            
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                Toastr::error($messages->first(), 'Error');
                return redirect()->back()->withInput();
            }
            
            $account              = new ChartOfAccount();
            $account->name        = $request->name;
            $account->code        = $request->code;
            $account->type        = $request->type;
            $account->sub_type    = $request->sub_type;
            $account->description = $request->description;
            $account->is_enabled  = isset($request->is_enabled) ? 1 : 0;
            $account->created_by  = \Auth::user()->id;
            $account->save();

            Toastr::success('Account successfully created.','Success');
            return redirect()->route('chart-of-account.index');
        
    }


    
    public function show(ChartOfAccount $chartOfAccount,Request $request)
    {
            if(!empty($request->start_date) && !empty($request->end_date))
            {
                $start = $request->start_date;
                $end   = $request->end_date;
            }
            else
            {
                $start = date('Y-m-01');
                $end   = date('Y-m-t');
            }

            if(!empty($request->start_date) && !empty($request->end_date))
            {
                $accounts = ChartOfAccount::select(\DB::raw('CONCAT(code, " - ", name) AS code_name, id'))
                    ->where('created_at', '>=', $start)
                    ->where('created_at', '<=', $end)
                    ->get()->pluck('code_name', 'id');
                $accounts->prepend('Select Account', '');

            }
            else
            {

                $accounts = ChartOfAccount::select(\DB::raw('CONCAT(code, " - ", name) AS code_name, id'))
                    ->get()->pluck('code_name', 'id');
                $accounts->prepend('Select Account', '');
            }


            if(!empty($request->account))
            {
                $account = ChartOfAccount::find($request->account);
            }
            else
            {
                $account = ChartOfAccount::find($chartOfAccount->id);
            }



            $balance = 0;
            $debit   = 0;
            $credit  = 0;


            $filter['startDateRange'] = $start;
            $filter['endDateRange']   = $end;

            return view('chartOfAccount.show', compact('filter', 'account', 'accounts' , 'journalItems'));

    }


    public function edit(ChartOfAccount $chartOfAccount)
    {
        $types = ChartOfAccountType::get()->pluck('name', 'id');
        $types->prepend('Select Account Type', 0);

        return view('chartOfAccount.edit', compact('chartOfAccount', 'types'));
    }


    public function update(Request $request, ChartOfAccount $chartOfAccount)
    {
        $validator = \Validator::make(
            $request->all(), [
                                'name' => 'required',
                            ]
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }


        $chartOfAccount->name        = $request->name;
        $chartOfAccount->code        = $request->code;
        $chartOfAccount->description = $request->description;
        $chartOfAccount->is_enabled  = isset($request->is_enabled) ? 1 : 0;
        $chartOfAccount->save();

        Toastr::success('Account successfully updated.','Success');
        return redirect()->route('chart-of-account.index');

    }


    public function destroy(ChartOfAccount $chartOfAccount)
    {
        $chartOfAccount->delete();

        Toastr::success('Account successfully deleted.','Success');
        return redirect()->route('chart-of-account.index');

    }

    public function getSubType(Request $request)
    {
        $types = ChartOfAccountSubType::where('type', $request->type)->get()->pluck('name', 'id')->toArray();

        return response()->json($types);
    }
}
