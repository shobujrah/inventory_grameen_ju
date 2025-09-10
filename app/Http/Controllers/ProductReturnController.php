<?php

namespace App\Http\Controllers;

use App\Models\Branch;

use App\Models\Product;
use App\Models\JournalItem;
use App\Models\JournalEntry;
use Illuminate\Http\Request;
use App\Models\ProductLedger;
use App\Models\ProductReturn;
use App\Models\Branch_Product;
use App\Models\ChartOfAccount;
use App\Models\ProductLedgerBH;
use App\Models\ProductAccountMap;
use App\Models\ProductReturnWarehouse;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;

class ProductReturnController extends Controller
{
    

    //  public function damageReturnList()
    // {
    //     $user = auth()->user();
    //     $userBranch = $user->branch->type;

    //     $showButton = in_array($userBranch, ['Branch', 'Headoffice']);
    
    //     if ($userBranch === 'Branch' || $userBranch === 'Headoffice') {
    //         $returnproducts = ProductReturn::where('branch_id', $user->branch_id)
    //             ->where(function ($query) {
    //                 $query->whereIn('status', [0, 1])
    //                       ->orWhere('deny_status', 1);
    //             })
    //             ->get();
        
    //     } elseif ($userBranch === 'Warehouse') {
    //         $returnproducts = ProductReturn::where(function ($query) {
    //             $query->whereIn('status', [0, 1])
    //                   ->orWhere('deny_status', 1);
    //         })->get();
        
    //     } else {
    //         $returnproducts = ProductReturn::where('branch_id', $user->branch_id)
    //             ->where(function ($query) {
    //                 $query->whereIn('status', [0, 1])
    //                       ->orWhere('deny_status', 1);
    //             })
    //             ->get();
    //     }
    //     return view('productReturn.list', compact('returnproducts', 'showButton'));
    // }




       //this is right just role_name = admin wise get all, that not do, before all okay
    // public function damageReturnList()
    // {
    //     $user = auth()->user();
    //     $userBranch = $user->branch->type;
    
    //     $showButton = in_array($userBranch, ['Branch', 'Headoffice']);
    
    //     if ($userBranch === 'Branch') {
    //         $returnproducts = ProductReturn::where('branch_id', $user->branch_id)
    //             ->where('user_id', $user->id)
    //             ->where(function ($query) {
    //                 $query->whereIn('status', [0, 1])
    //                       ->orWhere('deny_status', 1);
    //             })
    //             ->get();
    //     } elseif ($userBranch === 'Headoffice') {
    //         $returnproducts = ProductReturn::where('branch_id', $user->branch_id)
    //             ->where(function ($query) {
    //                 $query->whereIn('status', [0, 1])
    //                       ->orWhere('deny_status', 1);
    //             })
    //             ->get();
    //     } elseif ($userBranch === 'Warehouse') {
    //         $returnproducts = ProductReturn::where(function ($query) {
    //             $query->whereIn('status', [0, 1])
    //                   ->orWhere('deny_status', 1);
    //         })->get();
    //     } else {
    //         $returnproducts = ProductReturn::where('branch_id', $user->branch_id)
    //             ->where(function ($query) {
    //                 $query->whereIn('status', [0, 1])
    //                       ->orWhere('deny_status', 1);
    //             })
    //             ->get();
    //     }
    //     return view('productReturn.list', compact('returnproducts', 'showButton'));
    // }
    




    public function damageReturnList()
    {
        $user = auth()->user();
        $userBranch = $user->branch->type;
        $showButton = in_array($userBranch, ['Branch', 'Headoffice']);
        $paymentMethods = ChartOfAccount::whereIn('id', [54, 55, 58])->get();
        
        if ($user->role_name === 'Admin') {
            $returnproducts = ProductReturn::whereIn('status', [0, 1])->orWhere('deny_status', 1)->get();
        } else {
            if ($userBranch === 'Branch') {
                $returnproducts = ProductReturn::where('branch_id', $user->branch_id)
                    ->where('user_id', $user->id)
                    ->where(function ($query) {
                        $query->whereIn('status', [0, 1])
                              ->orWhere('deny_status', 1);
                    })
                    ->get();
            } elseif ($userBranch === 'Headoffice') {
                $returnproducts = ProductReturn::where('branch_id', $user->branch_id)
                    ->where(function ($query) {
                        $query->whereIn('status', [0, 1])
                              ->orWhere('deny_status', 1);
                    })
                    ->get();
            } elseif ($userBranch === 'Warehouse') {
                $returnproducts = ProductReturn::where(function ($query) {
                    $query->whereIn('status', [0, 1])
                          ->orWhere('deny_status', 1);
                })->get();
            } else {
                $returnproducts = ProductReturn::where('branch_id', $user->branch_id)
                    ->where(function ($query) {
                        $query->whereIn('status', [0, 1])
                              ->orWhere('deny_status', 1);
                    })
                    ->get();
            }
        }
        
        return view('productReturn.list', compact('returnproducts', 'showButton', 'paymentMethods'));
    }








   public function damageReturnListWarehouse()
    {
        $user = auth()->user();
        $userBranch = $user->branch->type;
        $showButton = in_array($userBranch, ['Branch', 'Headoffice']);
        $paymentMethods = ChartOfAccount::whereIn('id', [54, 55, 58])->get();

        if ($user->role_name === 'Admin' || $user->role_name === 'PurchaseTeam') {
            $returnproducts = ProductReturnWarehouse::whereIn('status', [0, 1])
                ->orWhere('deny_status', 1)
                ->get();
        } elseif ($userBranch === 'Warehouse') {
            $returnproducts = ProductReturnWarehouse::where(function ($query) {
                $query->whereIn('status', [0, 1])
                    ->orWhere('deny_status', 1);
            })->get();
        } else {
            $returnproducts = collect(); 
        }

        return view('productReturn.whlist', compact('returnproducts', 'showButton', 'paymentMethods'));
    }

    










//old
    // public function damageReturnStore(Request $request)
    // {
    //     $request->validate([
    //         'branch_id' => 'required',
    //         'product_id' => 'required',
    //         'quantity' => 'required',
    //         'reason' => 'required',
    //         'date' => 'required',
    //     ]);

    //     ProductReturn::create([
    //         'branch_id' => $request->branch_id,
    //         'product_id' => $request->product_id,
    //         'return_quantity' => $request->quantity,
    //         'reason' => $request->reason,
    //         'date' => $request->date,
    //         'user_id' => auth()->id(),
    //         'status' => 0,
    //         'notification_status' => 0,
    //     ]);

    //     $branch = DB::table('branch')->where('id', $request->branch_id)->first();

    //     Toastr::success('Damage/Return entry saved successfully.', 'Success');
    //     return redirect()->route('stock.view', ['branch_id' => $branch->id]);
    // }



    //work but warehouse return not happen
    // public function damageReturnStore(Request $request)
    // {
    //     $request->validate([
    //         'branch_id' => 'required',
    //         'product_id' => 'required',
    //         'quantity' => 'required',
    //         'reason' => 'required',
    //         'date' => 'required',
    //         'price' => 'required', // Ensure price is included in request
    //     ]);

    //     $userId = auth()->id();
    //     $branchId = $request->branch_id;
    //     $productId = $request->product_id;
    //     $price = $request->price;

    //     // Fetch price details from remain_details
    //     $product = Branch_Product::where('branch_id', $branchId)
    //         ->where('product_id', $productId)
    //         ->first();

    //     if (!$product) {
    //         Toastr::error('Product not found.', 'Error');
    //         return redirect()->back();
    //     }

    //     $details = json_decode($product->remain_details, true);
    //     $matched = collect($details)->firstWhere('prc', $price);

    //     if (!$matched) {
    //         Toastr::error('Price does not match, so not process for return.', 'Error');
    //         return redirect()->back();
    //     }

    //     // If price matches, continue storing the data
    //     ProductReturn::create([
    //         'branch_id' => $request->branch_id,
    //         'product_id' => $request->product_id,
    //         'return_quantity' => $request->quantity,
    //         'reason' => $request->reason,
    //         'date' => $request->date,
    //         'price' => $request->price,
    //         'user_id' => auth()->id(),
    //         'status' => 0,
    //         'notification_status' => 0,
    //     ]);

    //     $branch = DB::table('branch')->where('id', $branchId)->first();

    //     Toastr::success('Damage/Return entry saved successfully.', 'Success');
    //     return redirect()->route('stock.view', ['branch_id' => $branch->id]);
    // }






    public function damageReturnStore(Request $request)
    {
        $request->validate([
            'branch_id' => 'required',
            'product_id' => 'required',
            'quantity' => 'required',
            'reason' => 'required',
            'date' => 'required',
            'price' => 'required', 
        ]);

        $userId = auth()->id();
        $branchId = $request->branch_id;
        $productId = $request->product_id;
        $price = $request->price;

        $product = Branch_Product::where('branch_id', $branchId)
            ->where('product_id', $productId)
            ->first();

        if (!$product) {
            Toastr::error('Product not found.', 'Error');
            return redirect()->back();
        }

        $user = auth()->user();
        $branchType = $user->branch_type;

        if ($branchType === 'Warehouse') {
            $details = $request->price;
            $matched = $product->firstWhere('price', $details);

            if (!$matched) {
                Toastr::error('Price does not match, so not process for return.', 'Error');
                return redirect()->back();
            }
            ProductReturnWarehouse::create([
                'branch_id' => $request->branch_id,
                'product_id' => $request->product_id,
                'return_quantity' => $request->quantity,
                'reason' => $request->reason,
                'date' => $request->date,
                'price' => $request->price,
                'user_id' => auth()->id(),
                'status' => 0,
                'notification_status' => 0,
            ]);
        } 
        
        
        // else {
        //     $details = json_decode($product->remain_details, true);
        //     $matched = collect($details)->firstWhere('prc', $price);

        //     if (!$matched) {
        //         Toastr::error('Price does not match, so not process for return.', 'Error');
        //         return redirect()->back();
        //     }
        //     ProductReturn::create([
        //         'branch_id' => $request->branch_id,
        //         'product_id' => $request->product_id,
        //         'return_quantity' => $request->quantity,
        //         'reason' => $request->reason,
        //         'date' => $request->date,
        //         'price' => $request->price,
        //         'user_id' => auth()->id(),
        //         'status' => 0,
        //         'notification_status' => 0,
        //     ]);
        // }



        else {
            $details = json_decode($product->remain_details, true);
            $matched = collect($details)->firstWhere('prc', $price);

            if (!$matched) {
                Toastr::error('Price does not match, so not process for return.', 'Error');
                return redirect()->back();
            }

            // Get total quantity available for the given price
            $totalQty = collect($details)
                ->where('prc', $price)
                ->sum(function ($item) {
                    return (int) $item['qty'];
                });

            if ($request->quantity > $totalQty) {
                Toastr::error('This price has not enough quantity as you request.', 'Error');
                return redirect()->back();
            }

            ProductReturn::create([
                'branch_id' => $request->branch_id,
                'product_id' => $request->product_id,
                'return_quantity' => $request->quantity,
                'reason' => $request->reason,
                'date' => $request->date,
                'price' => $request->price,
                'user_id' => auth()->id(),
                'status' => 0,
                'notification_status' => 0,
            ]);
        }


        
        $branch = DB::table('branch')->where('id', $branchId)->first();

        Toastr::success('Damage/Return entry saved successfully.', 'Success');
        return redirect()->route('stock.view', ['branch_id' => $branch->id]);
        
    }












   //branch & headoffice
    public function cancel($id)
    {
        $returnproduct = ProductReturn::findOrFail($id);
        $returnproduct->status = NULL;
        $returnproduct->notification_status = NULL;
        $returnproduct->save();
    
        Toastr::success('Damage/Return product has been cancelled.', 'Success');
        return redirect()->back();
    }

    

    //warehouse
    public function cancelWarehouse($id)
    {
        $returnproduct = ProductReturnWarehouse::findOrFail($id);
        $returnproduct->status = NULL;
        $returnproduct->notification_status = NULL;
        $returnproduct->save();
    
        Toastr::success('Damage/Return product has been cancelled.', 'Success');
        return redirect()->back();
    }
    




     //old code 

    // public function accept($id)
    // {
    //     $acceptreturnproduct = ProductReturn::findOrFail($id);
    //     $acceptreturnproduct->update(['status' => 1]);
    //     $acceptreturnproduct->update(['notification_status' => NULL]);
    
    //     $productId = $acceptreturnproduct->product_id;
    //     $stockLevel = $acceptreturnproduct->return_quantity;
    //     $branchId = $acceptreturnproduct->branch_id;
    
    //     $branchProduct = Branch_Product::where('branch_id', $branchId)
    //                                    ->where('product_id', $productId)
    //                                    ->first();
    
    //     if ($branchProduct) {
    //         $branchProduct->stock -= $stockLevel;
    //         $branchProduct->save();
    //     }
    
    //     $warehouseBranchProduct = Branch_Product::whereHas('branch', function ($query) {
    //             $query->where('type', 'Warehouse');
    //         })
    //         ->where('product_id', $productId)
    //         ->first();
    
    //     if ($warehouseBranchProduct) {
    //         $warehouseBranchProduct->stock += $stockLevel;
    //         $warehouseBranchProduct->save();
    //     }
    
    //     Toastr::success('Damage/Return entry accepted and stock updated.', 'Success');
    //     return redirect()->route('damage.return.list');
    // }
    




     //done brfore return
    // public function accept($id)
    // {
    //     $acceptreturnproduct = ProductReturn::findOrFail($id);
    //     $acceptreturnproduct->update([
    //         'status' => 1,
    //         'notification_status' => NULL,
    //     ]);

    //     $productId = $acceptreturnproduct->product_id;
    //     $stockLevel = $acceptreturnproduct->return_quantity;
    //     $branchId = $acceptreturnproduct->branch_id;
    //     $acppriced = $acceptreturnproduct->price;

    //     $branchProduct = Branch_Product::where('branch_id', $branchId)
    //                                 ->where('product_id', $productId)
    //                                 ->first();

    //     if ($branchProduct) {
    //         $branchProduct->stock -= $stockLevel;

    //         $branchProduct->details_stockout = json_encode([
    //             ['qty' => (string) $stockLevel, 'prc' => number_format($acppriced, 2, '.', '')]
    //         ]);

    //         $remainDetails = json_decode($branchProduct->remain_details, true);
    //         foreach ($remainDetails as &$detail) {
    //             if ($detail['prc'] == number_format($acppriced, 2, '.', '')) {
    //                 $detail['qty'] = (string)((int)$detail['qty'] - (int)$stockLevel);
    //                 break;
    //             }
    //         }
    //         $branchProduct->remain_details = json_encode($remainDetails);

    //         $branchProduct->save();
    //     } 

    //     $branchType = Branch::where('id', $acceptreturnproduct->branch_id)->value('type');

    //     $narrationValue = $branchType === 'Headoffice' ? 'Headoffice' : 'Branch';
    //     $requisitionValue = 'return by ' . $narrationValue;

    //     ProductLedgerBH::create([
    //         'entry_date'      => now()->format('Y-m-d'),
    //         'narration'       => $narrationValue,
    //         'type'            => 'StockOut',
    //         'user_id'         => $acceptreturnproduct->user_id,
    //         'branch_id'       => $acceptreturnproduct->branch_id,
    //         'product_id'      => $acceptreturnproduct->product_id,
    //         'quantity'        => $acceptreturnproduct->return_quantity,
    //         'price'           => $acceptreturnproduct->price,
    //         'requisition_id'  => $requisitionValue,
    //     ]); 
        
    //     $warehouseBranchProduct = Branch_Product::whereHas('branch', function ($query) {
    //             $query->where('type', 'Warehouse');
    //         })
    //         ->where('product_id', $productId)
    //         ->where('price', $acppriced)
    //         ->first();

    //     if ($warehouseBranchProduct) {
    //         $warehouseBranchProduct->stock += $stockLevel;

    //         $detailsStockin = json_decode($warehouseBranchProduct->details_stockin, true) ?? [];

    //         $detailsStockin[] = [
    //             'requisition' => 'return',
    //             'quantity'    => $stockLevel,
    //             'date'        => now()->format('d-m-Y'),
    //             'store_by'    => auth()->id()
    //         ];

    //         $warehouseBranchProduct->details_stockin = json_encode($detailsStockin);
    //         $warehouseBranchProduct->save();
    //     }

    //     Toastr::success('Damage/Return entry accepted and stock updated.', 'Success');
    //     return redirect()->route('damage.return.list');
        
    // }




    public function accept($id)
    {
        $acceptreturnproduct = ProductReturn::findOrFail($id);
        $acceptreturnproduct->update([
            'status' => 1,
            'notification_status' => NULL,
        ]);

        $productId = $acceptreturnproduct->product_id;
        $stockLevel = $acceptreturnproduct->return_quantity;
        $branchId = $acceptreturnproduct->branch_id;
        $acppriced = $acceptreturnproduct->price;

        $branchProduct = Branch_Product::where('branch_id', $branchId)
                                    ->where('product_id', $productId)
                                    ->first();

        if ($branchProduct) {
            $branchProduct->stock -= $stockLevel;

            $branchProduct->details_stockout = json_encode([
                ['qty' => (string) $stockLevel, 'prc' => number_format($acppriced, 2, '.', '')]
            ]);

            $remainDetails = json_decode($branchProduct->remain_details, true);
            foreach ($remainDetails as &$detail) {
                if ($detail['prc'] == number_format($acppriced, 2, '.', '')) {
                    $detail['qty'] = (string)((int)$detail['qty'] - (int)$stockLevel);
                    break;
                }
            }
            $branchProduct->remain_details = json_encode($remainDetails);

            $branchProduct->save();
        } 

        $branchType = Branch::where('id', $acceptreturnproduct->branch_id)->value('type');

        $narrationValue = $branchType === 'Headoffice' ? 'Headoffice' : 'Branch';
        $requisitionValue = 'return by ' . $narrationValue;

        ProductLedgerBH::create([
            'entry_date'      => now()->format('Y-m-d'),
            'narration'       => $narrationValue,
            'type'            => 'StockOut',
            'user_id'         => $acceptreturnproduct->user_id,
            'branch_id'       => $acceptreturnproduct->branch_id,
            'product_id'      => $acceptreturnproduct->product_id,
            'quantity'        => $acceptreturnproduct->return_quantity,
            'price'           => $acceptreturnproduct->price,
            'requisition_id'  => $requisitionValue,
        ]); 
        
        $warehouseBranchProduct = Branch_Product::whereHas('branch', function ($query) {
                $query->where('type', 'Warehouse');
            })
            ->where('product_id', $productId)
            ->where('price', $acppriced)
            ->first();

        if ($warehouseBranchProduct) {
            $warehouseBranchProduct->stock += $stockLevel;

            $detailsStockin = json_decode($warehouseBranchProduct->details_stockin, true) ?? [];

            $detailsStockin[] = [
                'requisition' => 'return',
                'quantity'    => $stockLevel,
                'date'        => now()->format('d-m-Y'),
                'store_by'    => auth()->id()
            ];

            $warehouseBranchProduct->details_stockin = json_encode($detailsStockin);
            $warehouseBranchProduct->save();

            // ------- NEW CODE STARTS HERE -------

            $paymentMethod = request('payment_type');
            $invoiceNo = 'INV-' . strtoupper(uniqid());

            $paymentMethodDisplay = ucfirst(strtolower($paymentMethod));
            $chartAccount = ChartOfAccount::where('name', 'like', $paymentMethodDisplay)
                ->orWhere('name', 'like', '%' . $paymentMethodDisplay . '%')
                ->first(['id', 'code']);

            if (!$chartAccount) {
                $chartAccount = ChartOfAccount::where('name', 'like', '%Cash%')
                    ->orWhere('name', 'like', '%Bank%')
                    ->first(['id', 'code']) ?? ChartOfAccount::first(['id', 'code']);

                if (!$chartAccount) {
                    Toastr::error("No Chart of Accounts configured in the system!", 'Error');
                    return redirect()->back();
                }
            }

            $journalEntry = JournalEntry::create([
                'date'        => now()->toDateString(),
                'reference'   => $invoiceNo,
                'description' => 'Payment for return',
                'journal_id'  => (JournalEntry::max('journal_id') ?? 99) + 1,
                'created_by'  => auth()->id(),
            ]);

            $totalAmount = 0;

            $batch = $warehouseBranchProduct->batch;

            ProductLedger::create([
                'entry_date'            => now()->format('Y-m-d'),
                'narration'             => 'warehouseaccept', 
                'type'                  => 'stockin',
                'user_id'               => auth()->id(),
                'branch_id'             => $warehouseBranchProduct->branch_id,
                'product_id'            => $productId,
                'quantity'              => $stockLevel,
                'price'                 => $acppriced,
                'batch'                 => $batch,
                'requisition_id'        => 'return',
                'invoice_no'            => $invoiceNo,
                'payment_method'        => $paymentMethod,
                'chart_of_account_id'   => $chartAccount->id,
                'chart_of_account_code' => $chartAccount->code,
            ]);

            $product = Product::find($productId);
            $productChartAccount = null;

            if ($product && $product->code) {
                $map = ProductAccountMap::where('product_code', $product->code)->first(['account_expense_code']);
                if ($map && $map->account_expense_code) {
                    $productChartAccount = ChartOfAccount::where('code', $map->account_expense_code)->first(['id']);
                }
            }

            $chartAccountsac = ChartOfAccount::where('code', 4025)->first(['id', 'name']);

            if ($productChartAccount) {
                $amount = $acppriced;
                $totalAmount += $amount;

                JournalItem::create([
                    'journal'     => $journalEntry->id,
                    'account'     => $chartAccountsac->id,
                    'debit'       => $amount,
                    'credit'      => 0.00,
                    // 'description' => $acceptreturnproduct->reason, 
                    'description' => $product->name . '-' . $acceptreturnproduct->reason,
                    'date'        => $journalEntry->date,
                ]);
            }

            if ($totalAmount > 0) {
                JournalItem::create([
                    'journal'     => $journalEntry->id,
                    'account'     => $chartAccount->id,
                    'debit'       => 0.00,
                    'credit'      => $totalAmount,
                    'description' => 'Payment by ' . $paymentMethod,
                    'date'        => $journalEntry->date,
                ]);
            }


            // ------- NEW CODE ENDS HERE -------

            $secondJournalTotal = 0; 

            $journalEntry = JournalEntry::create([
                'date'        => now()->toDateString(),
                'reference'   => $invoiceNo,
                'description' => 'Received for return',
                'journal_id'  => (JournalEntry::max('journal_id') ?? 99) + 1,
                'created_by'  => auth()->id(),
            ]);

            if ($productChartAccount) {
                $amount = $acppriced;
                $secondJournalTotal += $amount;


                JournalItem::create([
                    'journal'     => $journalEntry->id,
                    'account'     => $productChartAccount->id,
                    'debit'       => $amount,
                    'credit'      => 0.00,
                    // 'description' => $acceptreturnproduct->reason,
                    'description' => $product->name . '-' . $acceptreturnproduct->reason,
                    'date'        => $journalEntry->date,
                ]);
            }

            if ($totalAmount > 0) {
                JournalItem::create([
                    'journal'     => $journalEntry->id,
                    'account'     => $chartAccountsac->id,
                    'debit'       => 0.00,
                    // 'credit'      => $totalAmount,
                    'credit'      => $secondJournalTotal,
                    // 'description' => 'Received by $chartAccountsac->id -> name',
                    'description' => 'Received by-' . $chartAccountsac->name,
                    'date'        => $journalEntry->date,
                ]);
            }


        }

        Toastr::success('Damage/Return entry accepted and stock updated.', 'Success');
        return redirect()->route('damage.return.list');

    } 






    


    public function acceptWarehouse($id)
    {
        $acceptreturnproduct = ProductReturnWarehouse::findOrFail($id);

        $productId = $acceptreturnproduct->product_id;
        $stockLevel = $acceptreturnproduct->return_quantity;
        $branchId = $acceptreturnproduct->branch_id;
        $acppriced = $acceptreturnproduct->price;

        $branchProduct = Branch_Product::where('branch_id', $branchId)
                                    ->where('product_id', $productId)
                                    ->where('price', $acppriced)
                                    ->first();
        
        if ($branchProduct) {
            if ($branchProduct->stock < $stockLevel) {
                toastr()->error('Insufficient stock available for return');
                return redirect()->route('warehouse.damage.return.list');
            }

            $branchProduct->stock -= $stockLevel;
            $branchProduct->details_stockout = json_encode([
                ['qty' => (string) $stockLevel, 'prc' => number_format($acppriced, 2, '.', ''), 'type' => 'return']
            ]);
            
            if ($branchProduct->save()) {
                $acceptreturnproduct->update([
                    'status' => 1,
                    'notification_status' => NULL,
                ]);
            } 

        }



            $warehouseBranchProduct = Branch_Product::whereHas('branch', function ($query) {
                    $query->where('type', 'Warehouse');
                })
                ->where('product_id', $productId)
                ->where('price', $acppriced)
                ->first();

            $narrationValue = 'warehouse';
            $requisitionValue = 'return by ' . $narrationValue;

            // ------- NEW CODE STARTS HERE -------

            $paymentMethod = request('payment_type');
            $invoiceNo = 'INV-' . strtoupper(uniqid());

            $paymentMethodDisplay = ucfirst(strtolower($paymentMethod));
            $chartAccount = ChartOfAccount::where('name', 'like', $paymentMethodDisplay)
                ->orWhere('name', 'like', '%' . $paymentMethodDisplay . '%')
                ->first(['id', 'code']);

            if (!$chartAccount) {
                $chartAccount = ChartOfAccount::where('name', 'like', '%Cash%')
                    ->orWhere('name', 'like', '%Bank%')
                    ->first(['id', 'code']) ?? ChartOfAccount::first(['id', 'code']);

                if (!$chartAccount) {
                    Toastr::error("No Chart of Accounts configured in the system!", 'Error');
                    return redirect()->back();
                }
            }

            $journalEntry = JournalEntry::create([
                'date'        => now()->toDateString(),
                'reference'   => $invoiceNo,
                'description' => 'Received for return',
                'journal_id'  => (JournalEntry::max('journal_id') ?? 99) + 1,
                'created_by'  => auth()->id(),
            ]);

            $totalAmount = 0;

            $batch = $warehouseBranchProduct->batch;

            ProductLedger::create([
                'entry_date'            => now()->format('Y-m-d'),
                'narration'             => $narrationValue,
                'type'                  => 'stockout',
                'user_id'               => auth()->id(),
                'branch_id'             => $acceptreturnproduct->branch_id,
                'product_id'            => $acceptreturnproduct->product_id,
                'quantity'              => $acceptreturnproduct->return_quantity,
                'price'                 => $acceptreturnproduct->price,
                'batch'                 => $branchProduct->batch,
                'requisition_id'        => 'return',
                'payment_method'        => request()->get('payment_type'),
                'invoice_no'            => $invoiceNo,
                'chart_of_account_id'   => $chartAccount->id,
                'chart_of_account_code' => $chartAccount->code,
            ]);

            $product = Product::find($productId);
            $productChartAccount = null;

            if ($product && $product->code) {
                $map = ProductAccountMap::where('product_code', $product->code)->first(['account_income_code']);
                if ($map && $map->account_income_code) {
                    $productChartAccount = ChartOfAccount::where('code', $map->account_income_code)->first(['id']);
                }
            }

            $chartAccountsac = ChartOfAccount::where('code', 3015)->first(['id', 'name']);

            if ($productChartAccount) {
                $amount = $acppriced;
                $totalAmount += $amount;

                JournalItem::create([
                    'journal'     => $journalEntry->id,
                    'account'     => $chartAccount->id,
                    'debit'       => $amount,
                    'credit'      => 0.00,
                    // 'description' => $acceptreturnproduct->reason, 
                    'description' => $product->name . '-' . $acceptreturnproduct->reason,
                    'date'        => $journalEntry->date,
                ]);
            }

            if ($totalAmount > 0) {
                JournalItem::create([
                    'journal'     => $journalEntry->id,
                    'account'     => $chartAccountsac->id,
                    'debit'       => 0.00,
                    'credit'      => $totalAmount,
                    'description' => 'Received by ' . $paymentMethod,
                    'date'        => $journalEntry->date,
                ]);
            }


            // ------- NEW CODE ENDS HERE -------

            $secondJournalTotal = 0; 

            $journalEntry = JournalEntry::create([
                'date'        => now()->toDateString(),
                'reference'   => $invoiceNo,
                'description' => 'Payment for return',
                'journal_id'  => (JournalEntry::max('journal_id') ?? 99) + 1,
                'created_by'  => auth()->id(),
            ]);

            if ($productChartAccount) {
                $amount = $acppriced;
                $secondJournalTotal += $amount;


                JournalItem::create([
                    'journal'     => $journalEntry->id,
                    'account'     => $chartAccountsac->id,
                    'debit'       => $amount,
                    'credit'      => 0.00,
                    // 'description' => $acceptreturnproduct->reason,
                    'description' => $product->name . '-' . $acceptreturnproduct->reason,
                    'date'        => $journalEntry->date,
                ]);
            }

            if ($totalAmount > 0) {
                JournalItem::create([
                    'journal'     => $journalEntry->id,
                    'account'     => $productChartAccount->id,
                    'debit'       => 0.00,
                    // 'credit'      => $totalAmount,
                    'credit'      => $secondJournalTotal,
                    // 'description' => 'Received by $chartAccountsac->id -> name',
                    'description' => 'Payment by-' . $chartAccountsac->name,
                    'date'        => $journalEntry->date,
                ]);
            }


    
        Toastr::success('Damage/Return entry accepted and stock updated.', 'Success');
        return redirect()->route('warehouse.damage.return.list');

    }






















    //Branch & Headoffice
    public function denyReturnProduct(Request $request)
    {
        $request->validate([
            'returnproduct_id' => 'required|exists:product_returns,id',
            'deny_note' => 'required|string|max:255',
        ]);
    
        $returnProduct = ProductReturn::findOrFail($request->returnproduct_id);
        $returnProduct->update(['status' => NULL]);
        $returnProduct->update(['notification_status' => NULL]);
        $returnProduct->deny_status = 1; 
        $returnProduct->deny_reason_note = $request->deny_note; 
        $returnProduct->save();
    
        Toastr::success('Product return denied successfully with note.', 'Success');
        return redirect()->route('damage.return.list');
    }


    //Warehouse
    public function denyReturnProductWarehouse(Request $request)
    {

    //   dd($request->all());

        $request->validate([
            'returnproduct_id' => 'required|exists:product_return_warehouses,id',
            'deny_note' => 'required|string|max:255',
        ]);
    
        $returnProduct = ProductReturnWarehouse::findOrFail($request->returnproduct_id);
        $returnProduct->update(['status' => NULL]);
        $returnProduct->update(['notification_status' => NULL]);
        $returnProduct->deny_status = 1; 
        $returnProduct->deny_reason_note = $request->deny_note; 
        $returnProduct->save();
    
        Toastr::success('Product return denied successfully with note.', 'Success');
        return redirect()->route('warehouse.damage.return.list');
    }
    




    //old okay but warehouse not check
    // public function checkDamageReturnQuantity(Request $request)
    // {
    //     $branchId = $request->branch_id;
    //     $productId = $request->product_id;
    //     $requestedQuantity = $request->quantity;
    
    //     $pendingReturns = ProductReturn::where('branch_id', $branchId)
    //                                     ->where('product_id', $productId)
    //                                     ->where('status', 0)
    //                                     ->get();

    //     $allPendingReturnValue = $pendingReturns->sum('return_quantity');
    
    //     $branchProduct = Branch_Product::where('branch_id', $branchId)
    //                                   ->where('product_id', $productId)
    //                                   ->first();
    
    //     $restOfStock = $branchProduct ? $branchProduct->stock : 0;

    //     $checkAllStock = $restOfStock - $allPendingReturnValue;
    
    //     if ($requestedQuantity > $checkAllStock) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => "Quantity is greater than the available stock. Please check input quantity or  the pending  'damage/return' product list."
    //         ]);
    //     }
    
    //     return response()->json([
    //         'success' => true
    //     ]);
    // }




    // public function checkDamageReturnQuantity(Request $request)
    // {
    //     $branchId = $request->branch_id;
    //     $productId = $request->product_id;
    //     $requestedQuantity = $request->quantity;
        
    //     if (auth()->user()->branch_type == "Warehouse") {
    //         $requestedprice = $request->price;
            
    //         $pendingReturns = ProductReturnWarehouse::where('branch_id', $branchId)
    //                                     ->where('product_id', $productId)
    //                                     ->where('status', 0)
    //                                     ->get();
    //         $allPendingReturnValue = $pendingReturns->sum('return_quantity');

    //         $branchProduct = Branch_Product::where('branch_id', $branchId)
    //                                 ->where('product_id', $productId)  
    //                                 ->where('price', $requestedprice) 
    //                                 ->first();

    //         $restOfStock = $branchProduct ? $branchProduct->stock : 0;
    //         $checkAllStock = $restOfStock - $allPendingReturnValue;


    //         if ($requestedQuantity > $checkAllStock) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => "Quantity is greater than the available stock. Please check input quantity or the pending 'damage/return' product list."
    //             ]);
    //         }
            
    //         return response()->json([
    //             'success' => true
    //         ]);





    //     } else {
    //         $pendingReturns = ProductReturn::where('branch_id', $branchId)
    //                                     ->where('product_id', $productId)
    //                                     ->where('status', 0)
    //                                     ->get();

    //         $allPendingReturnValue = $pendingReturns->sum('return_quantity');
        
    //         $branchProduct = Branch_Product::where('branch_id', $branchId)
    //                                 ->where('product_id', $productId)
    //                                 ->first();
        
    //         $restOfStock = $branchProduct ? $branchProduct->stock : 0;
    //         $checkAllStock = $restOfStock - $allPendingReturnValue;
    //     }
        
    //     if ($requestedQuantity > $checkAllStock) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => "Quantity is greater than the available stock. Please check input quantity or the pending 'damage/return' product list."
    //         ]);
    //     }
        
    //     return response()->json([
    //         'success' => true
    //     ]);



    // }






    public function checkDamageReturnQuantity(Request $request)
    {
        $branchId = $request->branch_id;
        $productId = $request->product_id;
        $requestedQuantity = $request->quantity;
        $requestedprice = $request->price;

        if (auth()->user()->branch_type == "Warehouse") {
            $requestedprice = $request->price;
            
            $pendingReturns = ProductReturnWarehouse::where('branch_id', $branchId)
                ->where('product_id', $productId)
                ->where('price', $requestedprice) 
                ->where('status', 0)
                ->get();

            $allPendingReturnValue = $pendingReturns->sum('return_quantity');

            $branchProduct = Branch_Product::where('branch_id', $branchId)
                ->where('product_id', $productId)
                ->where('price', $requestedprice)
                ->first();


                if (!$branchProduct) {
                    return response()->json([
                        'success' => false,
                        'message' => "Invalid price"
                    ]);
                }

            $restOfStock = $branchProduct ? $branchProduct->stock : 0;
            $checkAllStock = $restOfStock - $allPendingReturnValue;

            if ($requestedQuantity > $checkAllStock) {
                return response()->json([
                    'success' => false,
                    'message' => "Quantity is greater than the available stock. Please check input quantity or the pending 'damage/return' product list."
                ]);
            }

            return response()->json([
                'success' => true
            ]);
        } 

        else {
            $pendingReturns = ProductReturn::where('branch_id', $branchId)
                ->where('product_id', $productId)
                ->where('price', $requestedprice) 
                ->where('status', 0)
                ->get();

            $allPendingReturnValue = $pendingReturns->sum('return_quantity');

            $branchProduct = Branch_Product::where('branch_id', $branchId)
                ->where('product_id', $productId)
                ->first(); 


            $details = json_decode($branchProduct->remain_details, true);
            $matched = collect($details)->firstWhere('prc', $requestedprice);

            if (!$matched) {
                return response()->json([
                    'success' => false,
                    'message' => "Price does not match, so not process for return."
                ]);

            }

            // Get total quantity available for the given price
            $totalQty = collect($details)
                ->where('prc', $requestedprice)
                ->sum(function ($item) {
                    return (int) $item['qty'];
                });

            if ($requestedQuantity > $totalQty) {
                return response()->json([
                    'success' => false,
                    'message' => "This price has not enough quantity as you request."
                ]);


            }


            $restOfStock = $totalQty ? $totalQty : 0;
            $checkAllStock = $restOfStock - $allPendingReturnValue;

            // $restOfStock = $branchProduct ? $branchProduct->stock : 0;
            // $checkAllStock = $restOfStock - $allPendingReturnValue;

            if ($requestedQuantity > $checkAllStock) {
                return response()->json([
                    'success' => false,
                    'message' => "Quantity is greater than the available stock. Please check input quantity or the pending 'damage/return' product list."
                ]);
            }

            return response()->json([
                'success' => true
            ]);
        }


        
    }










    public function handleReturnProductPage()
    {

        DB::table('product_returns') 
            ->where('notification_status', 0)
            ->update(['notification_status' => 1]);

        Toastr::success('Notifications cleared.', 'Success');
        return redirect()->route('damage.return.list');
    }








}
