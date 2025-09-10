<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductLedger;
use App\Models\ProductReturn;
use App\Models\Branch_Product;
use App\Models\ProductExpense;
use App\Models\ProductCategory;
use App\Models\ProductLedgerBH;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Brian2694\Toastr\Facades\Toastr;

use Barryvdh\DomPDF\Facade\Pdf as PDF;


class ProductExpenseController extends Controller
{
    
    // public function expenseList()
    // {
    //     $productexpense = ProductExpense::get();
    //     return view('productExpense.list', compact('productexpense'));
    // }
  


    // public function expenseList()
    // {
    //     $user = auth()->user();
    //     $userBranch = $user->branch->type;

    //     if ($userBranch === 'Branch') {
    //         $productexpense = ProductExpense::where('branch_id', $user->branch_id)->get();
    //     } elseif ($userBranch === 'Headoffice' || $userBranch === 'Warehouse') {
    //         $productexpense = ProductExpense::all();
    //     } else {
    //         $productexpense = ProductExpense::where('branch_id', $user->branch_id)->get();
    //     }

    //     return view('productExpense.list', compact('productexpense'));
    // }


    // public function expenseList()
    // {
    //     $user = auth()->user();
    //     $userBranch = $user->branch->type;

    //     if ($userBranch === 'Branch' || $userBranch === 'Warehouse') {
    //         $productexpense = ProductExpense::where('branch_id', $user->branch_id)
    //                                         ->orderBy('created_at', 'desc')
    //                                         ->get();
    //     } elseif ($userBranch === 'Headoffice') {
    //         $productexpense = ProductExpense::orderBy('created_at', 'desc')
    //                                         ->get();
    //     } else {
    //         $productexpense = ProductExpense::where('branch_id', $user->branch_id)
    //                                         ->orderBy('created_at', 'desc')
    //                                         ->get();
    //     }

    //     return view('productExpense.list', compact('productexpense'));
    // }


    public function expenseList()
    {
        $user = auth()->user();
        $userBranch = $user->branch->type;
    
        if ($userBranch === 'Branch') {
            $productexpense = ProductExpense::where('branch_id', $user->branch_id)
                                            ->where('user_id', $user->id)
                                            ->orderBy('created_at', 'desc')
                                            ->get();
        } elseif ($userBranch === 'Warehouse') {
            $productexpense = ProductExpense::where('branch_id', $user->branch_id)
                                            ->orderBy('created_at', 'desc')
                                            ->get();
        } elseif ($userBranch === 'Headoffice') {
            $productexpense = ProductExpense::orderBy('created_at', 'desc')
                                            ->get();
        } else {
            $productexpense = ProductExpense::where('branch_id', $user->branch_id)
                                            ->orderBy('created_at', 'desc')
                                            ->get();
        }
        return view('productExpense.list', compact('productexpense'));
    }
    










    public function expenseEntry()
    {
        $user = auth()->user();
    
        $userBranch = $user->branch->type; 
        
        if ($userBranch == 'Branch') {
            $branches = Branch::where('id', $user->branch_id)->pluck('name', 'id');
        }
   
        // elseif ($userBranch == 'Headoffice' || $userBranch == 'Warehouse') {
        //     $branches = Branch::pluck('name', 'id');
        // } 
        
        else {
            $branches = Branch::where('id', $user->branch_id)->pluck('name', 'id');
        }
    
        $products = Product::all();

        return view('productExpense.create', compact('products', 'branches', 'userBranch'));
    }
    



    public function getBranchProducts($branchId)
    {
        $branchProducts = Branch_Product::where('branch_id', $branchId)
            ->with('product:id,name')
            ->get(['product_id', 'stock']);
    
        return response()->json($branchProducts);
    }
    



    // public function expenseStore(Request $request)
    // {
    //     $request->validate([
    //         'branch_id' => 'required',
    //         'consignee_name' => 'required',
    //         'date_from' => 'required|date',
    //         'items.*.name' => 'required',
    //         'items.*.price' => 'required|numeric',
    //         'items.*.amount' => 'required|numeric',
    //     ]);

    //     foreach ($request->items as $item) {
    //         ProductExpense::create([
    //             'branch_id' => $request->branch_id,
    //             'consignee_name' => $request->consignee_name,
    //             'expense_date' => $request->date_from,
    //             'user_id' => auth()->id(),
    //             'product_id' => $item['name'],
    //             'expense_amount' => $item['amount'],
    //         ]);
    //     }

    //     Toastr::success('Expense entry saved successfully.', 'Success');
    //     return redirect()->route('product.expense.entry');
    // }







    //okay last old 
    // public function expenseStore(Request $request)
    // {
    //     $request->validate([
    //         'branch_id' => 'required',
    //         'consignee_name' => 'required',
    //         'date_from' => 'required|date',
    //         'items.*.name' => 'required',
    //         'items.*.price' => 'required|numeric',
    //         'items.*.amount' => 'required|numeric',
    //     ]);

    //     foreach ($request->items as $item) {
    //         $info = Product::find($item['name']);
    //         $expense_price = round($item['amount'] * $info->price,2);
    //         ProductExpense::create([
    //             'branch_id' => $request->branch_id,
    //             'consignee_name' => $request->consignee_name,
    //             'expense_date' => $request->date_from,
    //             'user_id' => auth()->id(),
    //             'product_id' => $item['name'],
    //             'expense_amount' => $item['amount'],
    //             'expense_price' => $expense_price,
    //         ]);

    //         $branchProduct = Branch_Product::where('branch_id', $request->branch_id)
    //             ->where('product_id', $item['name'])
    //             ->first();

    //         if ($branchProduct) {
    //             $branchProduct->stock -= $item['amount'];
    //             $branchProduct->save();
    //         }

    //         // Product Ledger Entry
    //         ProductLedger::create([
    //             'entry_date' => date('Y-m-d'),
    //             'narration' => 'Expense',
    //             'type' => 'Expense',
    //             'user_id' => auth()->id(),
    //             'branch_id' => $request->branch_id,
    //             'product_id' => $item['name'],
    //             'consignee_name' => $request->consignee_name,
    //             'quantity' => $item['amount'],
    //             'price' => $expense_price,
    //         ]);
    //     }

    //     Toastr::success('Expense entry saved successfully.', 'Success');
    //     return redirect()->route('product.expense.list');
    // }


    

    // public function expenseStore(Request $request)
    // {
    //     $request->validate([
    //         'branch_id' => 'required',
    //         'consignee_name' => 'required',
    //         'date_from' => 'required|date',
    //         'items.*.name' => 'required',
    //         'items.*.price' => 'required|numeric',
    //         'items.*.amount' => 'required|numeric',
    //     ]);

    //     foreach ($request->items as $item) {
    //         $info = Product::find($item['name']);
    //         $expense_price = round($item['amount'] * $info->price,2);
    //         ProductExpense::create([
    //             'branch_id' => $request->branch_id,
    //             'consignee_name' => $request->consignee_name,
    //             'expense_date' => $request->date_from,
    //             'user_id' => auth()->id(),
    //             'product_id' => $item['name'],
    //             'expense_amount' => $item['amount'],
    //             'expense_price' => $expense_price,
    //         ]);

    //         $branchProduct = Branch_Product::where('branch_id', $request->branch_id)
    //             ->where('product_id', $item['name'])
    //             ->first();

    //         if ($branchProduct) {
    //             $branchProduct->stock -= $item['amount'];
    //             $branchProduct->save();
    //         }

    //         // Product Ledger Entry
    //         ProductLedger::create([
    //             'entry_date' => date('Y-m-d'),
    //             'narration' => 'Expense',
    //             'type' => 'Expense',
    //             'user_id' => auth()->id(),
    //             'branch_id' => $request->branch_id,
    //             'product_id' => $item['name'],
    //             'consignee_name' => $request->consignee_name,
    //             'quantity' => $item['amount'],
    //             'price' => $expense_price,
    //         ]);
    //     }

    //     Toastr::success('Expense entry saved successfully.', 'Success');
    //     return redirect()->route('product.expense.list');
    // }



    public function expenseStore(Request $request)
    {
        $request->validate([
            'branch_id' => 'required',
            'consignee_name' => 'required',
            'date_from' => 'required|date',
            'items.*.name' => 'required',
            'items.*.price' => 'required|numeric',
            'items.*.amount' => 'required|numeric',
        ]);

        $branchId = $request->branch_id;
        $branch = Branch::find($branchId);
        $narrationType = $branch && $branch->type === 'Headoffice' ? 'Headoffice' : 'Branch';

        foreach ($request->items as $item) {
            $productId = $item['name'];
            $amountExpenditure = $item['amount'];

            $productInfo = Product::find($productId);

            $branchProduct = Branch_Product::where('branch_id', $branchId)
                ->where('product_id', $productId)
                ->first();

            $detailsStockOut = [];
            $expensePriceJson = [];

            if ($branchProduct) {
                
                $branchProduct->stock -= $amountExpenditure;

                $remainDetails = json_decode($branchProduct->remain_details, true);
                $deductQty = $amountExpenditure;

                foreach ($remainDetails as &$rdItem) {
                    if ($deductQty <= 0) break;

                    $itemQty = (int) $rdItem['qty'];
                    $itemPrc = $rdItem['prc'];

                    if ($itemQty <= 0) continue;

                    $deductNow = min($itemQty, $deductQty);
                    $rdItem['qty'] = $itemQty - $deductNow;
                    $deductQty -= $deductNow;

                    $fifoEntry = [
                        'qty' => (string) $deductNow,
                        'prc' => $itemPrc,
                    ];

                    $detailsStockOut[] = $fifoEntry;
                    $expensePriceJson[] = $fifoEntry;
                }

                $branchProduct->remain_details = json_encode($remainDetails);

                $newStockOutEntry = json_encode($detailsStockOut);
                $existingStockOut = $branchProduct->details_stockout ?? '';
                $branchProduct->details_stockout = trim($existingStockOut . "\n" . $newStockOutEntry);

                $branchProduct->save();
            }

            ProductExpense::create([
                'branch_id' => $branchId,
                'consignee_name' => $request->consignee_name,
                'expense_date' => $request->date_from,
                'user_id' => auth()->id(),
                'product_id' => $productId,
                'expense_amount' => $amountExpenditure,
                'expense_price' => json_encode($expensePriceJson),
            ]);

            foreach ($expensePriceJson as $entry) {
                ProductLedgerBH::create([
                    'entry_date'     => $request->date_from,
                    'narration'      => $narrationType,
                    'type'           => 'StockOut',
                    'user_id'        => auth()->id(),
                    'branch_id'      => $branchId,
                    'product_id'     => $productId,
                    'consignee_name' => $request->consignee_name,
                    'quantity'       => $entry['qty'],
                    'price'          => $entry['prc'],
                    'requisition_id' => 'expense by ' . $narrationType,
                ]);
            }
        }

        Toastr::success('Expense entry saved successfully.', 'Success');
        return redirect()->route('product.expense.list');
    }






    //friday last new add this method route wise 
    public function damageReturnCheckStock(Request $request)
    {
        $userId = auth()->id();
        $branchId = $request->branch_id;
        $productId = $request->product_id;
        $price = $request->price;
        $quantity = $request->quantity;

        // Count how many items already pending for this user, branch, product and price
        $pendingCount = DB::table('product_returns')
            ->where('branch_id', $branchId)
            ->where('product_id', $productId)
            ->where('user_id', $userId)
            ->where('price', $price)
            ->where('status', 0)
            ->count();

        // Fetch price breakdown
        $product = \App\Models\Branch_Product::where('branch_id', $branchId)
            ->where('product_id', $productId)
            ->first();

        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found']);
        }

        $details = json_decode($product->remain_details, true);
        $matched = collect($details)->firstWhere('prc', $price);

        if (!$matched) {
            return response()->json(['success' => false, 'message' => 'Price not found in stock']);
        }

        $availableQty = intval($matched['qty']) - $pendingCount;

        if ($availableQty <= 0 || $availableQty < $quantity) {
            return response()->json(['success' => false, 'message' => 'This price product is already pending return or unavailable']);
        }

        return response()->json(['success' => true]);

        return response()->json(['success' => false, 'message' => 'This price is already pending for return.']);

    }















    //okay old before remaining

    // public function expenseStoresingle(Request $request)
    // {
    //     $request->validate([
    //         'branch_id' => 'required',
    //         'product_id' => 'required',
    //         'consignee_name' => 'required',
    //         'expense_date' => 'required|date',
    //         'amount_expenditure' => 'required|numeric|min:1',
    //     ]);

    //     ProductExpense::create([
    //         'branch_id' => $request->branch_id,
    //         'consignee_name' => $request->consignee_name,
    //         'expense_date' => $request->expense_date,
    //         'user_id' => auth()->id(),
    //         'product_id' => $request->product_id,
    //         'expense_amount' => $request->amount_expenditure,
    //     ]);

    //     $branchProduct = Branch_Product::where('branch_id', $request->branch_id)
    //         ->where('product_id', $request->product_id)
    //         ->first();

    //     if ($branchProduct) {
    //         $branchProduct->stock -= $request->amount_expenditure;
    //         $branchProduct->save();
            
    //     }

    //     $branch = DB::table('branch')->where('id', $request->branch_id)->first();
        
    //     Toastr::success('Expense entry saved successfully.', 'Success');
    //     return redirect()->route('stock.view', ['branch_id' => $branch->id]);

    // }





    // public function expenseStoresingle(Request $request)
    // {
    //     $request->validate([
    //         'branch_id' => 'required',
    //         'product_id' => 'required',
    //         'consignee_name' => 'required',
    //         'expense_date' => 'required|date',
    //         'amount_expenditure' => 'required|numeric|min:1',
    //     ]);

    //     // Create the expense entry
    //     ProductExpense::create([
    //         'branch_id' => $request->branch_id,
    //         'consignee_name' => $request->consignee_name,
    //         'expense_date' => $request->expense_date,
    //         'user_id' => auth()->id(),
    //         'product_id' => $request->product_id,
    //         'expense_amount' => $request->amount_expenditure,
    //     ]);

    //     // Fetch the branch product
    //     $branchProduct = Branch_Product::where('branch_id', $request->branch_id)
    //         ->where('product_id', $request->product_id)
    //         ->first();

    //     if ($branchProduct) {
    //         // Reduce overall stock
    //         $branchProduct->stock -= $request->amount_expenditure;
    //         $branchProduct->save();

    //         // FIFO deduction from remain_details
    //         $remainDetails = json_decode($branchProduct->remain_details, true);
    //         $deductQty = $request->amount_expenditure;
    //         $detailsStockOut = [];

    //         foreach ($remainDetails as &$item) {
    //             if ($deductQty <= 0) break;

    //             $itemQty = (int) $item['qty'];
    //             $itemPrc = $item['prc'];

    //             if ($itemQty <= 0) continue;

    //             $deductNow = min($itemQty, $deductQty);
    //             $item['qty'] = $itemQty - $deductNow;
    //             $deductQty -= $deductNow;

    //             $detailsStockOut[] = [
    //                 'qty' => (string) $deductNow,
    //                 'prc' => $itemPrc,
    //             ];
    //         }

    //         // Update the columns
    //         $branchProduct->remain_details = json_encode($remainDetails);
    //         $branchProduct->details_stockout = json_encode($detailsStockOut);
    //         $branchProduct->save();
    //     }

    //     $branch = DB::table('branch')->where('id', $request->branch_id)->first();

    //     Toastr::success('Expense entry saved successfully.', 'Success');
    //     return redirect()->route('stock.view', ['branch_id' => $branch->id]);
    // }



    public function expenseStoresingle(Request $request)
    {
        $request->validate([
            'branch_id' => 'required',
            'product_id' => 'required',
            'consignee_name' => 'required',
            'expense_date' => 'required|date',
            'amount_expenditure' => 'required|numeric|min:1',
        ]);

        // Fetch the branch product
        $branchProduct = Branch_Product::where('branch_id', $request->branch_id)
            ->where('product_id', $request->product_id)
            ->first();

        $detailsStockOut = [];
        $expensePriceJson = [];

        if ($branchProduct) {
            // Reduce overall stock
            $branchProduct->stock -= $request->amount_expenditure;
            $branchProduct->save();

            // FIFO deduction from remain_details
            $remainDetails = json_decode($branchProduct->remain_details, true);
            $deductQty = $request->amount_expenditure;

            foreach ($remainDetails as &$item) {
                if ($deductQty <= 0) break;

                $itemQty = (int) $item['qty'];
                $itemPrc = $item['prc'];

                if ($itemQty <= 0) continue;

                $deductNow = min($itemQty, $deductQty);
                $item['qty'] = $itemQty - $deductNow;
                $deductQty -= $deductNow;

                $fifoEntry = [
                    'qty' => (string) $deductNow,
                    'prc' => $itemPrc,
                ];

                $detailsStockOut[] = $fifoEntry;
                $expensePriceJson[] = $fifoEntry;
            }

            // Update Branch_Product columns
            $branchProduct->remain_details = json_encode($remainDetails);

            // $branchProduct->details_stockout = json_encode($detailsStockOut); 

            $newStockOutEntry = json_encode($detailsStockOut);
            $existingStockOut = $branchProduct->details_stockout ?? '';
            $branchProduct->details_stockout = trim($existingStockOut . "\n" . $newStockOutEntry);


            $branchProduct->save();
        }

        // Create the expense entry with expense_price
        ProductExpense::create([
            'branch_id' => $request->branch_id,
            'consignee_name' => $request->consignee_name,
            'expense_date' => $request->expense_date,
            'user_id' => auth()->id(),
            'product_id' => $request->product_id,
            'expense_amount' => $request->amount_expenditure,
            'expense_price' => json_encode($expensePriceJson),
        ]);

        
        $branchId = $request->branch_id;
        $branch = Branch::find($branchId);
        $narrationType = $branch && $branch->type === 'Headoffice' ? 'Headoffice' : 'Branch';

        foreach ($expensePriceJson as $item) {
            ProductLedgerBH::create([
                'entry_date'     => $request->expense_date,
                'narration'      => $narrationType,
                'type'           => 'StockOut',
                'user_id'        => $userId ?? auth()->id(),
                'branch_id'      => $branchId,
                'product_id'     => $request->product_id,
                'consignee_name' => $request->consignee_name,
                'quantity'       => $item['qty'],
                'price'          => $item['prc'],
                'requisition_id' => 'expense by ' . $narrationType,
            ]);
        }



        $branch = DB::table('branch')->where('id', $request->branch_id)->first();

        Toastr::success('Expense entry saved successfully.', 'Success');
        return redirect()->route('stock.view', ['branch_id' => $branch->id]);
    }





    




    // public function ledgerReport()
    // {
    //     $products = Product::all();
    //     $branches = Branch::all(); 
    //     $productexpensereport = ProductExpense::all();

    //     return view('ledgerReport.ledger', compact('productexpensereport', 'branches', 'products'));
    // }




    public function ledgerReportOld(Request $request)
    {
        $products = Product::all();
        $branches = Branch::all();

        $query = ProductExpense::query();
    
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }
    
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }
    
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('expense_date', [$request->start_date, $request->end_date]);
        }
    
        $productexpensereport = $query->get();
    
        return view('ledgerReport.ledger', compact('productexpensereport', 'branches', 'products'));
    }


    public function stockInOutReport(Request $request)
    {
        $role = auth()->user()->getRoleNames()->first();
        $products = Product::all();
        $branches = Branch::all();

        $query = ProductLedger::query();

        if($role=='Branch'){
            $query->where('user_id', auth()->id());
        }
    
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }
    
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }
    
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
    
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('entry_date', [$request->start_date, $request->end_date]);
        }
    
        $productledger = $query->get();
    
        return view('reportNew.stock-in-out', compact('productledger', 'branches', 'products'));
    }

    public function productLedger(Request $request)
    {
        $products = Product::orderBy('name', 'asc')->get();
        $branches = Branch::orderBy('name', 'asc')->get();

        $openingBalance = 0;
        $productledger = [];
        $query = ProductLedger::query();
        $queryOpening = ProductLedger::query();
    
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
            $queryOpening->where('branch_id', $request->branch_id);
        }
        
        if ($request->filled('start_date')) {
            $query->where("entry_date", '>=', $request->start_date);
            $queryOpening->where("entry_date", '<', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->where("entry_date", '<=', $request->end_date);
        }

        
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
            $productledger = $query->get();
            
            $queryOpening->where('product_id', $request->product_id);
            $ledgersOpening = $queryOpening->get();
            if($ledgersOpening){
                foreach ($ledgersOpening as $row) {
                    if($row->type=='Stock In'){
                        $openingBalance = $openingBalance + $row->quantity;
                    }
                    if($row->type=='Expense'){
                        $openingBalance = $openingBalance - $row->quantity;
                    }
                }
            }
        }
    
    
        return view('reportNew.product-ledger', compact('productledger', 'branches', 'products','openingBalance'));
    }
    





    // public function storeproductLedger(Request $request)
    // {
    //     $products = Product::orderBy('name', 'asc')->get();
    //     $categories = ProductCategory::orderBy('name', 'asc')->get();
    
    //     $productId = $request->input('product_id');
    //     $startDate = $request->input('start_date');
    //     $endDate = $request->input('end_date');
    
    //     $branchId = Branch::where('type', 'Warehouse')->pluck('id');
    //     $branchProducts = Branch_Product::whereIn('branch_id', $branchId)
    //                             ->pluck('product_id')
    //                             ->toArray();
    
    //     $branchProductDetails = Branch_Product::whereIn('product_id', $branchProducts)
    //         ->whereIn('branch_id', $branchId)
    //         ->select('product_id', 'price')
    //         ->get();
    
    //     $productDetails = Product::with('category')
    //         ->whereIn('id', $branchProducts)
    //         ->select('id', 'name', 'product_category_id') 
    //         ->get()
    //         ->keyBy('id');
    
    //     $previousStockInQuantities = DB::table('product_ledgers')
    //         ->whereIn('product_id', $branchProducts)
    //         ->where('type', 'stockin')
    //         ->when($startDate, function ($q) use ($startDate) {
    //             return $q->whereDate('entry_date', '<', $startDate); 
    //         })
    //         ->select('product_id', 'price', DB::raw('SUM(quantity) as total_quantity'))
    //         ->groupBy('product_id', 'price')
    //         ->get()
    //         ->keyBy(function ($item) {
    //             return $item->product_id . '-' . $item->price; 
    //         });
    
    //     $previousStockOutQuantities = DB::table('product_ledgers')
    //         ->whereIn('product_id', $branchProducts)
    //         ->where('type', 'stockout')
    //         ->when($startDate, function ($q) use ($startDate) {
    //             return $q->whereDate('entry_date', '<', $startDate); 
    //         })
    //         ->select('product_id', 'price', DB::raw('SUM(quantity) as total_quantity'))
    //         ->groupBy('product_id', 'price')
    //         ->get()
    //         ->keyBy(function ($item) {
    //             return $item->product_id . '-' . $item->price; 
    //         });
    
    //     $finalQuantities = [];
    //     foreach ($previousStockInQuantities as $key => $stockIn) {
    //         $stockOut = $previousStockOutQuantities[$key] ?? null;
    //         $finalQuantities[$key] = $stockIn->total_quantity - ($stockOut ? $stockOut->total_quantity : 0);
    //     }
    
    //     $ledgerData = DB::table('product_ledgers')
    //         ->whereIn('product_id', $branchProducts)
    //         ->where('type', 'stockin')
    //         ->when($productId && $productId != 'all', function ($q) use ($productId) {
    //             return $q->where('product_id', $productId);
    //         })
    //         ->when($startDate, function ($q) use ($startDate) {
    //             return $q->whereDate('entry_date', '>=', $startDate);
    //         })
    //         ->when($endDate, function ($q) use ($endDate) {
    //             return $q->whereDate('entry_date', '<=', $endDate);
    //         })
    //         ->select('product_id', 'price', DB::raw('SUM(quantity) as total_quantity'))
    //         ->groupBy('product_id', 'price')
    //         ->get()
    //         ->keyBy(function ($item) {
    //             return $item->product_id . '-' . $item->price;
    //         });
    
    //     $salesData = DB::table('product_ledgers')
    //         ->whereIn('product_id', $branchProducts)
    //         ->where('type', 'stockout')
    //         ->when($productId && $productId != 'all', function ($q) use ($productId) {
    //             return $q->where('product_id', $productId);
    //         })
    //         ->when($startDate, function ($q) use ($startDate) {
    //             return $q->whereDate('entry_date', '>=', $startDate);
    //         })
    //         ->when($endDate, function ($q) use ($endDate) {
    //             return $q->whereDate('entry_date', '<=', $endDate);
    //         })
    //         ->select('product_id', 'price', DB::raw('SUM(quantity) as total_quantity'))
    //         ->groupBy('product_id', 'price')
    //         ->get()
    //         ->keyBy(function ($item) {
    //             return $item->product_id . '-' . $item->price;
    //         });
    
    //     return view('reportNew.store-product_ledger', compact('products', 'categories', 'ledgerData', 'productDetails', 'branchProducts', 'branchProductDetails', 'finalQuantities', 'salesData'));
    // } 



    //all is okay before single product search
    // public function storeproductLedger(Request $request)
    // {
    //     $products = Product::orderBy('name', 'asc')->get();
    //     $categories = ProductCategory::orderBy('name', 'asc')->get();
    
    //     $productId = $request->input('product_id');
    //     $startDate = $request->input('start_date');
    //     $endDate = $request->input('end_date');
    
    //     $branchId = Branch::where('type', 'Warehouse')->pluck('id');
    //     $branchProducts = Branch_Product::whereIn('branch_id', $branchId)
    //                             ->pluck('product_id')
    //                             ->toArray();
    
    //     $branchProductDetails = Branch_Product::whereIn('product_id', $branchProducts)
    //         ->whereIn('branch_id', $branchId)
    //         ->select('product_id', 'price')
    //         ->get();
    
    //     $productDetails = Product::with('category')
    //         ->whereIn('id', $branchProducts)
    //         ->select('id', 'name', 'product_category_id') 
    //         ->get()
    //         ->keyBy('id');
    
    //     $previousStockInQuantities = DB::table('product_ledgers')
    //         ->whereIn('product_id', $branchProducts)
    //         ->where('type', 'stockin')
    //         ->when($startDate, function ($q) use ($startDate) {
    //             return $q->whereDate('entry_date', '<', $startDate); 
    //         })
    //         ->select('product_id', 'price', DB::raw('SUM(quantity) as total_quantity'))
    //         ->groupBy('product_id', 'price')
    //         ->get()
    //         ->keyBy(function ($item) {
    //             return $item->product_id . '-' . $item->price; 
    //         });
    
    //     $previousStockOutQuantities = DB::table('product_ledgers')
    //         ->whereIn('product_id', $branchProducts)
    //         ->where('type', 'stockout')
    //         ->when($startDate, function ($q) use ($startDate) {
    //             return $q->whereDate('entry_date', '<', $startDate); 
    //         })
    //         ->select('product_id', 'price', DB::raw('SUM(quantity) as total_quantity'))
    //         ->groupBy('product_id', 'price')
    //         ->get()
    //         ->keyBy(function ($item) {
    //             return $item->product_id . '-' . $item->price; 
    //         });
    
    //     $finalQuantities = [];
    //     foreach ($previousStockInQuantities as $key => $stockIn) {
    //         $stockOut = $previousStockOutQuantities[$key] ?? null;
    //         $finalQuantities[$key] = $stockIn->total_quantity - ($stockOut ? $stockOut->total_quantity : 0);
    //     }
    
    //     $ledgerData = DB::table('product_ledgers')
    //         ->whereIn('product_id', $branchProducts)
    //         ->where('type', 'stockin')
    //         ->when($productId && $productId != 'all', function ($q) use ($productId) {
    //             return $q->where('product_id', $productId);
    //         })
    //         ->when($startDate, function ($q) use ($startDate) {
    //             return $q->whereDate('entry_date', '>=', $startDate);
    //         })
    //         ->when($endDate, function ($q) use ($endDate) {
    //             return $q->whereDate('entry_date', '<=', $endDate);
    //         })
    //         ->select('product_id', 'price', DB::raw('SUM(quantity) as total_quantity'))
    //         ->groupBy('product_id', 'price')
    //         ->get()
    //         ->keyBy(function ($item) {
    //             return $item->product_id . '-' . $item->price;
    //         });
    
    //     $salesData = DB::table('product_ledgers')
    //         ->whereIn('product_id', $branchProducts)
    //         ->where('type', 'stockout')
    //         ->when($productId && $productId != 'all', function ($q) use ($productId) {
    //             return $q->where('product_id', $productId);
    //         })
    //         ->when($startDate, function ($q) use ($startDate) {
    //             return $q->whereDate('entry_date', '>=', $startDate);
    //         })
    //         ->when($endDate, function ($q) use ($endDate) {
    //             return $q->whereDate('entry_date', '<=', $endDate);
    //         })
    //         ->select('product_id', 'price', DB::raw('SUM(quantity) as total_quantity'))
    //         ->groupBy('product_id', 'price')
    //         ->get()
    //         ->keyBy(function ($item) {
    //             return $item->product_id . '-' . $item->price;
    //         });
    
    //         $groupedProducts = [];
    //         foreach ($branchProductDetails as $product) {
    //             $categoryName = $productDetails[$product->product_id]->category->name ?? 'Uncategorized';
    //             $groupedProducts[$categoryName][] = $product;
    //         }

    //     return view('reportNew.store-product_ledger', compact('products', 'categories', 'ledgerData', 'productDetails', 'branchProducts', 'branchProductDetails', 'finalQuantities', 'salesData', 'groupedProducts'));

    // }







    //single product search is okay sequention product and dropdown product list problem
    // public function storeproductLedger(Request $request)
    // {
    //     $products = Product::orderBy('name', 'asc')->get();
    //     $categories = ProductCategory::orderBy('name', 'asc')->get();
    
    //     $productId = $request->input('product_id');
    //     $startDate = $request->input('start_date');
    //     $endDate = $request->input('end_date');
    
    //     $branchId = Branch::where('type', 'Warehouse')->pluck('id');
        
    //     $branchProducts = Branch_Product::whereIn('branch_id', $branchId)
    //                             ->when($productId && $productId != 'all', function ($q) use ($productId) {
    //                                 return $q->where('product_id', $productId);
    //                             })
    //                             ->pluck('product_id')
    //                             ->toArray();
    
    //     $branchProductDetails = Branch_Product::whereIn('product_id', $branchProducts)
    //         ->whereIn('branch_id', $branchId)
    //         ->select('product_id', 'price')
    //         ->get();
    
    //     $productDetails = Product::with('category')
    //         ->whereIn('id', $branchProducts)
    //         ->select('id', 'name', 'product_category_id') 
    //         ->get()
    //         ->keyBy('id');
    

    //         $previousStockInQuantities = DB::table('product_ledgers')
    //         ->whereIn('product_id', $branchProducts)
    //         ->where('type', 'stockin')
    //         ->when($startDate, function ($q) use ($startDate) {
    //             return $q->whereDate('entry_date', '<', $startDate); 
    //         })
    //         ->select('product_id', 'price', DB::raw('SUM(quantity) as total_quantity'))
    //         ->groupBy('product_id', 'price')
    //         ->get()
    //         ->keyBy(function ($item) {
    //             return $item->product_id . '-' . $item->price; 
    //         });
    
    //     $previousStockOutQuantities = DB::table('product_ledgers')
    //         ->whereIn('product_id', $branchProducts)
    //         ->where('type', 'stockout')
    //         ->when($startDate, function ($q) use ($startDate) {
    //             return $q->whereDate('entry_date', '<', $startDate); 
    //         })
    //         ->select('product_id', 'price', DB::raw('SUM(quantity) as total_quantity'))
    //         ->groupBy('product_id', 'price')
    //         ->get()
    //         ->keyBy(function ($item) {
    //             return $item->product_id . '-' . $item->price; 
    //         });
    
    //     $finalQuantities = [];
    //     foreach ($previousStockInQuantities as $key => $stockIn) {
    //         $stockOut = $previousStockOutQuantities[$key] ?? null;
    //         $finalQuantities[$key] = $stockIn->total_quantity - ($stockOut ? $stockOut->total_quantity : 0);
    //     }
    
    //     $ledgerData = DB::table('product_ledgers')
    //         ->whereIn('product_id', $branchProducts)
    //         ->where('type', 'stockin')
    //         ->when($productId && $productId != 'all', function ($q) use ($productId) {
    //             return $q->where('product_id', $productId);
    //         })
    //         ->when($startDate, function ($q) use ($startDate) {
    //             return $q->whereDate('entry_date', '>=', $startDate);
    //         })
    //         ->when($endDate, function ($q) use ($endDate) {
    //             return $q->whereDate('entry_date', '<=', $endDate);
    //         })
    //         ->select('product_id', 'price', DB::raw('SUM(quantity) as total_quantity'))
    //         ->groupBy('product_id', 'price')
    //         ->get()
    //         ->keyBy(function ($item) {
    //             return $item->product_id . '-' . $item->price;
    //         });
    
    //     $salesData = DB::table('product_ledgers')
    //         ->whereIn('product_id', $branchProducts)
    //         ->where('type', 'stockout')
    //         ->when($productId && $productId != 'all', function ($q) use ($productId) {
    //             return $q->where('product_id', $productId);
    //         })
    //         ->when($startDate, function ($q) use ($startDate) {
    //             return $q->whereDate('entry_date', '>=', $startDate);
    //         })
    //         ->when($endDate, function ($q) use ($endDate) {
    //             return $q->whereDate('entry_date', '<=', $endDate);
    //         })
    //         ->select('product_id', 'price', DB::raw('SUM(quantity) as total_quantity'))
    //         ->groupBy('product_id', 'price')
    //         ->get()
    //         ->keyBy(function ($item) {
    //             return $item->product_id . '-' . $item->price;
    //         });
    
    //         $groupedProducts = [];
    //         foreach ($branchProductDetails as $product) {
    //             $categoryName = $productDetails[$product->product_id]->category->name ?? 'Uncategorized';
    //             $groupedProducts[$categoryName][] = $product;
    //         }

    //     //old upto this done with return view
    //     // return view('reportNew.store-product_ledger', compact('products', 'categories', 'ledgerData', 'productDetails', 'branchProducts', 'branchProductDetails', 'finalQuantities', 'salesData', 'groupedProducts'));

        

    //     //new with return for table product name sequsence this are extra
    //     // After grouping products by category, sort each category's products by product_id
    //     foreach ($groupedProducts as $categoryName => &$products) {
    //         usort($products, function ($a, $b) {
    //             return $a->product_id - $b->product_id;
    //         });
    //     }

    //     return view('reportNew.store-product_ledger', compact('products', 'categories', 'ledgerData', 'productDetails', 'branchProducts', 'branchProductDetails', 'finalQuantities', 'salesData', 'groupedProducts'));



    // }




    //all done with start and end date wise 
    // public function storeproductLedger(Request $request)
    // {
    //     $allProducts = Product::orderBy('name', 'asc')->get();
        
    //     $categories = ProductCategory::orderBy('name', 'asc')->get();
    
    //     $productId = $request->input('product_id');
    //     $startDate = $request->input('start_date');
    //     $endDate = $request->input('end_date');
    
    //     $branchId = Branch::where('type', 'Warehouse')->pluck('id');
        
    //     $branchProducts = Branch_Product::whereIn('branch_id', $branchId)
    //                             ->when($productId && $productId != 'all', function ($q) use ($productId) {
    //                                 return $q->where('product_id', $productId);
    //                             })
    //                             ->pluck('product_id')
    //                             ->toArray();
    
    //     $branchProductDetails = Branch_Product::whereIn('product_id', $branchProducts)
    //         ->whereIn('branch_id', $branchId)
    //         ->select('product_id', 'price')
    //         ->get();
    
    //     $productDetails = Product::with('category')
    //         ->whereIn('id', $branchProducts)
    //         ->select('id', 'name', 'product_category_id') 
    //         ->get()
    //         ->keyBy('id');
    
    //     $previousStockInQuantities = DB::table('product_ledgers')
    //         ->whereIn('product_id', $branchProducts)
    //         ->where('type', 'stockin')
    //         ->when($startDate, function ($q) use ($startDate) {
    //             return $q->whereDate('entry_date', '<', $startDate); 
    //         })
    //         ->select('product_id', 'price', DB::raw('SUM(quantity) as total_quantity'))
    //         ->groupBy('product_id', 'price')
    //         ->get()
    //         ->keyBy(function ($item) {
    //             return $item->product_id . '-' . $item->price; 
    //         });
    
    //     $previousStockOutQuantities = DB::table('product_ledgers')
    //         ->whereIn('product_id', $branchProducts)
    //         ->where('type', 'stockout')
    //         ->when($startDate, function ($q) use ($startDate) {
    //             return $q->whereDate('entry_date', '<', $startDate); 
    //         })
    //         ->select('product_id', 'price', DB::raw('SUM(quantity) as total_quantity'))
    //         ->groupBy('product_id', 'price')
    //         ->get()
    //         ->keyBy(function ($item) {
    //             return $item->product_id . '-' . $item->price; 
    //         });
    
    //     $finalQuantities = [];
    //     foreach ($previousStockInQuantities as $key => $stockIn) {
    //         $stockOut = $previousStockOutQuantities[$key] ?? null;
    //         $finalQuantities[$key] = $stockIn->total_quantity - ($stockOut ? $stockOut->total_quantity : 0);
    //     }
    
    //     $ledgerData = DB::table('product_ledgers')
    //         ->whereIn('product_id', $branchProducts)
    //         ->where('type', 'stockin')
    //         ->when($productId && $productId != 'all', function ($q) use ($productId) {
    //             return $q->where('product_id', $productId);
    //         })
    //         ->when($startDate, function ($q) use ($startDate) {
    //             return $q->whereDate('entry_date', '>=', $startDate);
    //         })
    //         ->when($endDate, function ($q) use ($endDate) {
    //             return $q->whereDate('entry_date', '<=', $endDate);
    //         })
    //         ->select('product_id', 'price', DB::raw('SUM(quantity) as total_quantity'))
    //         ->groupBy('product_id', 'price')
    //         ->get()
    //         ->keyBy(function ($item) {
    //             return $item->product_id . '-' . $item->price;
    //         });
    
    //     $salesData = DB::table('product_ledgers')
    //         ->whereIn('product_id', $branchProducts)
    //         ->where('type', 'stockout')
    //         ->when($productId && $productId != 'all', function ($q) use ($productId) {
    //             return $q->where('product_id', $productId);
    //         })
    //         ->when($startDate, function ($q) use ($startDate) {
    //             return $q->whereDate('entry_date', '>=', $startDate);
    //         })
    //         ->when($endDate, function ($q) use ($endDate) {
    //             return $q->whereDate('entry_date', '<=', $endDate);
    //         })
    //         ->select('product_id', 'price', DB::raw('SUM(quantity) as total_quantity'))
    //         ->groupBy('product_id', 'price')
    //         ->get()
    //         ->keyBy(function ($item) {
    //             return $item->product_id . '-' . $item->price;
    //         });
    
    //     $groupedProducts = [];
    //     foreach ($branchProductDetails as $product) {
    //         $categoryName = $productDetails[$product->product_id]->category->name ?? 'Uncategorized';
    //         $groupedProducts[$categoryName][] = $product;
    //     }
    
    //     foreach ($groupedProducts as $categoryName => &$products) {
    //         usort($products, function ($a, $b) {
    //             return $a->product_id - $b->product_id;
    //         });
    //     }
    
    //     return view('reportNew.store-product_ledger', compact('allProducts', 'categories', 'ledgerData', 'productDetails', 'branchProducts', 'branchProductDetails', 'finalQuantities', 'salesData', 'groupedProducts', 'products'));
    // }




    //all done with month year wise 
    public function storeproductLedger(Request $request)
    {
        $allProducts = Product::orderBy('name', 'asc')->get();
        $categories = ProductCategory::orderBy('name', 'asc')->get();
    
        $productId = $request->input('product_id');
        $monthYear = $request->input('month_year');
    
        // Convert month_year to start_date and end_date
        if ($monthYear) {
            $startDate = date('Y-m-01', strtotime($monthYear));
            $endDate = date('Y-m-t', strtotime($monthYear)); // 't' gives the last day of the month
        } else {
            $startDate = null;
            $endDate = null;
        }
    
        $branchId = Branch::where('type', 'Warehouse')->pluck('id');
    
        $branchProducts = Branch_Product::whereIn('branch_id', $branchId)
                                ->when($productId && $productId != 'all', function ($q) use ($productId) {
                                    return $q->where('product_id', $productId);
                                })
                                ->pluck('product_id')
                                ->toArray();
    
        $branchProductDetails = Branch_Product::whereIn('product_id', $branchProducts)
            ->whereIn('branch_id', $branchId)
            ->select('product_id', 'price')
            ->get();
    
        $productDetails = Product::with('category')
            ->whereIn('id', $branchProducts)
            ->select('id', 'name', 'product_category_id') 
            ->get()
            ->keyBy('id');
    
        $previousStockInQuantities = DB::table('product_ledgers')
            ->whereIn('product_id', $branchProducts)
            ->where('type', 'stockin')
            ->when($startDate, function ($q) use ($startDate) {
                return $q->whereDate('entry_date', '<', $startDate); 
            })
            ->select('product_id', 'price', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_id', 'price')
            ->get()
            ->keyBy(function ($item) {
                return $item->product_id . '-' . $item->price; 
            });
    
        $previousStockOutQuantities = DB::table('product_ledgers')
            ->whereIn('product_id', $branchProducts)
            ->where('type', 'stockout')
            ->when($startDate, function ($q) use ($startDate) {
                return $q->whereDate('entry_date', '<', $startDate); 
            })
            ->select('product_id', 'price', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_id', 'price')
            ->get()
            ->keyBy(function ($item) {
                return $item->product_id . '-' . $item->price; 
            });
    
        $finalQuantities = [];
        foreach ($previousStockInQuantities as $key => $stockIn) {
            $stockOut = $previousStockOutQuantities[$key] ?? null;
            $finalQuantities[$key] = $stockIn->total_quantity - ($stockOut ? $stockOut->total_quantity : 0);
        }
    
        $ledgerData = DB::table('product_ledgers')
            ->whereIn('product_id', $branchProducts)
            ->where('type', 'stockin')
            ->when($productId && $productId != 'all', function ($q) use ($productId) {
                return $q->where('product_id', $productId);
            })
            ->when($startDate, function ($q) use ($startDate) {
                return $q->whereDate('entry_date', '>=', $startDate);
            })
            ->when($endDate, function ($q) use ($endDate) {
                return $q->whereDate('entry_date', '<=', $endDate);
            })
            ->select('product_id', 'price', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_id', 'price')
            ->get()
            ->keyBy(function ($item) {
                return $item->product_id . '-' . $item->price;
            });
    
        $salesData = DB::table('product_ledgers')
            ->whereIn('product_id', $branchProducts)
            ->where('type', 'stockout')
            ->when($productId && $productId != 'all', function ($q) use ($productId) {
                return $q->where('product_id', $productId);
            })
            ->when($startDate, function ($q) use ($startDate) {
                return $q->whereDate('entry_date', '>=', $startDate);
            })
            ->when($endDate, function ($q) use ($endDate) {
                return $q->whereDate('entry_date', '<=', $endDate);
            })
            ->select('product_id', 'price', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_id', 'price')
            ->get()
            ->keyBy(function ($item) {
                return $item->product_id . '-' . $item->price;
            });
    
        $groupedProducts = [];
        foreach ($branchProductDetails as $product) {
            $categoryName = $productDetails[$product->product_id]->category->name ?? 'Uncategorized';
            $groupedProducts[$categoryName][] = $product;
        }
    
        foreach ($groupedProducts as $categoryName => &$products) {
            usort($products, function ($a, $b) {
                return $a->product_id - $b->product_id;
            });
        }
    
        return view('reportNew.store-product_ledger', compact('allProducts', 'categories', 'ledgerData', 'productDetails', 'branchProducts', 'branchProductDetails', 'finalQuantities', 'salesData', 'groupedProducts'));
    }






    //old without dom pdf download pass value 
    // public function BranchHeadofcproductLedger(Request $request)
    // {
    //     $user = Auth::user();

    //     if ($user->role_name === 'Admin') {
    //         $branches = Branch::whereIn('type', ['Branch', 'Headoffice'])->pluck('name', 'id');
    //     } else {
    //         $branches = Branch::where('id', $user->branch_id)->pluck('name', 'id');
    //     }

    //     $selectedBranch = $request->input('branch_id', $user->branch_id);
    //     $isAdmin = $user->role_name === 'Admin';

    //     $allProducts = Product::orderBy('name', 'asc')->get();
    //     $categories = ProductCategory::orderBy('name', 'asc')->get();

    //     $productId = $request->input('product_id');
    //     $monthYear = $request->input('month_year');

    //     if ($monthYear) {
    //         $startDate = date('Y-m-01', strtotime($monthYear));
    //         $endDate = date('Y-m-t', strtotime($monthYear)); 
    //     } else {
    //         $startDate = null;
    //         $endDate = null;
    //     }

    //     $branchProducts = Branch_Product::where('branch_id', $selectedBranch)
    //                             ->when($productId && $productId != 'all', function ($q) use ($productId) {
    //                                 return $q->where('product_id', $productId);
    //                             })
    //                             ->pluck('product_id')
    //                             ->toArray(); 


    //     $branchProductDetails = Branch_Product::where('branch_id', $selectedBranch)
    //         ->whereIn('product_id', $branchProducts)
    //         ->select('product_id', 'details_stockin')
    //         ->get()
    //         ->flatMap(function ($item) {
    //             $details = [];
    //             if (!empty($item->details_stockin)) {
    //                 $decoded = json_decode($item->details_stockin, true);
    //                 $details = is_array($decoded) ? $decoded : [];
    //             }
                
    //             $priceGroups = [];

    //             foreach ($details as $detail) {
    //                 if (isset($detail['prc']) && isset($detail['qty'])) {
    //                     $price = $detail['prc'];
    //                     $qty = is_numeric($detail['qty']) ? (float)$detail['qty'] : 0;
                        
    //                     if (!isset($priceGroups[$price])) {
    //                         $priceGroups[$price] = 0;
    //                     }
    //                     $priceGroups[$price] += $qty;
    //                 }
    //             }
                
    //             $entries = [];
    //             foreach ($priceGroups as $price => $totalQty) {
    //                 $entries[] = (object) [
    //                     'product_id' => $item->product_id,
    //                     'price' => $price,
    //                     'quantity' => $totalQty
    //                 ];
    //             }
                
    //             return $entries;
    //         });

    //     $branchProductDetails = $branchProductDetails ?: [];

    //     $productDetails = Product::with('category')
    //         ->whereIn('id', $branchProducts)
    //         ->select('id', 'name', 'product_category_id') 
    //         ->get()
    //         ->keyBy('id');

    //     $previousStockInQuantities = DB::table('product_ledger_b_h_s')
    //         ->where('branch_id', $selectedBranch)
    //         ->whereIn('product_id', $branchProducts)
    //         ->where('type', 'StockIn')
    //         ->when($startDate, function ($q) use ($startDate) {
    //             return $q->whereDate('entry_date', '<', $startDate); 
    //         })
    //         ->select('product_id', 'price', DB::raw('SUM(quantity) as total_quantity'))
    //         ->groupBy('product_id', 'price')
    //         ->get()
    //         ->keyBy(function ($item) {
    //             return $item->product_id . '-' . $item->price; 
    //         });

    //     $previousStockOutQuantities = DB::table('product_ledger_b_h_s')
    //         ->where('branch_id', $selectedBranch)
    //         ->whereIn('product_id', $branchProducts)
    //         ->where('type', 'StockOut')
    //         ->when($startDate, function ($q) use ($startDate) {
    //             return $q->whereDate('entry_date', '<', $startDate); 
    //         })
    //         ->select('product_id', 'price', DB::raw('SUM(quantity) as total_quantity'))
    //         ->groupBy('product_id', 'price')
    //         ->get()
    //         ->keyBy(function ($item) {
    //             return $item->product_id . '-' . $item->price; 
    //         });

    //     $finalQuantities = [];
    //     foreach ($previousStockInQuantities as $key => $stockIn) {
    //         $stockOut = $previousStockOutQuantities[$key] ?? null;
    //         $finalQuantities[$key] = $stockIn->total_quantity - ($stockOut ? $stockOut->total_quantity : 0);
    //     }

    //     $ledgerData = DB::table('product_ledger_b_h_s')
    //         ->where('branch_id', $selectedBranch)
    //         ->whereIn('product_id', $branchProducts)
    //         ->where('type', 'StockIn')
    //         ->when($productId && $productId != 'all', function ($q) use ($productId) {
    //             return $q->where('product_id', $productId);
    //         })
    //         ->when($startDate, function ($q) use ($startDate) {
    //             return $q->whereDate('entry_date', '>=', $startDate);
    //         })
    //         ->when($endDate, function ($q) use ($endDate) {
    //             return $q->whereDate('entry_date', '<=', $endDate);
    //         })
    //         ->select('product_id', 'price', DB::raw('SUM(quantity) as total_quantity'))
    //         ->groupBy('product_id', 'price')
    //         ->get()
    //         ->keyBy(function ($item) {
    //             return $item->product_id . '-' . $item->price;
    //         });

    //     $salesData = DB::table('product_ledger_b_h_s')
    //         ->where('branch_id', $selectedBranch)
    //         ->whereIn('product_id', $branchProducts)
    //         ->where('type', 'StockOut')
    //         ->when($productId && $productId != 'all', function ($q) use ($productId) {
    //             return $q->where('product_id', $productId);
    //         })
    //         ->when($startDate, function ($q) use ($startDate) {
    //             return $q->whereDate('entry_date', '>=', $startDate);
    //         })
    //         ->when($endDate, function ($q) use ($endDate) {
    //             return $q->whereDate('entry_date', '<=', $endDate);
    //         })
    //         ->select('product_id', 'price', DB::raw('SUM(quantity) as total_quantity'))
    //         ->groupBy('product_id', 'price')
    //         ->get()
    //         ->keyBy(function ($item) {
    //             return $item->product_id . '-' . $item->price;
    //         });

    //     $groupedProducts = [];
    //     foreach ($branchProductDetails as $product) {
    //         $categoryName = $productDetails[$product->product_id]->category->name ?? 'Uncategorized';
    //         $groupedProducts[$categoryName][] = $product;
    //     }

    //     foreach ($groupedProducts as $categoryName => &$products) {
    //         usort($products, function ($a, $b) {
    //             return $a->product_id - $b->product_id;
    //         });
    //     }

    //     return view('reportNew.brnch_hdoffc-product_ledger', compact(
    //         'allProducts', 
    //         'categories', 
    //         'ledgerData', 
    //         'productDetails', 
    //         'branchProducts', 
    //         'branchProductDetails', 
    //         'finalQuantities', 
    //         'salesData', 
    //         'groupedProducts', 
    //         'branches', 
    //         'selectedBranch', 
    //         'isAdmin'
    //     ));
    // }




    public function BranchHeadofcproductLedger(Request $request)
    {
        $user = Auth::user();

        if ($user->role_name === 'Admin') {
            $branches = Branch::whereIn('type', ['Branch', 'Headoffice'])->pluck('name', 'id');
        } else {
            $branches = Branch::where('id', $user->branch_id)->pluck('name', 'id');
        }

        $selectedBranch = $request->input('branch_id', $user->branch_id);
        $isAdmin = $user->role_name === 'Admin';

        $allProducts = Product::orderBy('name', 'asc')->get();
        $categories = ProductCategory::orderBy('name', 'asc')->get();

        $productId = $request->input('product_id');
        $monthYear = $request->input('month_year');

        if ($monthYear) {
            $startDate = date('Y-m-01', strtotime($monthYear));
            $endDate = date('Y-m-t', strtotime($monthYear)); 
        } else {
            $startDate = null;
            $endDate = null;
        }

        $branchProducts = Branch_Product::where('branch_id', $selectedBranch)
                                ->when($productId && $productId != 'all', function ($q) use ($productId) {
                                    return $q->where('product_id', $productId);
                                })
                                ->pluck('product_id')
                                ->toArray(); 


        $branchProductDetails = Branch_Product::where('branch_id', $selectedBranch)
            ->whereIn('product_id', $branchProducts)
            ->select('product_id', 'details_stockin')
            ->get()
            ->flatMap(function ($item) {
                $details = [];
                if (!empty($item->details_stockin)) {
                    $decoded = json_decode($item->details_stockin, true);
                    $details = is_array($decoded) ? $decoded : [];
                }
                
                $priceGroups = [];

                foreach ($details as $detail) {
                    if (isset($detail['prc']) && isset($detail['qty'])) {
                        $price = $detail['prc'];
                        $qty = is_numeric($detail['qty']) ? (float)$detail['qty'] : 0;
                        
                        if (!isset($priceGroups[$price])) {
                            $priceGroups[$price] = 0;
                        }
                        $priceGroups[$price] += $qty;
                    }
                }
                
                $entries = [];
                foreach ($priceGroups as $price => $totalQty) {
                    $entries[] = (object) [
                        'product_id' => $item->product_id,
                        'price' => $price,
                        'quantity' => $totalQty
                    ];
                }
                
                return $entries;
            });

        $branchProductDetails = $branchProductDetails ?: [];

        $productDetails = Product::with('category')
            ->whereIn('id', $branchProducts)
            ->select('id', 'name', 'product_category_id') 
            ->get()
            ->keyBy('id');

        $previousStockInQuantities = DB::table('product_ledger_b_h_s')
            ->where('branch_id', $selectedBranch)
            ->whereIn('product_id', $branchProducts)
            ->where('type', 'StockIn')
            ->when($startDate, function ($q) use ($startDate) {
                return $q->whereDate('entry_date', '<', $startDate); 
            })
            ->select('product_id', 'price', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_id', 'price')
            ->get()
            ->keyBy(function ($item) {
                return $item->product_id . '-' . $item->price; 
            });

        $previousStockOutQuantities = DB::table('product_ledger_b_h_s')
            ->where('branch_id', $selectedBranch)
            ->whereIn('product_id', $branchProducts)
            ->where('type', 'StockOut')
            ->when($startDate, function ($q) use ($startDate) {
                return $q->whereDate('entry_date', '<', $startDate); 
            })
            ->select('product_id', 'price', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_id', 'price')
            ->get()
            ->keyBy(function ($item) {
                return $item->product_id . '-' . $item->price; 
            });

        $finalQuantities = [];
        foreach ($previousStockInQuantities as $key => $stockIn) {
            $stockOut = $previousStockOutQuantities[$key] ?? null;
            $finalQuantities[$key] = $stockIn->total_quantity - ($stockOut ? $stockOut->total_quantity : 0);
        }

        $ledgerData = DB::table('product_ledger_b_h_s')
            ->where('branch_id', $selectedBranch)
            ->whereIn('product_id', $branchProducts)
            ->where('type', 'StockIn')
            ->when($productId && $productId != 'all', function ($q) use ($productId) {
                return $q->where('product_id', $productId);
            })
            ->when($startDate, function ($q) use ($startDate) {
                return $q->whereDate('entry_date', '>=', $startDate);
            })
            ->when($endDate, function ($q) use ($endDate) {
                return $q->whereDate('entry_date', '<=', $endDate);
            })
            ->select('product_id', 'price', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_id', 'price')
            ->get()
            ->keyBy(function ($item) {
                return $item->product_id . '-' . $item->price;
            });

        $salesData = DB::table('product_ledger_b_h_s')
            ->where('branch_id', $selectedBranch)
            ->whereIn('product_id', $branchProducts)
            ->where('type', 'StockOut')
            ->when($productId && $productId != 'all', function ($q) use ($productId) {
                return $q->where('product_id', $productId);
            })
            ->when($startDate, function ($q) use ($startDate) {
                return $q->whereDate('entry_date', '>=', $startDate);
            })
            ->when($endDate, function ($q) use ($endDate) {
                return $q->whereDate('entry_date', '<=', $endDate);
            })
            ->select('product_id', 'price', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_id', 'price')
            ->get()
            ->keyBy(function ($item) {
                return $item->product_id . '-' . $item->price;
            });

        $groupedProducts = [];
        foreach ($branchProductDetails as $product) {
            $categoryName = $productDetails[$product->product_id]->category->name ?? 'Uncategorized';
            $groupedProducts[$categoryName][] = $product;
        }

        foreach ($groupedProducts as $categoryName => &$products) {
            usort($products, function ($a, $b) {
                return $a->product_id - $b->product_id;
            });
        }

    
        // ✅ PDF Download Logic
        if ($request->has('pdf') && $request->pdf == 'true') {
            $pdf = PDF::loadView('reportNew.brnch_hdoffc-product_ledger_pdf', compact(
                'allProducts', 
                'categories', 
                'ledgerData', 
                'productDetails', 
                'branchProducts', 
                'branchProductDetails', 
                'finalQuantities', 
                'salesData', 
                'groupedProducts', 
                'branches', 
                'selectedBranch', 
                'isAdmin'
            ))->setPaper('A4', 'landscape');

            return $pdf->download('Ledger-Report-' . now()->format('Y-m-d') . '.pdf');
        }

        return view('reportNew.brnch_hdoffc-product_ledger', compact(
            'allProducts', 
            'categories', 
            'ledgerData', 
            'productDetails', 
            'branchProducts', 
            'branchProductDetails', 
            'finalQuantities', 
            'salesData', 
            'groupedProducts', 
            'branches', 
            'selectedBranch', 
            'isAdmin'
        ));



    }









    public function checkExpenseStock(Request $request)
    {
        $branchId = $request->branch_id;
        $productId = $request->product_id;
        $requestedQuantity = $request->amount_expenditure;
        

        $pendingExpenses = ProductReturn::where('branch_id', $branchId)
                                ->where('product_id', $productId)
                                ->where('status', 0)
                                ->get();


        $allPendingExpense = $pendingExpenses->sum('return_quantity'); 

        $branchProduct = Branch_Product::where('branch_id', $branchId)
                                    ->where('product_id', $productId)
                                    ->first();


        $restOfStock = $branchProduct ? $branchProduct->stock : 0;

        $checkAllStock = $restOfStock - $allPendingExpense;



        if ($requestedQuantity > $checkAllStock) {
            return response()->json([
                'success' => false,
                'message' => "Quantity is greater than the available stock. Please check input amount of expenditure or  the pending  'damage/return' product list."
            ]);
        }
    
        return response()->json([
            'success' => true
        ]);

    
    }



    public function fetchProductData(Request $request)
    {
        $branchId = $request->branch_id;
        $productId = $request->product_id;
    
        $allPendingReturnValue = ProductReturn::where('branch_id', $branchId)
            ->where('product_id', $productId)
            ->where('status', 0)
            ->sum('return_quantity');

        $restOfStock = Branch_Product::where('branch_id', $branchId)
            ->where('product_id', $productId)
            ->value('stock');
    
        return response()->json([
            'pendingReturnQuantity' => $allPendingReturnValue,
            'stock' => $restOfStock
        ]);
    }




}
