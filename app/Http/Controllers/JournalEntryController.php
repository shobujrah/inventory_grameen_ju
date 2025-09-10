<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\ChartOfAccount;
use App\Models\JournalEntry;
use App\Models\JournalItem;
use App\Models\Utility;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;

class JournalEntryController extends Controller
{

    public function index()
    {
        $journalEntries = JournalEntry::get();

        return view('journalEntry.index', compact('journalEntries'));
    }


    public function create()
    {
        $accounts = ChartOfAccount::select(\DB::raw('CONCAT(code, " - ", name) AS code_name, id'))
                ->get()->pluck('code_name', 'id');

        $journalId = $this->journalNumber();

        return view('journalEntry.create', compact('accounts', 'journalId'));
    }


    public function store(Request $request)
    {
            $validator = \Validator::make(
                $request->all(), [
                    'date' => 'required',
                    'accounts' => 'required',
                ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $accounts = $request->accounts;

            $totalDebit  = 0;
            $totalCredit = 0;
            for($i = 0; $i < count($accounts); $i++)
            {
                $debit       = isset($accounts[$i]['debit']) ? $accounts[$i]['debit'] : 0;
                $credit      = isset($accounts[$i]['credit']) ? $accounts[$i]['credit'] : 0;
                $totalDebit  += $debit;
                $totalCredit += $credit;
            }

            if($totalCredit != $totalDebit)
            {
                Toastr::error('Debit and Credit must be Equal.','Error');
                return redirect()->back();
            }

            $journal              = new JournalEntry();
            $journal->journal_id  = $this->journalNumber();
            $journal->date        = $request->date;
            $journal->reference   = $request->reference;
            $journal->description = $request->description;
            $journal->created_by  = \Auth::user()->id;
            $journal->save();



            for($i = 0; $i < count($accounts); $i++)

            {
                $journalItem              = new JournalItem();
                $journalItem->journal     = $journal->id;
                $journalItem->date        = $request->date; // ← Add this line
                $journalItem->account     = $accounts[$i]['account'];
                $journalItem->description = $accounts[$i]['description'];
                $journalItem->debit       = isset($accounts[$i]['debit']) ? $accounts[$i]['debit'] : 0;
                $journalItem->credit      = isset($accounts[$i]['credit']) ? $accounts[$i]['credit'] : 0;
                $journalItem->save();

                $bankAccounts = BankAccount::where('chart_account_id','=',$accounts[$i]['account'])->get();
                if(!empty($bankAccounts))
                {
                    foreach ($bankAccounts as $bankAccount)
                    {
                        $old_balance = $bankAccount->opening_balance;
                        if ($journalItem->debit > 0) {
                            $new_balance = $old_balance - $journalItem->debit;
                        }
                        if ($journalItem->credit > 0) {
                            $new_balance = $old_balance + $journalItem->credit;
                        }
                        if (isset($new_balance)) {
                            $bankAccount->opening_balance = $new_balance;
                            $bankAccount->save();
                        }
                    }
                }

            }


            Toastr::success('Journal entry successfully created.','Success');
            return redirect()->route('journal-entry.index');

    }


    public function show(JournalEntry $journalEntry)
    {
        $accounts = $journalEntry->accounts;
        
        return view('journalEntry.view', compact('journalEntry', 'accounts'));

    }


    public function edit(JournalEntry $journalEntry)
    {
        $accounts = ChartOfAccount::select(\DB::raw('CONCAT(code, " - ", name) AS code_name, id'))->get()->pluck('code_name', 'id');
//            $accounts->prepend('--', '');

        return view('journalEntry.edit', compact('accounts', 'journalEntry'));

    }

    public function update(Request $request, JournalEntry $journalEntry)
    {
                $validator = \Validator::make(
                    $request->all(), [
                        'date' => 'required',
                        'accounts' => 'required',
                    ]
                );
                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $accounts = $request->accounts;

                $totalDebit  = 0;
                $totalCredit = 0;
                for($i = 0; $i < count($accounts); $i++)
                {
                    $debit       = isset($accounts[$i]['debit']) ? $accounts[$i]['debit'] : 0;
                    $credit      = isset($accounts[$i]['credit']) ? $accounts[$i]['credit'] : 0;
                    $totalDebit  += $debit;
                    $totalCredit += $credit;
                }

                if($totalCredit != $totalDebit)
                {
                    Toastr::error('Debit and Credit must be Equal.','Error');
                    return redirect()->back();
                }

                $journalEntry->date        = $request->date;
                $journalEntry->reference   = $request->reference;
                $journalEntry->description = $request->description;
                $journalEntry->created_by  = \Auth::user()->id;
                $journalEntry->save();

                for($i = 0; $i < count($accounts); $i++)
                {
                    $journalItem = JournalItem::find($accounts[$i]['id']);

                    if($journalItem == null)
                    {
                        $journalItem          = new JournalItem();
                        $journalItem->journal = $journalEntry->id;
                    }

                    if(isset($accounts[$i]['account']))
                    {
                        $journalItem->account = $accounts[$i]['account'];
                    }

                    $journalItem->description = $accounts[$i]['description'];
                    $journalItem->debit  = isset($accounts[$i]['debit']) ? $accounts[$i]['debit'] : 0;
                    $journalItem->credit = isset($accounts[$i]['credit']) ? $accounts[$i]['credit'] : 0;
                    $journalItem->date = $request->date; // ← Add this line
                    $journalItem->save();


                    $bankAccounts = BankAccount::where('chart_account_id','=',$accounts[$i]['account'])->get();
                    if(!empty($bankAccounts))
                    {
                        foreach ($bankAccounts as $bankAccount)
                        {
                            $old_balance = $bankAccount->opening_balance;
                            if ($journalItem->debit > 0) {
                                $new_balance = $old_balance - $journalItem->debit;
                            }
                            if ($journalItem->credit > 0) {
                                $new_balance = $old_balance + $journalItem->credit;
                            }
                            if (isset($new_balance)) {
                                $bankAccount->opening_balance = $new_balance;
                                $bankAccount->save();
                            }
                        }
                    }
                }

                Toastr::success('Journal entry successfully updated.','Success');
                return redirect()->route('journal-entry.index');


    }



    public function destroy(JournalEntry $journalEntry)
    {
        $journalEntry->delete();

        JournalItem::where('journal', '=', $journalEntry->id)->delete();

        Toastr::success('Journal entry successfully deleted.','Success');
        return redirect()->route('journal-entry.index');

    }

    function journalNumber()
    {
        $latest = JournalEntry::latest()->first();

        if(!$latest)
        {
            return 1;
        }

        return $latest->journal_id + 1;
    }

    public function accountDestroy(Request $request)
    {
        JournalItem::where('id', '=', $request->id)->delete();

        Toastr::success('Journal entry account successfully deleted.','Success');
        return redirect()->back();

    }
    public function journalDestroy($item_id)
    {
        $journal = JournalItem::find($item_id);
        $journal->delete();

        Toastr::success('Journal account successfully deleted.','Success');
        return redirect()->back();

    }
}
