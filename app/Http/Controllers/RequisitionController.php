<?php

namespace App\Http\Controllers;

use App\Helpers;
use Carbon\Carbon;
use App\Models\Branch;
use App\Models\Product;
use App\Models\Project;
use App\Models\Approval;
use App\Models\JournalItem;
use App\Models\Requisition;
use App\Models\JournalEntry;
use Illuminate\Http\Request;
use App\Models\ProductLedger;
use App\Models\ApprovalStatus;
use App\Models\Branch_Product;
use App\Models\ChartOfAccount;
use App\Models\ProductLedgerBH;
use App\Models\RequisitionItem;
use App\Models\ProductAccountMap;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Models\BranchHeadofficeLog;
use App\Helpers\NumberToWordsHelper;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator; 



class RequisitionController extends Controller
{
    public function index()
    {

        $requisitions = Requisition::all();
        $requisitionss = RequisitionItem::all();
        return view('requisition.index', compact('requisitions', 'requisitionss'));
    }


    // public function createreq()
    // {
    //     $products = Product::all();
    //     $branches = Branch::pluck('name', 'id'); 
    //     return view('requisition.form', compact('products', 'branches'));
    // }




    // public function createreq()
    // {
    //     $user = auth()->user();
    //     $userBranch = $user->branch->type; 
        
    //     if ($userBranch == 'Branch') {
    //         $branches = Branch::where('id', $user->branch_id)->pluck('name', 'id');
    //     }

    //     elseif ($userBranch == 'Warehouse') {
    //         $branches = Branch::where('id', $user->branch_id)->pluck('name', 'id');
    //     }
    //     else {
    //         $branches = Branch::where('id', $user->branch_id)->pluck('name', 'id');
    //     }
    //     $products = Product::all();

    //     return view('requisition.form', compact('products', 'branches', 'userBranch'));
    // }
    
    

    public function createreq()
    {
        $user = auth()->user();
        $userBranch = $user->branch->type; 
        
        if ($userBranch == 'Branch') {
            $branches = Branch::where('id', $user->branch_id)->pluck('name', 'id');
        } elseif ($userBranch == 'Warehouse') {
            $branches = Branch::where('id', $user->branch_id)->pluck('name', 'id');
        } else {
            $branches = Branch::where('id', $user->branch_id)->pluck('name', 'id');
        }
    
        $products = Product::all();
        $projects = Project::all();
    
        return view('requisition.form', compact('products', 'branches', 'userBranch', 'projects'));
    }
    










    // public function createreq()
    // {
    //     $user = auth()->user();
    //     $userBranch = $user->branch->type;
    
    //     if ($user->role_name == 'Admin') { 
    //         $branches = Branch::all()->pluck('name', 'id');
    //     } elseif ($userBranch == 'Branch') {
    //         $branches = Branch::where('id', $user->branch_id)->pluck('name', 'id');
    //     } elseif ($userBranch == 'Warehouse') {
    //         $branches = Branch::where('id', $user->branch_id)->pluck('name', 'id');
    //     } else {
    //         $branches = Branch::where('id', $user->branch_id)->pluck('name', 'id');
    //     }
    
    //     $products = Product::all();
    
    //     return view('requisition.form', compact('products', 'branches', 'userBranch'));
    // }









    // public function list(Request $request)
    // {
    //     $user_id = Auth::id();
    //     $isAdmin = 0;
    
    //     $roleId = DB::table('model_has_roles')->where('model_id', '=', $user_id)->first()->role_id;
    //     $isApproval = Approval::where('module', '=', 'requisition')->where('role_id', '=', $roleId)->get();
    
    //     if ($isApproval->isEmpty()) {
    //         $requisitions = Requisition::where('user_id', $user_id)->get();
    //     } else {
    //         $isAdmin = 1;
    //         $order = $isApproval[0]->order;
    //         $minOrder = Approval::where('module', '=', 'requisition')->min('order');
    
    //         if ($order == $minOrder) {
    //             $requisitions = Requisition::all();
    //         } else {
    //             $requisitions = [];
    //             $prevRole = Approval::where('module', '=', 'requisition')
    //                 ->where('order', '<', $order)
    //                 ->orderBy('order', 'desc')
    //                 ->first()->role_id;
    
    //             $allRequisitions = Requisition::all();
    
    //             foreach ($allRequisitions as $requisition) {
    //                 if ($requisition->user_id == auth()->user()->id) {
    //                     $requisitions[] = $requisition;
    //                 } else {
    //                     $approvalArray = ApprovalStatus::where('module', '=', 'requisition')
    //                         ->where('module_id', '=', $requisition->id)
    //                         ->where('role_id', '=', $prevRole)
    //                         ->where('status', '!=', 0) 
    //                         ->get();
    
    //                     if (!$approvalArray->isEmpty()) {
    //                         $requisitions[] = $requisition;
    //                     }
    //                 }
    //             }
    //         }
    //     }
    
    //     $minOrderRole = Approval::where('module', '=', 'requisition')
    //         ->where('order', Approval::where('module', '=', 'requisition')->min('order'))
    //         ->first()->role_id;

    //         $authUserBranchId = DB::table('users')->where('id', $user_id)->first()->branch_id;
    //         $authUserBranch = Branch::where('id', $authUserBranchId)->first();
    
    //     foreach ($requisitions as $requisition) {
    //         $approveStatus = ApprovalStatus::where('module', '=', 'requisition')
    //             ->where('module_id', '=', $requisition->id)
    //             ->where('role_id', '=', $minOrderRole)->get();
    
    //         $requisition->isApprove = $approveStatus->isEmpty() ? 0 : 1;
    
    //         if (!$isApproval->isEmpty()) {
    //             $statusChecked = ApprovalStatus::where('module', '=', 'requisition')
    //                 ->where('module_id', '=', $requisition->id)
    //                 ->where('role_id', '=', $roleId)
    //                 ->first();
    
    //             if (isset($statusChecked)) {
    //                 $requisition->statusChecked = 1;
    //                 $requisition->status = $statusChecked->status;
    //             } else {
    //                 $requisition->statusChecked = 0;
    //             }
    //         }

    //         // $purchaseStatus = RequisitionItem::where('requisition_id', $requisition->id)->pluck('purchase_status');
    //         // $requisition->purchase_status = $purchaseStatus->contains(1) ? 1 : 0;


    //     // $branch = Branch::where('id', $requisition->branch_id)->first();
    //     // $requisition->isHeadoffice = ($branch && $branch->type === 'Headoffice');

    //     }
    
    //     return view('requisition.list', compact('requisitions', 'isAdmin', 'user_id', 'roleId', 'authUserBranch'));

    // }
    



    // public function list(Request $request)
    // {
    //     $user_id = Auth::id();
    //     $authUserBranchId = DB::table('users')->where('id', $user_id)->first()->branch_id;
    //     $authUserBranch = Branch::where('id', $authUserBranchId)->first();

    //     // if ($authUserBranch->type === 'Headoffice' || $authUserBranch->type === 'Warehouse') {
    //     //     $requisitions = Requisition::all();
    //     // } else {
    //     //     $requisitions = Requisition::where('user_id', $user_id)->get();
    //     // }

    //     $requisitions = Requisition::where('user_id', $user_id)->orderBy('created_at', 'desc')->get();
        
    //     return view('requisition.list', compact('requisitions', 'user_id', 'authUserBranch'));
    // }
    




    public function list(Request $request)
    {
        $user_id = Auth::id();
        $authUserBranchId = DB::table('users')->where('id', $user_id)->first()->branch_id;
        $authUserBranch = Branch::where('id', $authUserBranchId)->first();
    
        $authUserRole = Auth::user()->role_name;
    
        if ($authUserRole === 'Admin') {
            $requisitions = Requisition::orderBy('created_at', 'desc')->get();
        } else {
            $requisitions = Requisition::where('user_id', $user_id)
                                        ->orderBy('created_at', 'desc')
                                        ->get();
        }
        return view('requisition.list', compact('requisitions', 'user_id', 'authUserBranch'));
    }
    


       // all is okay just admin panel this order list button can't show up to this 
    // public function orderlist(Request $request)
    // {
    //     $user_id = Auth::id();
    //     $authUserBranchId = DB::table('users')->where('id', $user_id)->first()->branch_id;
    //     $authUserBranch = Branch::where('id', $authUserBranchId)->first();
    
    //     if ($authUserBranch->type === 'Headoffice') {
    //         $warehouseBranchIds = Branch::where('type', 'Warehouse')->pluck('id')->toArray();
    //         $requisitions = Requisition::whereNotIn('branch_id', $warehouseBranchIds)
    //             ->orderBy('created_at', 'desc')
    //             ->get();
    
    //     } elseif ($authUserBranch->type === 'Warehouse') {
    //         $requisitions = Requisition::where('user_id', '!=', $user_id)
    //             ->orderBy('created_at', 'desc')
    //             ->get();
    //     } else {
    //         $requisitions = Requisition::where('user_id', $user_id)
    //             ->orderBy('created_at', 'desc')
    //             ->get();
    //     }
    
    //     return view('requisition.order.list', compact('requisitions', 'user_id', 'authUserBranch'));
    // }
    



    //all okay just partial delivery so status change and need button chanhe some
    // public function orderlist(Request $request)
    // {
    //     $user_id = Auth::id();
    //     $authUserBranchId = DB::table('users')->where('id', $user_id)->first()->branch_id;
    //     $authUserBranch = Branch::where('id', $authUserBranchId)->first();
    
    //     if ($authUserBranch->type === 'Headoffice') {
    //         $warehouseBranchIds = Branch::where('type', 'Warehouse')->pluck('id')->toArray();
    //         $requisitions = Requisition::whereNotIn('branch_id', $warehouseBranchIds)
    //             ->orderBy('created_at', 'desc')
    //             ->get();
    
    //     } elseif ($authUserBranch->type === 'Warehouse' || Auth::user()->role_name === 'Admin') {
    //         $requisitions = Requisition::where('user_id', '!=', $user_id)
    //             ->orderBy('created_at', 'desc')
    //             ->get();
    //     } else {
    //         $requisitions = Requisition::where('user_id', $user_id)
    //             ->orderBy('created_at', 'desc')
    //             ->get();
    //     }
    
    //     return view('requisition.order.list', compact('requisitions', 'user_id', 'authUserBranch'));
    // }
    





    public function orderlist(Request $request)
    {
        $user_id = Auth::id();
        $authUserBranchId = DB::table('users')->where('id', $user_id)->first()->branch_id;
        $authUserBranch = Branch::where('id', $authUserBranchId)->first();
    
        if ($authUserBranch->type === 'Headoffice') {
            $warehouseBranchIds = Branch::where('type', 'Warehouse')->pluck('id')->toArray();
            $requisitions = Requisition::whereNotIn('branch_id', $warehouseBranchIds)
                ->orderBy('created_at', 'desc')
                ->get();
    
        } elseif ($authUserBranch->type === 'Warehouse' || Auth::user()->role_name === 'Admin') {
            $requisitions = Requisition::where('user_id', '!=', $user_id)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $requisitions = Requisition::where('user_id', $user_id)
                ->orderBy('created_at', 'desc')
                ->get();
        }
    
        $paymentMethods = ChartOfAccount::whereIn('id', [54, 55, 58])->get();
        
        return view('requisition.order.list', compact('requisitions', 'user_id', 'authUserBranch', 'paymentMethods'));
    }









    public function orderlistEdit($id)
    {
        $requisitionheading = Requisition::findOrFail($id);
        $requisitionlist = RequisitionItem::where('requisition_id', $id)->get();
        $products = Product::all();
        $projects = Project::all();

        $user = auth()->user();
    
        $userBranch = $user->branch->type; 
        
        if ($userBranch == 'Branch') {
            $branches = Branch::where('id', $user->branch_id)->pluck('name', 'id');
        }
   
        elseif ($userBranch == 'Headoffice' || $userBranch == 'Warehouse') {
            $branches = Branch::pluck('name', 'id');
        } else {
            $branches = Branch::where('id', $user->branch_id)->pluck('name', 'id');
        }

        return view('requisition.order.edit', compact('requisitionheading', 'requisitionlist', 'products', 'projects', 'branches', 'userBranch', 'user'));
    }



    public function orderlistUpdate(Request $request, $id)
    {
        $requisitionheading = Requisition::find($id);

        $request->validate([
            'branch_id' => 'required',
            'project_id' => 'required',
            'date_from' => 'required|date_format:d/m/Y',
            'items.*.name' => 'required',
            'items.*.description' => 'required',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.amount' => 'required|integer|min:1',
        ]);


        $formattedDate = Carbon::createFromFormat('d/m/Y', $request->date_from)->format('d/m/Y');


        $requisitionheading->update([
            'branch_id' => $request->branch_id,
            'project_id' => $request->project_id,
            'date_from' => $formattedDate,
        ]);

        $existingItemIds = [];

        foreach ($request->items as $item) {

            $product = Product::find($item['name']);

            $totalPrice = $item['price'] * $item['amount'];

            if (isset($item['id'])) {
                $requisitionItem = RequisitionItem::find($item['id']);
                if (!$requisitionItem) {
                    continue;
                }
            } else {
                $requisitionItem = new RequisitionItem([
                    'requisition_id' => $requisitionheading->id,
                ]);
            }

            $requisitionItem->fill([
                'product_id' => $product->id,
                'product_description' => $item['description'],
                'single_product_name' => $product['name'],
                'price' => $item['price'],
                'demand_amount' => $item['amount'],
                'total_price' => $totalPrice,

                'comment' => $item['comment'],
            ]);

            $requisitionItem->save();
            $existingItemIds[] = $requisitionItem->id;
        }

        RequisitionItem::where('requisition_id', $requisitionheading->id)
            ->whereNotIn('id', $existingItemIds)
            ->delete();

        Toastr::success('Order Request Requisition updated successfully.', 'Success');
        return redirect()->route('order.list');
    }








    // public function orderlistView($id)
    // {

    //     $requisitionheading = Requisition::with('user')->find($id);
    //     $requisitionlist = RequisitionItem::where('requisition_id', $id)->get();

    //     $productNames = $requisitionlist->pluck('single_product_name');

    //     $productIds = Product::whereIn('id', $productNames)->pluck('name', 'id')->toArray();

    //     return view('requisition.order.view', compact('requisitionheading', 'requisitionlist', 'productIds'));
    // }



    public function orderlistView($id)
    {
        $requisitionheading = Requisition::with('user')->find($id);
        $requisitionlist = RequisitionItem::where('requisition_id', $id)->get();
    
        $productIds = $requisitionlist->pluck('product_id')->toArray();
    
        $warehouseBranchId = Branch::where('type', 'Warehouse')->value('id');
    
        $warehouseStock = Branch_Product::whereIn('product_id', $productIds)
            ->where('branch_id', $warehouseBranchId)
            ->pluck('stock', 'product_id')
            ->toArray();
    
        return view('requisition.order.view', compact('requisitionheading', 'requisitionlist', 'warehouseStock'));
    }
    





        // public function pDemandindex()
        // {
        //     $branchIds = Branch::where('type', '!=', 'Warehouse')->pluck('id');
        //     $requisitionIds = Requisition::whereIn('branch_id', $branchIds)->pluck('id');
            
        //     $requisitionItems = RequisitionItem::whereIn('requisition_id', $requisitionIds)
        //         ->where('delivery', 0)
        //         ->where('reject', 0)
        //         ->where('purchase', 0)
        //         ->get(['product_id', 'demand_amount']);

        //     return view('deamndproduct.index', compact('requisitionItems'));
        // }
    



        public function pDemandindex()
        {
            $branchIds = Branch::where('type', '!=', 'Warehouse')->pluck('id');

            $requisitionIds = Requisition::whereIn('branch_id', $branchIds)->pluck('id');

            $requisitionItems = RequisitionItem::whereIn('requisition_id', $requisitionIds)
                ->where('delivery', 0)
                ->where('reject', 0)
                ->where('purchase', 0)
                ->select('product_id', DB::raw('SUM(demand_amount) as total_demand'))
                ->groupBy('product_id')
                ->with('productnamedemand') 
                ->get();

            return view('deamndproduct.index', compact('requisitionItems'));
        }





    //warehouse reject list 

    public function rejectlist(Request $request)
    {
        $user_id = Auth::id();
        $authUserBranchId = DB::table('users')->where('id', $user_id)->first()->branch_id;
        $authUserBranch = Branch::where('id', $authUserBranchId)->first();
    
        if ($authUserBranch->type === 'Headoffice' || $authUserBranch->type === 'PurchaseTeam') {
            // $requisitions = Requisition::all();
            $requisitions = Requisition::where('purchase_reject', 1)->orderBy('created_at', 'desc')->get();


        } elseif ($authUserBranch->type === 'Warehouse') {
            $requisitions = Requisition::where('user_id', '!=', $user_id)->where('purchase_reject', 1)->orderBy('created_at', 'desc')->get();

        } else {
            $requisitions = Requisition::where('user_id', '!=', $user_id)->where('purchase_reject', 1)->orderBy('created_at', 'desc')->get();
        }
    
        return view('requisition.rejectbyteam.list', compact('requisitions', 'user_id', 'authUserBranch'));
    }


    public function rejectlistView($id)
    {

        $requisitionheading = Requisition::with('user')->find($id);

        // $requisitionlist = RequisitionItem::where('requisition_id', $id)->get();  

        $requisitionlist = RequisitionItem::where('requisition_id', $id)
            ->where('purchase_team_reject', 1)
            ->get();

        $productNames = $requisitionlist->pluck('single_product_name');

        $productIds = Product::whereIn('id', $productNames)->pluck('name', 'id')->toArray();

        return view('requisition.rejectbyteam.view', compact('requisitionheading', 'requisitionlist', 'productIds'));
    }


    //warehouse purchase list 

    // public function purchaselist(Request $request)
    // {
    //     $user_id = Auth::id();
    //     $authUserBranchId = DB::table('users')->where('id', $user_id)->first()->branch_id;
    //     $authUserBranch = Branch::where('id', $authUserBranchId)->first();
    
    //     if ($authUserBranch->type === 'Headoffice' || $authUserBranch->type === 'PurchaseTeam') {
    //         $requisitions = Requisition::where('purchase_approve', 1)->orderBy('created_at', 'desc')->get();

    //     } elseif ($authUserBranch->type === 'Warehouse') {
    //         $requisitions = Requisition::where('user_id', '!=', $user_id)->where('purchase_approve', 1)->orderBy('created_at', 'desc')->get();

    //     } else {
    //         $requisitions = Requisition::where('user_id', '!=', $user_id)->where('purchase_approve', 1)->orderBy('created_at', 'desc')->get();
    //     }
    
    //     return view('requisition.purchasebyteam.list', compact('requisitions', 'user_id', 'authUserBranch'));
    // }



    public function purchaselist(Request $request)
    {
        $user_id = Auth::id();
        $authUser = DB::table('users')->where('id', $user_id)->first(); 
        $authUserBranchId = $authUser->branch_id;
        $authUserBranch = Branch::where('id', $authUserBranchId)->first();
    
        // Access the user's role_name directly
        $roleName = $authUser->role_name ?? null;
    
        if ($authUserBranch->type === 'Headoffice' || $authUserBranch->type === 'PurchaseTeam' || $roleName === 'PurchaseTeam') {
            $requisitions = Requisition::where('purchase_approve', 1)->orderBy('created_at', 'desc')->get();
    
        } elseif ($authUserBranch->type === 'Warehouse') {
            $requisitions = Requisition::where('user_id', '!=', $user_id)->where('purchase_approve', 1)->orderBy('created_at', 'desc')->get();
    
        } else {
            $requisitions = Requisition::where('user_id', '!=', $user_id)->where('purchase_approve', 1)->orderBy('created_at', 'desc')->get();
        }
    
        return view('requisition.purchasebyteam.list', compact('requisitions', 'user_id', 'authUserBranch'));
    }
    

    public function purchaselistView($id)
    {

        $requisitionheading = Requisition::with('user')->find($id);

        // $requisitionlist = RequisitionItem::where('requisition_id', $id)->get();  

        $requisitionlist = RequisitionItem::where('requisition_id', $id)
            ->where('purchase', 2)
            ->get();

        $productNames = $requisitionlist->pluck('single_product_name');

        $productIds = Product::whereIn('id', $productNames)->pluck('name', 'id')->toArray();

        return view('requisition.purchasebyteam.view', compact('requisitionheading', 'requisitionlist', 'productIds'));
    }


    






   //purchase team reject list

   public function rejectlistCollection(Request $request)
   {
       $user_id = Auth::id();
       $authUserBranchId = DB::table('users')->where('id', $user_id)->first()->branch_id;
       $authUserBranch = Branch::where('id', $authUserBranchId)->first();
   
       if ($authUserBranch->type === 'PurchaseTeam') {
           $requisitions = Requisition::where('purchase_reject', 1)->orderBy('created_at', 'desc')->get();
       } 
       else {
           $requisitions = Requisition::where('user_id', '!=', $user_id)->where('purchase_reject', 1)->orderBy('created_at', 'desc')->get();
       }
       return view('requisition.purchaseteamownreject.list', compact('requisitions', 'user_id', 'authUserBranch'));
   }
    

   public function rejectlistCollectionView($id)
   {

       $requisitionheading = Requisition::with('user')->find($id);
       $requisitionlist = RequisitionItem::where('requisition_id', $id)
                                       ->where('purchase_team_reject', 1)
                                       ->get();

       $productNames = $requisitionlist->pluck('single_product_name');

       $productIds = Product::whereIn('id', $productNames)->pluck('name', 'id')->toArray();

       return view('requisition.view', compact('requisitionheading', 'requisitionlist', 'productIds'));
   }



    // public function completedorderlist()
    // {
    //     $requisitions = Requisition::where('status', 1)->orderBy('created_at', 'desc')->get();
    //     return view('requisition.completedorder.list', compact('requisitions'));
    // }
    
    

    // public function completedorderlist()
    // {
    //     $user = auth()->user();
    
    //     if ($user->role_name === 'Admin') {
    //         $requisitions = Requisition::where('status', 1)
    //                                     ->orderBy('created_at', 'desc')
    //                                     ->get();
    //     } else {
    //         $requisitions = Requisition::where('status', 1)
    //                                     ->where('branch_id', $user->branch_id)
    //                                     ->orderBy('created_at', 'desc')
    //                                     ->get();
    //     }
    
    //     return view('requisition.completedorder.list', compact('requisitions'));
    // }
    


    //okay and this was last without partial list
    // public function completedorderlist()
    // {
    //     $user = auth()->user();
        
    //     if ($user->role_name === 'Admin') {
    //         $requisitions = Requisition::where('status', 1)
    //                                     ->orderBy('created_at', 'desc')
    //                                     ->get();
    //     } else {
    //         $requisitions = Requisition::where('status', 1)
    //                                     ->where('branch_id', $user->branch_id)
    //                                     ->where('user_id', $user->id) 
    //                                     ->orderBy('created_at', 'desc')
    //                                     ->get();
    //     }
    //     return view('requisition.completedorder.list', compact('requisitions'));
    // }
    

    // public function completedorderlist()
    // {
    //     $user = auth()->user();

    //     if ($user->role_name === 'Admin') {
    //         $requisitions = Requisition::where('status', 1)
    //                                     ->orWhere(function ($query) {
    //                                         $query->where('status', 3)
    //                                             ->where('partial_stock', 0);
    //                                     })
    //                                     ->orderBy('created_at', 'desc')
    //                                     ->get();
    //     } else {
    //         $requisitions = Requisition::where('status', 1)
    //                                     ->orWhere(function ($query) use ($user) {
    //                                         $query->where('status', 3)
    //                                             ->where('partial_stock', 0);
    //                                     })
    //                                     ->where('branch_id', $user->branch_id)
    //                                     ->where('user_id', $user->id)
    //                                     ->orderBy('created_at', 'desc')
    //                                     ->get();
    //     }

    //     return view('requisition.completedorder.list', compact('requisitions'));
    // }







    public function completedorderlist()
    {
        $user = auth()->user();
    
        if ($user->role_name === 'Admin') {
            $requisitions = Requisition::where('status', 1)
                                        // ->orWhere(function ($query) {
                                        //     $query->where('status', 3)
                                        //           ->where('partial_stock', 0);
                                        // })
                                        ->orWhere(function ($query) {
                                            $query->where('status', 4)
                                                  ->where('partial_delivery', 0)
                                                  ->whereNull('partial_stock');
                                        })
                                        ->orWhere(function ($query) {
                                            $query->where('status', 2)
                                                  ->where('partial_delivery', 0)
                                                  ->whereNull('partial_stock');
                                        })
                                        ->orWhere(function ($query) {
                                            $query->where('status', 5)
                                                  ->where('partial_delivery', 0)
                                                  ->whereNull('partial_stock');
                                        })
                                        ->orderBy('created_at', 'desc')
                                        ->get();
        } else { 


            // $requisitions = Requisition::where('status', 1) 

            //                             ->orWhere(function ($query) use ($user) {
            //                                 $query->where('status', 4)
            //                                       ->where('partial_delivery', 0)
            //                                       ->whereNull('partial_stock');
            //                             })
            //                             ->orWhere(function ($query) use ($user) {
            //                                 $query->where('status', 2)
            //                                       ->where('partial_delivery', 0)
            //                                       ->whereNull('partial_stock');
            //                             })
            //                             ->orWhere(function ($query) use ($user) {
            //                                 $query->where('status', 5)
            //                                       ->where('partial_delivery', 0)
            //                                       ->whereNull('partial_stock');
            //                             })
            //                             ->where('branch_id', $user->branch_id)
            //                             ->where('user_id', $user->id)
            //                             ->orderBy('created_at', 'desc')
            //                             ->get();  


            $requisitions = Requisition::where('branch_id', $user->branch_id) 
            ->where(function ($query) {
                $query->where('status', 1)
                      ->orWhere(function ($subQuery) {
                          $subQuery->where('status', 4)
                                   ->where('partial_delivery', 0)
                                   ->whereNull('partial_stock');
                      })
                      ->orWhere(function ($subQuery) {
                          $subQuery->where('status', 2)
                                   ->where('partial_delivery', 0)
                                   ->whereNull('partial_stock');
                      })
                      ->orWhere(function ($subQuery) {
                          $subQuery->where('status', 5)
                                   ->where('partial_delivery', 0)
                                   ->whereNull('partial_stock');
                      });
            })
            ->orderBy('created_at', 'desc')
            ->get();


        }
    
        return view('requisition.completedorder.list', compact('requisitions'));
    }
    
















    public function completedorderlistView($id) 
    {
        $requisitionheading = Requisition::where('status', 1)->find($id);
        $requisitionlist = RequisitionItem::where('requisition_id', $id)->get();

        return view('requisition.completedorder.view', compact('requisitionheading', 'requisitionlist'));
    }


     //old { in stock click then branch + & warehouse both)}

    // public function completedorderlistInstock($id)
    // {
    //     $requisition = Requisition::findOrFail($id);
    //     $requisition->update(['status' => 3]);
    //     $requisitionItems = RequisitionItem::where('requisition_id', $requisition->id)->get();
    
    //     foreach ($requisitionItems as $item) {
    //         $productId = $item->product_id;
    //         $stockLevel = $item->demand_amount;
    //         $branchId = $requisition->branch_id;
    
    //         $branchProduct = Branch_Product::where('branch_id', $branchId)
    //                                        ->where('product_id', $productId)
    //                                        ->first();
    //         if ($branchProduct) {
    //             $branchProduct->stock += $stockLevel;
    //             $branchProduct->save();
    //         } else {
    //             Branch_Product::create([
    //                 'branch_id' => $branchId,
    //                 'product_id' => $productId,
    //                 'stock' => $stockLevel,
    //             ]);
    //         }
    //         $warehouseBranchProduct = Branch_Product::whereHas('branch', function ($query) {
    //                 $query->where('type', 'Warehouse');
    //             })
    //             ->where('product_id', $productId)
    //             ->first();
    
    //         if ($warehouseBranchProduct) {
    //             $warehouseBranchProduct->stock -= $stockLevel;
    //             $warehouseBranchProduct->save();
    //         }
    //     }
    //     Toastr::success('Stock updated successfully.', 'Success');
    //     return redirect()->route('completed.order.list');
    // }



    //Okay
    //new { in stock click then branch + only single)}

    // public function completedorderlistInstock($id)
    // {
    //     $requisition = Requisition::findOrFail($id);
    //     $requisition->update(['status' => 3]);
    //     $requisitionItems = RequisitionItem::where('requisition_id', $requisition->id)->get();
    
    //     foreach ($requisitionItems as $item) {
    //         $productId = $item->product_id;
    //         $stockLevel = $item->demand_amount;
    //         $branchId = $requisition->branch_id;
    
    //         $branchProduct = Branch_Product::where('branch_id', $branchId)
    //                                        ->where('product_id', $productId)
    //                                        ->first();
    //         if ($branchProduct) {
    //             $branchProduct->stock += $stockLevel;
    //             $branchProduct->save();
    //         } else {
    //             Branch_Product::create([
    //                 'branch_id' => $branchId,
    //                 'product_id' => $productId,
    //                 'stock' => $stockLevel,
    //             ]);
    //         }
            
    //         // Product Ledger Entry
    //         ProductLedger::create([
    //             'entry_date' => date('Y-m-d'),
    //             'narration' => 'Stock In',
    //             'type' => 'Stock In',
    //             'user_id' => auth()->id(),
    //             'branch_id' => $branchId,
    //             'product_id' => $productId,
    //             'quantity' => $stockLevel,
    //             'price' => $item->total_price,
    //         ]);
    //     }
        
    //     Toastr::success('Stock updated successfully.', 'Success');
    //     return redirect()->route('completed.order.list');
    // }







    // public function completedorderlistInstock($id)
    // {
    //     $requisition = Requisition::findOrFail($id);
    //     $requisition->update(['status' => 3]);

    //     // $requisitionItems = RequisitionItem::where('requisition_id', $requisition->id)->get();  

    //     $requisitionItems = RequisitionItem::where('requisition_id', $requisition->id)
    //                                ->where('delivery', 1)
    //                                ->get();

    
    //     foreach ($requisitionItems as $item) {
    //         $productId = $item->product_id;
    //         $stockLevel = $item->demand_amount;
    //         $branchId = $requisition->branch_id;
    
    //         $branchProduct = Branch_Product::where('branch_id', $branchId)
    //                                        ->where('product_id', $productId)
    //                                        ->first();
    //         if ($branchProduct) {
    //             $branchProduct->stock += $stockLevel;
    //             $branchProduct->save();
    //         } else {
    //             Branch_Product::create([
    //                 'branch_id' => $branchId,
    //                 'product_id' => $productId,
    //                 'stock' => $stockLevel,
    //             ]);
    //         }
            
    //         // Product Ledger Entry
    //         ProductLedger::create([
    //             'entry_date' => date('Y-m-d'),
    //             'narration' => 'Stock In',
    //             'type' => 'Stock In',
    //             'user_id' => auth()->id(),
    //             'branch_id' => $branchId,
    //             'product_id' => $productId,
    //             'quantity' => $stockLevel,
    //             'price' => $item->total_price,
    //         ]);
    //     }
        
    //     Toastr::success('Stock updated successfully.', 'Success');
    //     return redirect()->route('completed.order.list');
    // }



    // public function completedorderlistInstock($id)
    // {
    //     $requisition = Requisition::findOrFail($id);
    //     $requisition->update([
    //         'status' => 3,
    //         'partial_stock' => 0
    //     ]);
    
    //     $requisitionItems = RequisitionItem::where('requisition_id', $requisition->id)
    //                                        ->where('delivery', 1)
    //                                        ->get();
    
    //     foreach ($requisitionItems as $item) {
    //         $productId = $item->product_id;
    //         $stockLevel = $item->demand_amount;
    //         $branchId = $requisition->branch_id;
    
    //         $branchProduct = Branch_Product::where('branch_id', $branchId)
    //                                        ->where('product_id', $productId)
    //                                        ->first();
    //         if ($branchProduct) {
    //             $branchProduct->stock += $stockLevel;
    //             $branchProduct->save();
    //         } else {
    //             Branch_Product::create([
    //                 'branch_id' => $branchId,
    //                 'product_id' => $productId,
    //                 'stock' => $stockLevel,
    //             ]);
    //         }
            
    //         // Update stock_status in RequisitionItem
    //         $item->update(['stock_status' => 1]);
    
    //         // Product Ledger Entry
    //         ProductLedger::create([
    //             'entry_date' => date('Y-m-d'),
    //             'narration' => 'Stock In',
    //             'type' => 'Stock In',
    //             'user_id' => auth()->id(),
    //             'branch_id' => $branchId,
    //             'product_id' => $productId,
    //             'quantity' => $stockLevel,
    //             'price' => $item->total_price,
    //         ]);
    //     }
        
    //     Toastr::success('Stock updated successfully.', 'Success');
    //     return redirect()->route('completed.order.list');
    // }
    
    


    //today
    // public function completedorderlistInstock($id) 
    // {
    //     $requisition = Requisition::findOrFail($id);
    
    //     $requisitionItems = RequisitionItem::where('requisition_id', $requisition->id)->get();
    
    //     $allDelivered = $requisitionItems->every(function ($item) {
    //         return $item->delivery == 1;
    //     });
    
    //     $requisition->update([
    //         'status' => 3,
    //         'partial_stock' => $allDelivered ? 1 : 0,
    //     ]);
    
    //     $deliveredItems = $requisitionItems->where('delivery', 1);
    
    //     foreach ($deliveredItems as $item) {
    //         $productId = $item->product_id;
    //         $stockLevel = $item->demand_amount;
    //         $branchId = $requisition->branch_id;
    
    //         $branchProduct = Branch_Product::where('branch_id', $branchId)
    //                                        ->where('product_id', $productId)
    //                                        ->first();
    
    //         if ($branchProduct) {
    //             $branchProduct->stock += $stockLevel;
    //             $branchProduct->save();
    //         } else {
    //             Branch_Product::create([
    //                 'branch_id' => $branchId,
    //                 'product_id' => $productId,
    //                 'stock' => $stockLevel,
    //             ]);
    //         }
    
    //         $item->update(['stock_status' => 1]);

    //         ProductLedger::create([
    //             'entry_date' => date('Y-m-d'),
    //             'narration' => 'Stock In',
    //             'type' => 'Stock In',
    //             'user_id' => auth()->id(),
    //             'branch_id' => $branchId,
    //             'product_id' => $productId,
    //             'quantity' => $stockLevel,
    //             'price' => $item->total_price,
    //         ]);
    //     }
    
    //     Toastr::success('Stock updated successfully.', 'Success');
    //     return redirect()->route('completed.order.list');
    // }
    


    // public function completedorderlistInstock($id) 
    // {
    //     $requisition = Requisition::findOrFail($id);
        
    //     $requisitionItems = RequisitionItem::where('requisition_id', $requisition->id)->get();
        
    //     // Check if all items meet the condition: 'delivery' = 1 and 'stock_status' = 0
    //     $validItems = $requisitionItems->every(function ($item) {
    //         return $item->delivery == 1 && $item->stock_status == 0;
    //     });
    
    //     if (!$validItems) {
    //         Toastr::error('Not Possible to stock', 'Error');
    //         return redirect()->route('completed.order.list');
    //     }
    
    //     $allDelivered = $requisitionItems->every(function ($item) {
    //         return $item->delivery == 1;
    //     });
        
    //     $requisition->update([
    //         'status' => 3,
    //         'partial_stock' => $allDelivered ? 1 : 0,
    //     ]);
    
    //     $deliveredItems = $requisitionItems->where('delivery', 1);
    
    //     foreach ($deliveredItems as $item) {
    //         $productId = $item->product_id;
    //         $stockLevel = $item->demand_amount;
    //         $branchId = $requisition->branch_id;
    
    //         $branchProduct = Branch_Product::where('branch_id', $branchId)
    //                                        ->where('product_id', $productId)
    //                                        ->first();
    
    //         if ($branchProduct) {
    //             $branchProduct->stock += $stockLevel;
    //             $branchProduct->save();
    //         } else {
    //             Branch_Product::create([
    //                 'branch_id' => $branchId,
    //                 'product_id' => $productId,
    //                 'stock' => $stockLevel,
    //             ]);
    //         }
    
    //         $item->update(['stock_status' => 1]);
    
    //         ProductLedger::create([
    //             'entry_date' => date('Y-m-d'),
    //             'narration' => 'Stock In',
    //             'type' => 'Stock In',
    //             'user_id' => auth()->id(),
    //             'branch_id' => $branchId,
    //             'product_id' => $productId,
    //             'quantity' => $stockLevel,
    //             'price' => $item->total_price,
    //         ]);
    //     }
    
    //     Toastr::success('Stock updated successfully.', 'Success');
    //     return redirect()->route('completed.order.list');
    // }
    






    // public function completedorderlistInstock($id) 
    // {
    //     $requisition = Requisition::findOrFail($id);
    //     $requisitionItems = RequisitionItem::where('requisition_id', $requisition->id)->get();
    
    //     // Filter valid items to stock: 'delivery' = 1 and 'stock_status' = 0
    //     $validItems = $requisitionItems->filter(function ($item) {
    //         return $item->delivery == 1 && $item->stock_status == 0;
    //     });
    
    //     // Filter invalid items
    //     $invalidItems = $requisitionItems->filter(function ($item) {
    //         return !($item->delivery == 1 && $item->stock_status == 0);
    //     });
    
    //     if ($invalidItems->isNotEmpty()) {
    //         Toastr::error('Rest of item not possible to stock', 'Error');
    //     }
    
    //     // Update requisition status and partial_stock based on valid items
    //     $allDelivered = $validItems->count() === $requisitionItems->count();
    //     $requisition->update([
    //         'status' => 3,
    //         'partial_stock' => $allDelivered ? 0 : 1,
    //     ]);
    
    //     // Process valid items for stocking
    //     foreach ($validItems as $item) {
    //         $productId = $item->product_id;
    //         $stockLevel = $item->demand_amount;
    //         $branchId = $requisition->branch_id;
    
    //         $branchProduct = Branch_Product::where('branch_id', $branchId)
    //                                        ->where('product_id', $productId)
    //                                        ->first();
    
    //         if ($branchProduct) {
    //             $branchProduct->stock += $stockLevel;
    //             $branchProduct->save();
    //         } else {
    //             Branch_Product::create([
    //                 'branch_id' => $branchId,
    //                 'product_id' => $productId,
    //                 'stock' => $stockLevel,
    //             ]);
    //         }
    
    //         // Update stock_status for valid items
    //         $item->update(['stock_status' => 1]);
    
    //         ProductLedger::create([
    //             'entry_date' => date('Y-m-d'),
    //             'narration' => 'Stock In',
    //             'type' => 'Stock In',
    //             'user_id' => auth()->id(),
    //             'branch_id' => $branchId,
    //             'product_id' => $productId,
    //             'quantity' => $stockLevel,
    //             'price' => $item->total_price,
    //         ]);
    //     }
    
    //     if ($validItems->isNotEmpty()) {
    //         Toastr::success('Stock updated successfully for valid items.', 'Success');
    //     }
    
    //     return redirect()->route('completed.order.list');
    // }
    






    // public function completedorderlistInstock($id) 
    // {
    //     $requisition = Requisition::findOrFail($id);
    //     $requisitionItems = RequisitionItem::where('requisition_id', $requisition->id)->get();
    
    //     // Filter valid items to stock: 'delivery' = 1 and 'stock_status' = 0
    //     $validItems = $requisitionItems->filter(function ($item) {
    //         return $item->delivery == 1 && $item->stock_status == 0;
    //     });
    
    //     // Filter invalid items
    //     $invalidItems = $requisitionItems->filter(function ($item) {
    //         return !($item->delivery == 1 && $item->stock_status == 0);
    //     });
    
    //     if ($invalidItems->isNotEmpty()) {
    //         Toastr::error('Rest of item not possible to stock', 'Error');
    //     }
    
       
    //     $allDelivered = $requisitionItems->every(function ($item) {
    //         return $item->stock_status == 1;
    //     });
        
    //     $requisition->update([
    //         'status' => 3,
    //         'partial_stock' => $allDelivered ? 1 : 0, 
    //     ]);
        
    
    //     // Process valid items for stocking
    //     foreach ($validItems as $item) {
    //         $productId = $item->product_id;
    //         $stockLevel = $item->demand_amount;
    //         $branchId = $requisition->branch_id;
    
    //         $branchProduct = Branch_Product::where('branch_id', $branchId)
    //                                        ->where('product_id', $productId)
    //                                        ->first();
    
    //         if ($branchProduct) {
    //             $branchProduct->stock += $stockLevel;
    //             $branchProduct->save();
    //         } else {
    //             Branch_Product::create([
    //                 'branch_id' => $branchId,
    //                 'product_id' => $productId,
    //                 'stock' => $stockLevel,
    //             ]);
    //         }
    
    //         // Update stock_status for valid items
    //         $item->update(['stock_status' => 1]);
    
    //         ProductLedger::create([
    //             'entry_date' => date('Y-m-d'),
    //             'narration' => 'Stock In',
    //             'type' => 'Stock In',
    //             'user_id' => auth()->id(),
    //             'branch_id' => $branchId,
    //             'product_id' => $productId,
    //             'quantity' => $stockLevel,
    //             'price' => $item->total_price,
    //         ]);
    //     }
    
    //     if ($validItems->isNotEmpty()) {
    //         Toastr::success('Stock updated successfully for valid items.', 'Success');
    //     }
    
    //     return redirect()->route('completed.order.list');
    // }
    


    // public function completedorderlistInstock($id) 
    // {
    //     $requisition = Requisition::findOrFail($id);
    //     $requisitionItems = RequisitionItem::where('requisition_id', $requisition->id)->get();
    
    //     $validItems = $requisitionItems->filter(function ($item) {
    //         return $item->delivery == 1 && $item->stock_status == 0;
    //     });
    
    //     $invalidItems = $requisitionItems->filter(function ($item) {
    //         return !($item->delivery == 1 && $item->stock_status == 0);
    //     });
    
    //     if ($invalidItems->isNotEmpty()) {
    //     }
    
    //     foreach ($validItems as $item) {
    //         $productId = $item->product_id;
    //         $stockLevel = $item->demand_amount;
    //         $branchId = $requisition->branch_id;
    
    //         $branchProduct = Branch_Product::where('branch_id', $branchId)
    //                                        ->where('product_id', $productId)
    //                                        ->first();
    
    //         if ($branchProduct) {
    //             $branchProduct->stock += $stockLevel;
    //             $branchProduct->save();
    //         } else {
    //             Branch_Product::create([
    //                 'branch_id' => $branchId,
    //                 'product_id' => $productId,
    //                 'stock' => $stockLevel,
    //             ]);
    //         }
    
    //         $item->update(['stock_status' => 1]);  

    //         $allPurchased = $requisitionItems->every(function ($item) {
    //             return $item->purchase == 1; 
    //         });
            
    //         $allDelivered = $validItems->every(function ($item) {
    //             return $item->stock_status == 1; 
    //         });
            
    //         $requisition->update([
    //             'status' => 3,
    //             'partial_stock' => $allPurchased ? 1 : 0, 
    //         ]);
            
    //         ProductLedger::create([
    //             'entry_date' => date('Y-m-d'),
    //             'narration' => 'Stock In',
    //             'type' => 'Stock In',
    //             'user_id' => auth()->id(),
    //             'branch_id' => $branchId,
    //             'product_id' => $productId,
    //             'quantity' => $stockLevel,
    //             'price' => $item->total_price,
    //         ]);
    //     }
    
    //     if ($validItems->isNotEmpty()) {
    //         Toastr::success('Stock updated successfully for valid items.', 'Success');
    //     }
    
    //     return redirect()->route('completed.order.list');
    // }
    






 /// done but some missing last today 

    // public function completedorderlistInstock($id) 
    // {
    //     $requisition = Requisition::findOrFail($id);
    //     $requisitionItems = RequisitionItem::where('requisition_id', $requisition->id)->get();

    //     $validItems = $requisitionItems->filter(function ($item) {
    //         return $item->delivery == 1 && $item->stock_status == 0;
    //     });

    //     $invalidItems = $requisitionItems->filter(function ($item) {
    //         return !($item->delivery == 1 && $item->stock_status == 0);
    //     });

    //     if ($invalidItems->isNotEmpty()) {
    //     }

    //     foreach ($validItems as $item) {
    //         $productId = $item->product_id;
    //         $stockLevel = $item->demand_amount;
    //         $branchId = $requisition->branch_id;

    //         $branchProduct = Branch_Product::where('branch_id', $branchId)
    //                                     ->where('product_id', $productId)
    //                                     ->first();

    //         if ($branchProduct) {
    //             $branchProduct->stock += $stockLevel;
    //             $branchProduct->save();
    //         } else {
    //             Branch_Product::create([
    //                 'branch_id' => $branchId,
    //                 'product_id' => $productId,
    //                 'stock' => $stockLevel,
    //             ]);
    //         }

    //         $item->update(['stock_status' => 1]);  

    //         ProductLedger::create([
    //             'entry_date' => date('Y-m-d'),
    //             'narration' => 'Stock In',
    //             'type' => 'Stock In',
    //             'user_id' => auth()->id(),
    //             'branch_id' => $branchId,
    //             'product_id' => $productId,
    //             'quantity' => $stockLevel,
    //             'price' => $item->total_price,
    //         ]);
    //     }

    //     $allStocked = $requisitionItems->every(function ($item) {
    //         return $item->stock_status == 1;
    //     });

    //     $requisition->update([
    //         'status' => 3,
    //         'partial_stock' => $allStocked ? 1 : 0,
    //     ]);

    //     if ($validItems->isNotEmpty()) {
    //         Toastr::success('Stock updated successfully for valid items.', 'Success');
    //     }

    //     return redirect()->route('completed.order.list');
    // }




  //okay last
//     public function completedorderlistInstock($id) 
// {
//     $requisition = Requisition::findOrFail($id);
//     $requisitionItems = RequisitionItem::where('requisition_id', $requisition->id)->get();

//     $validItems = $requisitionItems->filter(function ($item) {
//         return $item->delivery == 1 && $item->stock_status == 0;
//     });

//     $invalidItems = $requisitionItems->filter(function ($item) {
//         return !($item->delivery == 1 && $item->stock_status == 0);
//     });

//     // Check if there are any pending products
//     $pendingProducts = $requisitionItems->filter(function ($item) {
//         return $item->delivery == 0 && $item->stock_status == 0;
//     });

//     foreach ($validItems as $item) {
//         $productId = $item->product_id;
//         $stockLevel = $item->demand_amount;
//         $branchId = $requisition->branch_id;

//         $branchProduct = Branch_Product::where('branch_id', $branchId)
//                                     ->where('product_id', $productId)
//                                     ->first();

//         if ($branchProduct) {
//             $branchProduct->stock += $stockLevel;
//             $branchProduct->save();
//         } else {
//             Branch_Product::create([
//                 'branch_id' => $branchId,
//                 'product_id' => $productId,
//                 'stock' => $stockLevel,
//             ]);
//         }

//         $item->update(['stock_status' => 1]);  

//         // ProductLedger::create([
//         //     'entry_date' => date('Y-m-d'),
//         //     'narration' => 'Stock In',
//         //     'type' => 'Stock In',
//         //     'user_id' => auth()->id(),
//         //     'branch_id' => $branchId,
//         //     'product_id' => $productId,
//         //     'quantity' => $stockLevel,
//         //     'price' => $item->total_price,
//         // ]);
//     }

//     $allStocked = $requisitionItems->every(function ($item) {
//         return $item->stock_status == 1;
//     });

//     $requisition->update([
//         'status' => 3,
//         'partial_stock' => $allStocked ? 1 : 0,
//     ]);

//     if ($validItems->isNotEmpty()) {
//         Toastr::success('Stock updated successfully for valid items.', 'Success');
//     }

//     // Show error message if there are pending products
//     // if ($pendingProducts->isNotEmpty()) {
//     //     Toastr::error('Stock is not possible for pending product.', 'Error');
//     // }

//     return redirect()->route('completed.order.list');
// }



   //okay last 2
    // public function completedorderlistInstock($id) 
    // {
    //     $requisition = Requisition::findOrFail($id);
    //     $requisitionItems = RequisitionItem::where('requisition_id', $requisition->id)->get();

    //     $validItems = $requisitionItems->filter(function ($item) {
    //         return $item->delivery == 1 && $item->stock_status == 0;
    //     });

    //     $invalidItems = $requisitionItems->filter(function ($item) {
    //         return !($item->delivery == 1 && $item->stock_status == 0);
    //     });

    //     // Check if there are any pending products
    //     $pendingProducts = $requisitionItems->filter(function ($item) {
    //         return $item->delivery == 0 && $item->stock_status == 0;
    //     });

    //     foreach ($validItems as $item) {
    //         $productId = $item->product_id;
    //         $stockLevel = $item->demand_amount;
    //         $branchId = $requisition->branch_id;

    //         $branchProduct = Branch_Product::where('branch_id', $branchId)
    //                                     ->where('product_id', $productId)
    //                                     ->first();

    //         if ($branchProduct) {
    //             $branchProduct->stock += $stockLevel;
                
    //             // Get data from branch_headoffice_logs table
    //             $headofficeLog = BranchHeadofficeLog::where('branch_id', $branchId)
    //                                             ->where('requisition_id', $requisition->id)
    //                                             ->where('product_id', $productId)
    //                                             ->first();
                
    //             if ($headofficeLog) {
    //                 // Get price_quantity and user_id from the log
    //                 $priceQuantityData = json_decode($headofficeLog->price_quantity, true);
    //                 $userId = $headofficeLog->user_id;
                    
    //                 // Prepare details_stockin data
    //                 $stockinDetails = $branchProduct->details_stockin 
    //                     ? json_decode($branchProduct->details_stockin, true) 
    //                     : [];
                    
    //                 if (!is_array($stockinDetails)) {
    //                     $stockinDetails = [];
    //                 }
                    
    //                 // Add new entry for each price/quantity pair
    //                 foreach ($priceQuantityData as $pqData) {
    //                     $stockinDetails[] = [
    //                         'requisition' => $requisition->id,
    //                         'quantity' => $pqData['quantity'],
    //                         'price' => $pqData['price'],
    //                         'date' => now()->format('d-m-Y'),
    //                         'store_by' => $userId
    //                     ];
    //                 }
                    
    //                 // Save the updated details_stockin
    //                 $branchProduct->details_stockin = json_encode($stockinDetails);
    //             }
                
    //             $branchProduct->save();
    //         } else {
    //             // Create new branch product
    //             $newBranchProduct = Branch_Product::create([
    //                 'branch_id' => $branchId,
    //                 'product_id' => $productId,
    //                 'stock' => $stockLevel,
    //             ]);
                
    //             // Get data from branch_headoffice_logs table
    //             $headofficeLog = BranchHeadofficeLog::where('branch_id', $branchId)
    //                                             ->where('requisition_id', $requisition->id)
    //                                             ->where('product_id', $productId)
    //                                             ->first();
                
    //             if ($headofficeLog) {
    //                 // Get price_quantity and user_id from the log
    //                 $priceQuantityData = json_decode($headofficeLog->price_quantity, true);
    //                 $userId = $headofficeLog->user_id;
                    
    //                 // Prepare details_stockin data
    //                 $stockinDetails = [];
                    
    //                 // Add new entry for each price/quantity pair
    //                 foreach ($priceQuantityData as $pqData) {
    //                     $stockinDetails[] = [
    //                         'requisition' => $requisition->id,
    //                         'quantity' => $pqData['quantity'],
    //                         'price' => $pqData['price'],
    //                         'date' => now()->format('d-m-Y'),
    //                         'store_by' => $userId
    //                     ];
    //                 }
                    
    //                 // Save the details_stockin
    //                 $newBranchProduct->details_stockin = json_encode($stockinDetails);
    //                 $newBranchProduct->save();
    //             }
    //         }

    //         $item->update(['stock_status' => 1]);  
    //     }

    //     $allStocked = $requisitionItems->every(function ($item) {
    //         return $item->stock_status == 1;
    //     });

    //     $requisition->update([
    //         'status' => 3,
    //         'partial_stock' => $allStocked ? 1 : 0,
    //     ]);

    //     if ($validItems->isNotEmpty()) {
    //         Toastr::success('Stock updated successfully for valid items.', 'Success');
    //     }

    //     return redirect()->route('completed.order.list');
    // }



    //okay last 3
    // public function completedorderlistInstock($id) 
    // {
    //     $requisition = Requisition::findOrFail($id);
    //     $requisitionItems = RequisitionItem::where('requisition_id', $requisition->id)->get();

    //     $validItems = $requisitionItems->filter(function ($item) {
    //         return $item->delivery == 1 && $item->stock_status == 0;
    //     });

    //     $invalidItems = $requisitionItems->filter(function ($item) {
    //         return !($item->delivery == 1 && $item->stock_status == 0);
    //     });

    //     $pendingProducts = $requisitionItems->filter(function ($item) {
    //         return $item->delivery == 0 && $item->stock_status == 0;
    //     });

    //     $branchId = $requisition->branch_id;
    //     $branch = Branch::find($branchId);
    //     $narrationType = $branch && $branch->type === 'Headoffice' ? 'Headoffice' : 'Branch';

    //     foreach ($validItems as $item) {
    //         $productId = $item->product_id;
    //         $stockLevel = $item->demand_amount;

    //         $branchProduct = Branch_Product::where('branch_id', $branchId)
    //                                     ->where('product_id', $productId)
    //                                     ->first();

    //         if ($branchProduct) {
    //             $branchProduct->stock += $stockLevel;

    //             $headofficeLog = BranchHeadofficeLog::where('branch_id', $branchId)
    //                                             ->where('requisition_id', $requisition->id)
    //                                             ->where('product_id', $productId)
    //                                             ->first();

    //             if ($headofficeLog) {
    //                 $priceQuantityData = json_decode($headofficeLog->price_quantity, true);
    //                 $userId = $headofficeLog->user_id;

    //                 $stockinDetails = $branchProduct->details_stockin 
    //                     ? json_decode($branchProduct->details_stockin, true) 
    //                     : [];

    //                 if (!is_array($stockinDetails)) {
    //                     $stockinDetails = [];
    //                 }

    //                 foreach ($priceQuantityData as $pqData) {
    //                     $stockinDetails[] = [
    //                         'requisition' => $requisition->id,
    //                         'quantity' => $pqData['quantity'],
    //                         'price' => $pqData['price'],
    //                         'date' => now()->format('d-m-Y'),
    //                         'store_by' => $userId
    //                     ];
    //                 }

    //                 $branchProduct->details_stockin = json_encode($stockinDetails);
    //             }

    //             $branchProduct->save();

    //             if (!empty($priceQuantityData)) {
    //                 foreach ($priceQuantityData as $pqData) {
    //                     ProductLedgerBH::create([
    //                         'entry_date'     => now()->format('Y-m-d'),
    //                         'narration'      => $narrationType,
    //                         'type'           => 'StockIn',
    //                         'user_id'        => $userId ?? auth()->id(),
    //                         'branch_id'      => $branchId,
    //                         'product_id'     => $productId,
    //                         'quantity'       => $pqData['quantity'],
    //                         'price'          => $pqData['price'],
    //                         'requisition_id' => $requisition->id,
    //                     ]);
    //                 }
    //             }
    //         } else {
    //             $newBranchProduct = Branch_Product::create([
    //                 'branch_id' => $branchId,
    //                 'product_id' => $productId,
    //                 'stock' => $stockLevel,
    //             ]);

    //             $headofficeLog = BranchHeadofficeLog::where('branch_id', $branchId)
    //                                             ->where('requisition_id', $requisition->id)
    //                                             ->where('product_id', $productId)
    //                                             ->first();

    //             if ($headofficeLog) {
    //                 $priceQuantityData = json_decode($headofficeLog->price_quantity, true);
    //                 $userId = $headofficeLog->user_id;

    //                 $stockinDetails = [];

    //                 foreach ($priceQuantityData as $pqData) {
    //                     $stockinDetails[] = [
    //                         'requisition' => $requisition->id,
    //                         'quantity' => $pqData['quantity'],
    //                         'price' => $pqData['price'],
    //                         'date' => now()->format('d-m-Y'),
    //                         'store_by' => $userId
    //                     ];
    //                 }

    //                 $newBranchProduct->details_stockin = json_encode($stockinDetails);
    //                 $newBranchProduct->save();

    //                 foreach ($priceQuantityData as $pqData) {
    //                     ProductLedgerBH::create([
    //                         'entry_date'     => now()->format('Y-m-d'),
    //                         'narration'      => $narrationType,
    //                         'type'           => 'StockIn',
    //                         'user_id'        => $userId ?? auth()->id(),
    //                         'branch_id'      => $branchId,
    //                         'product_id'     => $productId,
    //                         'quantity'       => $pqData['quantity'],
    //                         'price'          => $pqData['price'],
    //                         'requisition_id' => $requisition->id,
    //                     ]);
    //                 }
    //             }
    //         }

    //         $item->update(['stock_status' => 1]);  
    //     }

    //     $allStocked = $requisitionItems->every(function ($item) {
    //         return $item->stock_status == 1;
    //     });

    //     $requisition->update([
    //         'status' => 3,
    //         'partial_stock' => $allStocked ? 1 : 0,
    //     ]);

    //     if ($validItems->isNotEmpty()) {
    //         Toastr::success('Stock updated successfully for valid items.', 'Success');
    //     }

    //     return redirect()->route('completed.order.list');
    // }




    // public function completedorderlistInstock($id) 
    // {
    //     $requisition = Requisition::findOrFail($id);
    //     $requisitionItems = RequisitionItem::where('requisition_id', $requisition->id)->get();

    //     $validItems = $requisitionItems->filter(function ($item) {
    //         return $item->delivery == 1 && $item->stock_status == 0;
    //     });

    //     $invalidItems = $requisitionItems->filter(function ($item) {
    //         return !($item->delivery == 1 && $item->stock_status == 0);
    //     });

    //     $pendingProducts = $requisitionItems->filter(function ($item) {
    //         return $item->delivery == 0 && $item->stock_status == 0;
    //     });

    //     $branchId = $requisition->branch_id;
    //     $branch = Branch::find($branchId);
    //     $narrationType = $branch && $branch->type === 'Headoffice' ? 'Headoffice' : 'Branch';

    //     foreach ($validItems as $item) {
    //         $productId = $item->product_id;
    //         $stockLevel = $item->demand_amount;

    //         $branchProduct = Branch_Product::where('branch_id', $branchId)
    //                                     ->where('product_id', $productId)
    //                                     ->first();

    //         if ($branchProduct) {
    //             $branchProduct->stock += $stockLevel;

    //             $headofficeLog = BranchHeadofficeLog::where('branch_id', $branchId)
    //                                             ->where('requisition_id', $requisition->id)
    //                                             ->where('product_id', $productId)
    //                                             ->first();

    //             if ($headofficeLog) {
    //                 $priceQuantityData = json_decode($headofficeLog->price_quantity, true);
    //                 $userId = $headofficeLog->user_id;

    //                 $stockinDetails = $branchProduct->details_stockin 
    //                     ? json_decode($branchProduct->details_stockin, true) 
    //                     : [];

    //                 if (!is_array($stockinDetails)) {
    //                     $stockinDetails = [];
    //                 }

    //                 foreach ($priceQuantityData as $pqData) {
    //                     $stockinDetails[] = [
    //                         'req' => $requisition->id,
    //                         'qty' => $pqData['quantity'],
    //                         'prc' => $pqData['price'],
    //                         'date' => now()->format('d-m-Y'),
    //                         'usr' => $userId
    //                     ];
    //                 }

    //                 $branchProduct->details_stockin = json_encode($stockinDetails);
    //             }

    //             $branchProduct->save();

    //             if (!empty($priceQuantityData)) {
    //                 foreach ($priceQuantityData as $pqData) {
    //                     ProductLedgerBH::create([
    //                         'entry_date'     => now()->format('Y-m-d'),
    //                         'narration'      => $narrationType,
    //                         'type'           => 'StockIn',
    //                         'user_id'        => $userId ?? auth()->id(),
    //                         'branch_id'      => $branchId,
    //                         'product_id'     => $productId,
    //                         'quantity'       => $pqData['quantity'],
    //                         'price'          => $pqData['price'],
    //                         'requisition_id' => $requisition->id,
    //                     ]);
    //                 }
    //             }
    //         } else {
    //             $newBranchProduct = Branch_Product::create([
    //                 'branch_id' => $branchId,
    //                 'product_id' => $productId,
    //                 'stock' => $stockLevel,
    //             ]);

    //             $headofficeLog = BranchHeadofficeLog::where('branch_id', $branchId)
    //                                             ->where('requisition_id', $requisition->id)
    //                                             ->where('product_id', $productId)
    //                                             ->first();

    //             if ($headofficeLog) {
    //                 $priceQuantityData = json_decode($headofficeLog->price_quantity, true);
    //                 $userId = $headofficeLog->user_id;

    //                 $stockinDetails = [];

    //                 foreach ($priceQuantityData as $pqData) {
    //                     $stockinDetails[] = [
    //                         'req' => $requisition->id,
    //                         'qty' => $pqData['quantity'],
    //                         'prc' => $pqData['price'],
    //                         'date' => now()->format('d-m-Y'),
    //                         'usr' => $userId
    //                     ];
    //                 }

    //                 $newBranchProduct->details_stockin = json_encode($stockinDetails);
    //                 $newBranchProduct->save();
                    
    //                 foreach ($priceQuantityData as $pqData) {
    //                     ProductLedgerBH::create([
    //                         'entry_date'     => now()->format('Y-m-d'),
    //                         'narration'      => $narrationType,
    //                         'type'           => 'StockIn',
    //                         'user_id'        => $userId ?? auth()->id(),
    //                         'branch_id'      => $branchId,
    //                         'product_id'     => $productId,
    //                         'quantity'       => $pqData['quantity'],
    //                         'price'          => $pqData['price'],
    //                         'requisition_id' => $requisition->id,
    //                     ]);
    //                 }
    //             }
    //         }

    //         $item->update(['stock_status' => 1]);  
    //     }

    //     $allStocked = $requisitionItems->every(function ($item) {
    //         return $item->stock_status == 1;
    //     });

    //     $requisition->update([
    //         'status' => 3,
    //         'partial_stock' => $allStocked ? 1 : 0,
    //     ]);

    //     if ($validItems->isNotEmpty()) {
    //         Toastr::success('Stock updated successfully for valid items.', 'Success');
    //     }

    //     return redirect()->route('completed.order.list');
    // }



    //new code tmr will check

    public function completedorderlistInstock($id) 
    {
        $requisition = Requisition::findOrFail($id);
        $requisitionItems = RequisitionItem::where('requisition_id', $requisition->id)->get();

        $validItems = $requisitionItems->filter(function ($item) {
            return $item->delivery == 1 && $item->stock_status == 0;
        });

        $invalidItems = $requisitionItems->filter(function ($item) {
            return !($item->delivery == 1 && $item->stock_status == 0);
        });

        $pendingProducts = $requisitionItems->filter(function ($item) {
            return $item->delivery == 0 && $item->stock_status == 0;
        });

        $branchId = $requisition->branch_id;
        $branch = Branch::find($branchId);
        $narrationType = $branch && $branch->type === 'Headoffice' ? 'Headoffice' : 'Branch';

        foreach ($validItems as $item) {
            $productId = $item->product_id;
            $stockLevel = $item->demand_amount;

            $branchProduct = Branch_Product::where('branch_id', $branchId)
                                        ->where('product_id', $productId)
                                        ->first();

            if ($branchProduct) {
                $branchProduct->stock += $stockLevel;

                $headofficeLog = BranchHeadofficeLog::where('branch_id', $branchId)
                                                ->where('requisition_id', $requisition->id)
                                                ->where('product_id', $productId)
                                                ->first();

                if ($headofficeLog) {
                    $priceQuantityData = json_decode($headofficeLog->price_quantity, true);
                    $userId = $headofficeLog->user_id;

                    $stockinDetails = $branchProduct->details_stockin 
                        ? json_decode($branchProduct->details_stockin, true) 
                        : [];

                    if (!is_array($stockinDetails)) {
                        $stockinDetails = [];
                    }

                    foreach ($priceQuantityData as $pqData) {
                        $stockinDetails[] = [
                            'req' => $requisition->id,
                            'qty' => $pqData['quantity'],
                            'prc' => $pqData['price'],
                            'date' => now()->format('d-m-Y'),
                            'usr' => $userId
                        ];
                    }

                    $branchProduct->details_stockin = json_encode($stockinDetails);

                    // NEW: Remain Details Logic
                    $remainDetails = $branchProduct->remain_details 
                        ? json_decode($branchProduct->remain_details, true) 
                        : [];

                    if (!is_array($remainDetails)) {
                        $remainDetails = [];
                    }

                    foreach ($priceQuantityData as $pqData) {
                        $matched = false;
                        foreach ($remainDetails as &$rd) {
                            if ($rd['prc'] == $pqData['price']) {
                                $rd['qty'] = (string)((float)$rd['qty'] + (float)$pqData['quantity']);
                                $matched = true;
                                break;
                            }
                        }
                        unset($rd);

                        if (!$matched) {
                            $remainDetails[] = [
                                'qty' => (string)$pqData['quantity'],
                                'prc' => $pqData['price']
                            ];
                        }
                    }

                    $branchProduct->remain_details = json_encode($remainDetails);
                }

                $branchProduct->save();

                if (!empty($priceQuantityData)) {
                    foreach ($priceQuantityData as $pqData) {
                        ProductLedgerBH::create([
                            'entry_date'     => now()->format('Y-m-d'),
                            'narration'      => $narrationType,
                            'type'           => 'StockIn',
                            'user_id'        => $userId ?? auth()->id(),
                            'branch_id'      => $branchId,
                            'product_id'     => $productId,
                            'quantity'       => $pqData['quantity'],
                            'price'          => $pqData['price'],
                            'requisition_id' => $requisition->id,
                        ]);
                    }
                }
            } else {
                $newBranchProduct = Branch_Product::create([
                    'branch_id' => $branchId,
                    'product_id' => $productId,
                    'stock' => $stockLevel,
                ]);

                $headofficeLog = BranchHeadofficeLog::where('branch_id', $branchId)
                                                ->where('requisition_id', $requisition->id)
                                                ->where('product_id', $productId)
                                                ->first();

                if ($headofficeLog) {
                    $priceQuantityData = json_decode($headofficeLog->price_quantity, true);
                    $userId = $headofficeLog->user_id;

                    $stockinDetails = [];

                    foreach ($priceQuantityData as $pqData) {
                        $stockinDetails[] = [
                            'req' => $requisition->id,
                            'qty' => $pqData['quantity'],
                            'prc' => $pqData['price'],
                            'date' => now()->format('d-m-Y'),
                            'usr' => $userId
                        ];
                    }

                    $newBranchProduct->details_stockin = json_encode($stockinDetails);

                    // NEW: Remain Details Logic
                    $remainDetails = [];

                    foreach ($priceQuantityData as $pqData) {
                        $matched = false;
                        foreach ($remainDetails as &$rd) {
                            if ($rd['prc'] == $pqData['price']) {
                                $rd['qty'] = (string)((float)$rd['qty'] + (float)$pqData['quantity']);
                                $matched = true;
                                break;
                            }
                        }
                        unset($rd);

                        if (!$matched) {
                            $remainDetails[] = [
                                'qty' => (string)$pqData['quantity'],
                                'prc' => $pqData['price']
                            ];
                        }
                    }

                    $newBranchProduct->remain_details = json_encode($remainDetails);
                    $newBranchProduct->save();

                    foreach ($priceQuantityData as $pqData) {
                        ProductLedgerBH::create([
                            'entry_date'     => now()->format('Y-m-d'),
                            'narration'      => $narrationType,
                            'type'           => 'StockIn',
                            'user_id'        => $userId ?? auth()->id(),
                            'branch_id'      => $branchId,
                            'product_id'     => $productId,
                            'quantity'       => $pqData['quantity'],
                            'price'          => $pqData['price'],
                            'requisition_id' => $requisition->id,
                        ]);
                    }
                }
            }

            $item->update(['stock_status' => 1]);  
        }

        $allStocked = $requisitionItems->every(function ($item) {
            return $item->stock_status == 1;
        });

        $requisition->update([
            'status' => 3,
            'partial_stock' => $allStocked ? 1 : 0,
        ]);

        if ($validItems->isNotEmpty()) {
            Toastr::success('Stock updated successfully for valid items.', 'Success');
        }

        return redirect()->route('completed.order.list');
    }







    public function view($id)
    {
        $requisitionheading = Requisition::with('user')->find($id);
        $requisitionlist = RequisitionItem::where('requisition_id', $id)->get();

        $productNames = $requisitionlist->pluck('single_product_name');

        $productIds = Product::whereIn('id', $productNames)->pluck('name', 'id')->toArray();

        return view('requisition.view', compact('requisitionheading', 'requisitionlist', 'productIds'));
    }



    // public function edit($id)
    // {
    //     $requisitionheading = Requisition::findOrFail($id);
    //     $requisitionlist = RequisitionItem::where('requisition_id', $id)->get();
    //     $products = Product::all();
    //     $branches = Branch::pluck('name', 'id');
    
    //     return view('requisition.edit', compact('requisitionheading', 'requisitionlist', 'products', 'branches'));
    // }


    public function edit($id)
    {
        $requisitionheading = Requisition::findOrFail($id);
        $requisitionlist = RequisitionItem::where('requisition_id', $id)->get();
        $products = Product::all();
        $projects = Project::all(); 

        $user = auth()->user();
    
        $userBranch = $user->branch->type; 
        
        if ($userBranch == 'Branch') {
            $branches = Branch::where('id', $user->branch_id)->pluck('name', 'id');
        }
   
        elseif ($userBranch == 'Headoffice' || $userBranch == 'Warehouse') {
            $branches = Branch::pluck('name', 'id');
        } else {
            $branches = Branch::where('id', $user->branch_id)->pluck('name', 'id');
        }
    
        return view('requisition.edit', compact('requisitionheading', 'requisitionlist', 'products', 'branches', 'userBranch', 'user', 'projects'));
    }
    














    // public function store(Request $request)
    // {

    //     // dd($request->all());

    //     $request->validate([
    //         'branch_id' => 'required',
    //         'project_name' => 'required',
    //         'date_from' => 'required',
    //         'items.*.name' => 'required',
    //         'items.*.description' => 'required',
    //         'items.*.price' => 'required|numeric|min:0',
    //         'items.*.amount' => 'required|integer|min:1',
    //     ]);
    
    //     $user_id = auth()->user()->id;
    
    //     $requisition = Requisition::create([
    //         'branch_id' => $request->branch_id,
    //         'project_name' => $request->project_name,
    //         'date_from' => $request->date_from,
    //         'user_id' => $user_id,
    //     ]);
    
    //     foreach ($request->items as $item) {

    //         $product = Product::find($item['name']);
    
    //         RequisitionItem::create([
    //             'requisition_id' => $requisition->id,
    //             'product_id' => $product->id,
    //             'single_product_name' => $product->name,
    //             'product_description' => $item['description'],
    //             'price' => $item['price'],
    //             'demand_amount' => $item['amount'],
    //             'total_price' => $item['price'] * $item['amount'],

    //             'comment' => $item['comment'],
    //         ]);
    //     }
    
    //     Toastr::success('Requisition successfully created.', 'Success');
    //     return redirect()->route('requisition.list');
    // }


    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'branch_id' => 'required',
    //         'project_name' => 'required',
    //         'date_from' => 'required|date_format:d/m/Y',
    //         'items.*.name' => 'required',
    //         'items.*.description' => 'required',
    //         'items.*.price' => 'required|numeric|min:0',
    //         'items.*.amount' => 'required|integer|min:1',
    //     ]);

    //     $user_id = auth()->user()->id;
    //     $formattedDate = Carbon::createFromFormat('d/m/Y', $request->date_from)->format('d/m/Y');

    //     $requisition = Requisition::create([
    //         'branch_id' => $request->branch_id,
    //         'project_name' => $request->project_name,
    //         'date_from' => $formattedDate,
    //         'user_id' => $user_id,
    //     ]);

    //     foreach ($request->items as $item) {
    //         $product = Product::find($item['name']);

    //         RequisitionItem::create([
    //             'requisition_id' => $requisition->id,
    //             'product_id' => $product->id,
    //             'single_product_name' => $product->name,
    //             'product_description' => $item['description'],
    //             'price' => $item['price'],
    //             'demand_amount' => $item['amount'],
    //             'total_price' => $item['price'] * $item['amount'],
    //             'comment' => $item['comment'],
    //         ]);
    //     }

    //     Toastr::success('Requisition successfully created.', 'Success');
    //     return redirect()->route('requisition.list');
    // }

    


    public function store(Request $request)
    {
        $request->validate([
            'branch_id' => 'required',
            'project_id' => 'required',
            'date_from' => 'required|date_format:d/m/Y',
            'items.*.name' => 'required',
            'items.*.description' => 'required',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.amount' => 'required|integer|min:1',
        ]);
    
        $user_id = auth()->user()->id;
    
        // Format date to store as dd/mm/yyyy
        $formattedDate = Carbon::createFromFormat('d/m/Y', $request->date_from)->format('d/m/Y');
    
        $requisition = Requisition::create([
            'branch_id' => $request->branch_id,
            'project_id' => $request->project_id,
            'date_from' => $formattedDate,
            'user_id' => $user_id,
        ]);
    
        foreach ($request->items as $item) {
            $product = Product::find($item['name']);
    
            RequisitionItem::create([
                'requisition_id' => $requisition->id,
                'product_id' => $product->id,
                'single_product_name' => $product->name,
                'product_description' => $item['description'],
                'price' => $item['price'],
                'demand_amount' => $item['amount'],
                'total_price' => $item['price'] * $item['amount'],
                'comment' => $item['comment'],
            ]);
        }
    
        Toastr::success('Requisition successfully created.', 'Success');
        return redirect()->route('requisition.list');
    }
    












    public function update(Request $request, $id)
    {
        $requisitionheading = Requisition::find($id);

        $request->validate([
            'branch_id' => 'required',
            'project_id' => 'required',
            'date_from' => 'required|date_format:d/m/Y',
            'items.*.name' => 'required',
            'items.*.description' => 'required',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.amount' => 'required|integer|min:1',
        ]);

        $formattedDate = Carbon::createFromFormat('d/m/Y', $request->date_from)->format('d/m/Y');

        $requisitionheading->update([
            'branch_id' => $request->branch_id,
            'project_id' => $request->project_id,
            'date_from' => $formattedDate,
        ]);

        $existingItemIds = [];

        foreach ($request->items as $item) {

            $product = Product::find($item['name']);

            $totalPrice = $item['price'] * $item['amount'];

            if (isset($item['id'])) {
                $requisitionItem = RequisitionItem::find($item['id']);
                if (!$requisitionItem) {
                    continue;
                }
            } else {
                $requisitionItem = new RequisitionItem([
                    'requisition_id' => $requisitionheading->id,
                ]);
            }

            $requisitionItem->fill([
                'product_id' => $product->id,
                'product_description' => $item['description'],
                'single_product_name' => $product['name'],
                'price' => $item['price'],
                'demand_amount' => $item['amount'],
                'total_price' => $totalPrice,
                'comment' => $item['comment'],
            ]);

            $requisitionItem->save();
            $existingItemIds[] = $requisitionItem->id;
        }

        RequisitionItem::where('requisition_id', $requisitionheading->id)
            ->whereNotIn('id', $existingItemIds)
            ->delete();

        Toastr::success('Requisition updated successfully.', 'Success');
        return redirect()->route('requisition.list');
    }








    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'branch_id' => 'required',
    //         'project_name' => 'required',
    //         'date_from' => 'required|date_format:d/m/Y',
    //         'items.*.name' => 'required',
    //         'items.*.description' => 'required',
    //         'items.*.price' => 'required|numeric|min:0',
    //         'items.*.amount' => 'required|integer|min:1',
    //     ]);
    
    //     $formattedDate = Carbon::createFromFormat('d/m/Y', $request->date_from)->format('d/m/Y');
    
    //     $requisition = Requisition::findOrFail($id);
    //     $requisition->update([
    //         'branch_id' => $request->branch_id,
    //         'project_name' => $request->project_name,
    //         'date_from' => $formattedDate,
    //         'user_id' => auth()->user()->id,
    //     ]);
    
    //     // Remove existing items to replace with updated ones
    //     $requisition->items()->delete();
    
    //     foreach ($request->items as $item) {
    //         $product = Product::find($item['name']);
    
    //         RequisitionItem::create([
    //             'requisition_id' => $requisition->id,
    //             'product_id' => $product->id,
    //             'single_product_name' => $product->name,
    //             'product_description' => $item['description'],
    //             'price' => $item['price'],
    //             'demand_amount' => $item['amount'],
    //             'total_price' => $item['price'] * $item['amount'],
    //             'comment' => $item['comment'],
    //         ]);
    //     }
    
    //     Toastr::success('Requisition successfully updated.', 'Success');
    //     return redirect()->route('requisition.list');
    // }
    
















    public function delete($id)
    {
        $requisitionheading = Requisition::find($id);
        if ($requisitionheading) {
            $requisitionheading->delete();
            RequisitionItem::where('requisition_id', $id)->delete();
            Toastr::success('Requisition deleted successfully.', 'Success');
        } else {
            Toastr::error('Requisition not found.', 'Error');
        }
        return redirect()->route('requisition.list');
    }
    


    public function approve()
    {
        $roles = Role::all();
        $approvals = Approval::with('role')->get();

        return view('requisition.approve', compact('roles', 'approvals'));
    }

    public function Approvestore(Request $request)
    {
        $request->validate([
            'role_id' => 'required',
        ]);

        $module = 'requisition';

        $existingApproval = Approval::where('module', $module)
            ->where('role_id', $request->role_id)
            ->first();

        if ($existingApproval) {
            Toastr::error('Approval for this role already exists.', 'Error');
            return redirect()->route('requisition.approve');
        }

        $maxOrder = Approval::where('module', $module)->max('order');
        $order = ($maxOrder !== null) ? $maxOrder + 1 : 1;

        Approval::create([
            'module' => $module,
            'role_id' => $request->role_id,
            'order' => $order,
        ]);

        Toastr::success('Approval successfully created.', 'Success');
        return redirect()->route('requisition.approve');
    }


    public function Approvedelete($id)
    {
        $approval = Approval::find($id);

        if (!$approval) {

            return redirect()->route('requisition.approve')->with('error', 'Approval record not found.');
        }

        $approval->delete();

        return redirect()->route('requisition.approve')->with('success', 'Approval record deleted successfully.');
    }


    public function approveRequisition($id)
    {
        $userId=auth()->user()->id;
        $roleId = DB::table('model_has_roles')->where('model_id', '=', $userId)->first()->role_id;

        ApprovalStatus::create([
            "module" => "requisition",
            "module_id" => $id,
            "user_id" => $userId,
            "role_id" => $roleId,
            "status" => 1
        ]);
        Toastr::success('Requisition Approved.', 'Success');
        return redirect()->route('requisition.list');
    }

    public function rejectRequisition($id)
    {
        $userId=auth()->user()->id;
        $roleId = DB::table('model_has_roles')->where('model_id', '=', $userId)->first()->role_id;

        ApprovalStatus::create([
            "module" => "requisition",
            "module_id" => $id,
            "user_id" => $userId,
            "role_id" => $roleId,
            "status" => 0
        ]);
        Toastr::success('Requisition Rejected.', 'Success');
        return redirect()->route('requisition.list');
    }






    // public function purchase($id)
    // {
    //     $requisition = Requisition::findOrFail($id);
    //     $requisition->update(['status' => 2]);
    
    //     Toastr::success('Purchase updated successfully.', 'Success');
    //     return redirect()->route('requisition.list');
    // }

    // public function purchase($id)
    // {
    //     $requisition = Requisition::findOrFail($id);
    //     $requisition->update(['status' => 4]);
    
    //     Toastr::success('Purchase updated successfully.', 'Success');
    //     return redirect()->route('requisition.list');
    // }


    //purchase for othres by warehopuseb


    // public function purchase($id)
    // {
    //     // dd($id);
        
    //     $requisition = Requisition::findOrFail($id);
    //     $requisition->update(['status' => 4]);
    
    //     Toastr::success('Send for purchase successfully.', 'Success');
    //     return redirect()->route('order.list');
    // }  



    // public function purchase($id)
    // {
    //     // Find the requisition and update its status
    //     $requisition = Requisition::findOrFail($id);
    //     $requisition->update(['status' => 4]);
    
    //     // Retrieve selected product IDs from the query parameter
    //     $selectedProductIds = request()->query('items', []);
    //     $selectedProductIdsArray = explode(',', $selectedProductIds);
    
    //     // Update only the requisition items for the given requisition ID and selected product IDs
    //     $requisitionItems = RequisitionItem::where('requisition_id', $requisition->id)
    //         ->whereIn('product_id', $selectedProductIdsArray)
    //         ->get();
    
    //     foreach ($requisitionItems as $item) {
    //         $item->update(['purchase' => 1]);
    //     }
    
    //     Toastr::success('Send for purchase successfully.', 'Success');
    //     return redirect()->route('order.list');
    // }
    



    //done without partial purchase 

    // public function purchase($id)
    // {
    //     $requisition = Requisition::findOrFail($id);
    //     $requisition->update(['status' => 4]);
        
    //     $selectedProductIds = request()->query('items', []);
        
    //     if (empty($selectedProductIds)) {
    //         Toastr::error('No items selected for purchase.', 'Error');
    //         return redirect()->route('order.list');
    //     }
    
    //     $selectedProductIdsArray = explode(',', $selectedProductIds);
        
    //     $requisitionItems = RequisitionItem::where('requisition_id', $requisition->id)
    //         ->whereIn('product_id', $selectedProductIdsArray)
    //         ->get();
        
    //     foreach ($requisitionItems as $item) {
    //         $item->update(['purchase' => 1]);
    //     }
        
    //     Toastr::success('Items sent for purchase successfully.', 'Success');
    //     return redirect()->route('order.list');
    // }
    





    public function purchase($id)
    {
        $requisition = Requisition::findOrFail($id);
        
        $selectedProductIds = request()->query('items', []);
        
        if (empty($selectedProductIds)) {
            Toastr::error('No items selected for purchase.', 'Error');
            return redirect()->route('order.list');
        }
        
        $selectedProductIdsArray = explode(',', $selectedProductIds);
        
        $requisitionItems = RequisitionItem::where('requisition_id', $requisition->id)
            ->whereIn('product_id', $selectedProductIdsArray)
            ->get();
        
        foreach ($requisitionItems as $item) {
            $item->update(['purchase' => 1]);
        }
        
        // Check if all items with the requisition_id have been purchased
        $allPurchased = RequisitionItem::where('requisition_id', $requisition->id)
            ->where('purchase', 0)
            ->doesntExist();
        
        $requisition->update([
            'status' => 4,
            'pending_purchase_status' => 0,
            'partial_purchase' => $allPurchased ? 1 : 0,
        ]);
        
        Toastr::success('Items sent for purchase successfully.', 'Success');
        return redirect()->route('order.list');
    }
    



















    // public function purchaseCheck($id)
    // {
    //     $requisition = Requisition::findOrFail($id);
    //     $requisitionItems = RequisitionItem::where('requisition_id', $requisition->id)
    //         ->where('delivery', '!=', 1) 
    //         ->where('reject', '!=', 1)  
    //         ->get();

    //     $allDetails = [];

    //     foreach ($requisitionItems as $item) {
    //         $productId = $item->product_id;
    //         $demandAmount = $item->demand_amount;

    //         $product = Product::find($productId);
    //         $productName = $product ? $product->name : "Unknown Product";

    //         $warehouseBranchProduct = Branch_Product::whereHas('branch', function ($query) {
    //                 $query->where('type', 'Warehouse');
    //             })
    //             ->where('product_id', $productId)
    //             ->first();

    //         $stock = $warehouseBranchProduct ? $warehouseBranchProduct->stock : 0;

    //         $allDetails[] = [
    //             'product_id' => $productId,
    //             'product_name' => $productName,
    //             'stock' => $stock,
    //             'demand_amount' => $demandAmount,
    //             'is_insufficient' => $demandAmount > $stock,
    //         ];
    //     }

    //     session()->flash('allDetails', $allDetails);
    //     session()->flash('showModal', true);

    //     return redirect()->back();
    // }



    //done
    // public function purchaseCheck($id)
    // {
    //     $requisition = Requisition::findOrFail($id);
    //     $requisitionItems = RequisitionItem::where('requisition_id', $requisition->id)
    //         ->where('delivery', '!=', 1)
    //         ->where('reject', '!=', 1)
    //         ->get();

    //     $allDetails = [];

    //     foreach ($requisitionItems as $item) {
    //         $productId = $item->product_id;
    //         $demandAmount = $item->demand_amount;

    //         $product = Product::find($productId);
    //         $productName = $product ? $product->name : "Unknown Product";

    //         $warehouseBranchProduct = Branch_Product::whereHas('branch', function ($query) {
    //                 $query->where('type', 'Warehouse');
    //             })
    //             ->where('product_id', $productId)
    //             ->first();

    //         $stock = $warehouseBranchProduct ? $warehouseBranchProduct->stock : 0;

    //         $allDetails[] = [
    //             'req_id' => $requisition->id,
    //             'product_id' => $productId,
    //             'product_name' => $productName,
    //             'stock' => $stock,
    //             'demand_amount' => $demandAmount,
    //             'is_insufficient' => $demandAmount > $stock,
    //             'purchase' => $item->purchase ?? 0,
    //         ];
    //     }

    //     session()->flash('allDetails', $allDetails);
    //     session()->flash('showPurchaseModal', true);

    //     return redirect()->back();
    // }

    


    //done and working before multiple price and batch wise product whole sum stock
    // public function purchaseCheck($id)
    // {
    //     $requisition = Requisition::findOrFail($id);
    //     $requisitionItems = RequisitionItem::where('requisition_id', $requisition->id)
    //         ->where('delivery', '!=', 1)
    //         ->where('reject', '!=', 1)
    //         ->get();
    
    //     $allDetails = [];
    //     $allInPurchaseProgress = true; 
    
    //     foreach ($requisitionItems as $item) {
    //         $productId = $item->product_id;
    //         $demandAmount = $item->demand_amount;
    
    //         $product = Product::find($productId);
    //         $productName = $product ? $product->name : "Unknown Product";
    
    //         $warehouseBranchProduct = Branch_Product::whereHas('branch', function ($query) {
    //                 $query->where('type', 'Warehouse');
    //             })
    //             ->where('product_id', $productId)
    //             ->first();
    
    //         $stock = $warehouseBranchProduct ? $warehouseBranchProduct->stock : 0;
    
    //         $isInPurchaseProgress = $item->purchase == 1;
    //         if (!$isInPurchaseProgress) {
    //             $allInPurchaseProgress = false; 
    //         }
    
    //         $allDetails[] = [
    //             'req_id' => $requisition->id,
    //             'product_id' => $productId,
    //             'product_name' => $productName,
    //             'stock' => $stock,
    //             'demand_amount' => $demandAmount,
    //             'is_insufficient' => $demandAmount > $stock,
    //             'purchase' => $item->purchase ?? 0,
    //         ];
    //     }
    
    //     session()->flash('allDetails', $allDetails);
    //     session()->flash('showPurchaseModal', true);
    //     session()->flash('allInPurchaseProgress', $allInPurchaseProgress);
    
    //     return redirect()->back();
    // }  




    public function purchaseCheck($id)
    {
        $requisition = Requisition::findOrFail($id);
        $requisitionItems = RequisitionItem::where('requisition_id', $requisition->id)
            ->where('delivery', '!=', 1)
            ->where('reject', '!=', 1)
            ->get();
    
        $allDetails = [];
        $allInPurchaseProgress = true; 
    
        foreach ($requisitionItems as $item) {
            $productId = $item->product_id;
            $demandAmount = $item->demand_amount;
    
            $product = Product::find($productId);
            $productName = $product ? $product->name : "Unknown Product"; 

            $warehouseStocks = Branch_Product::whereHas('branch', function ($query) {
                    $query->where('type', 'Warehouse');
                })
                ->where('product_id', $productId)
                ->get()
                ->groupBy('product_id');

            $totalStock = 0;
            $batchDetails = [];

            if ($warehouseStocks->isNotEmpty()) {
                foreach ($warehouseStocks as $batches) {
                    foreach ($batches as $batch) {
                        $totalStock += $batch->stock;
                        $batchDetails[] = "(Batch: {$batch->batch}, {$batch->stock})";
                    }
                }
            }

            $isInPurchaseProgress = $item->purchase == 1;
            if (!$isInPurchaseProgress) {
                $allInPurchaseProgress = false; 
            }
    
            $allDetails[] = [
                'req_id' => $requisition->id,
                'product_id' => $productId,
                'product_name' => $productName,
                'stock' => $totalStock,
                'demand_amount' => $demandAmount,
                'is_insufficient' => $demandAmount > $totalStock,
                'purchase' => $item->purchase ?? 0,
            ];
        }
    
        session()->flash('allDetails', $allDetails);
        session()->flash('showPurchaseModal', true);
        session()->flash('allInPurchaseProgress', $allInPurchaseProgress);
    
        return redirect()->back(); 
        
    }
    











    

    //purchase own for warehouse 

    // public function purchaseownWarehouse($id)
    // {
    //     $requisition = Requisition::findOrFail($id);
    //     $requisition->update(['status' => 4]);
    
    //     Toastr::success('Send for purchase successfully.', 'Success');
    //     return redirect()->route('requisition.list');
    // }



    public function purchaseownWarehouse($id)
    {
        $requisition = Requisition::findOrFail($id);
    
        // Update all items under this requisition to 'purchased'
        $requisitionItems = RequisitionItem::where('requisition_id', $requisition->id)->get();
    
        foreach ($requisitionItems as $item) {
            $item->update(['purchase' => 1]);
        }
    
        // Check if all items are purchased
        $allPurchased = RequisitionItem::where('requisition_id', $requisition->id)
            ->where('purchase', 0)
            ->doesntExist();
    
        // Update requisition status
        $requisition->update([
            'status' => 4,
            'pending_purchase_status' => 0,
            'partial_purchase' => $allPurchased ? 1 : 0,
        ]);
    
        Toastr::success('All items sent for purchase successfully.', 'Success');
        return redirect()->route('requisition.list');
    }
    




    public function purchaseCollectionlist()
    {
        $requisitions = Requisition::where('purchase_approve', 1)->get();
    
        return view('requisition.purchasecollectionlist', compact('requisitions'));
    }


    public function purchaseCollectionView($id)
    {
        $requisitionheading = Requisition::where('purchase_approve', 1)->find($id);

        $requisitionlist = RequisitionItem::where('requisition_id', $id)
        ->where('purchase', 2)
        ->get();

        $productNames = $requisitionlist->pluck('single_product_name');
        $productIds = Product::whereIn('id', $productNames)->pluck('name', 'id')->toArray();

        return view('requisition.purchasecollectionview', compact('requisitionheading', 'requisitionlist', 'productIds'));
    }  
    

    // public function purchaseCollectionView($id)
    // {
    //     $requisitionheading = Requisition::where('purchase_approve', 1)->find($id);
    //     $requisitionlist = RequisitionItem::where('requisition_id', $id)->get();
    //     $productIds = $requisitionlist->pluck('product_id');
    //     $productNames = Product::whereIn('id', $productIds)->pluck('name', 'id')->toArray();
    
    //     return view('requisition.purchasecollectionview', compact('requisitionheading', 'requisitionlist', 'productNames'));
    // }
    



    



    
    // public function pendingPurchase()
    // {
    //     $requisitions = Requisition::where('status', 4)->get();

    //     return view('requisition.pendingpurchaselist', compact('requisitions'));
    // }


    // public function pendingPurchase()
    // {
       
    //     // $requisitions = Requisition::where('status', 4)->get();
    //     // $requisitions = Requisition::whereIn('status', [4, 7])->get();

    //     $requisitions = Requisition::whereIn('status', [4, 7])->orderBy('id', 'desc')->get();

    
    //     return view('requisition.pendingpurchaselist', compact('requisitions'));
    // }  


    // public function pendingPurchase()
    // {
    //     $requisitions = Requisition::whereIn('status', [4, 7])
    //         ->orWhere(function ($query) {
    //             $query->where('status', 3)
    //                   ->where('partial_purchase', 0)
    //                   ->where('partial_delivery', 0)
    //                   ->where('partial_stock', 0);
    //         })
    //         ->orderBy('id', 'desc')
    //         ->get();
    
    //     return view('requisition.pendingpurchaselist', compact('requisitions'));
    // }
    
    


    // public function pendingPurchase()
    // {
    //     $requisitions = Requisition::whereIn('status', [4, 7])
    //         ->orWhere(function ($query) {
    //             $query->where('status', 3)
    //                   ->where('partial_purchase', 0)
    //                   ->where('partial_delivery', 0)
    //                   ->where('partial_stock', 0);
    //         })
    //         ->orWhere(function ($query) {
    //             $query->where('status', 1)
    //                   ->where('partial_delivery', 0)
    //                   ->where('partial_reject', 0)
    //                   ->where('partial_purchase', 0);
    //         })
    //         ->orderBy('id', 'desc')
    //         ->get();
    
    //     return view('requisition.pendingpurchaselist', compact('requisitions'));
    // }
    




    // public function pendingPurchase()
    // {
    //     $requisitions = Requisition::whereIn('pending_purchase_status', [0, 3, 4])->orderBy('id', 'desc')->get();

    //     return view('requisition.pendingpurchaselist', compact('requisitions'));
    // }



    public function pendingPurchase()
    {
        $requisitions = Requisition::whereIn('pending_purchase_status', [0, 3, 4])
            ->orWhere(function ($query) {
                $query->where('pending_purchase_status', 5)
                      ->where('pending_approval_status_headoffice', 0);
            })
            ->orderBy('id', 'desc')
            ->get(); 

        $paymentMethods = ChartOfAccount::whereIn('id', [54, 55, 58])->get();
    
        return view('requisition.pendingpurchaselist', compact('requisitions', 'paymentMethods'));
    }
    



    
    // public function pendingPurchaseView($id)
    // {
    //     $requisitionheading = Requisition::findorFail($id);
    //     $requisitionlist = RequisitionItem::where('requisition_id', $id)->get();

    //     return view('requisition.pendingpurchaseview', compact('requisitionheading', 'requisitionlist'));
    // }  


    public function pendingPurchaseView($id)
    {
        $requisitionheading = Requisition::findOrFail($id);

        $requisitionlist = RequisitionItem::where('requisition_id', $id)
            ->where('purchase', 1)
            ->get();

        return view('requisition.pendingpurchaseview', compact('requisitionheading', 'requisitionlist'));
    }






    public function uploadDocument(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'document' => 'required|file|mimes:pdf,doc,docx,jpeg,jpg,png', 
            'document' => 'required|file|mimes:pdf,doc,docx,jpeg,jpg,png,xlsx,xls',
            'requisition_id' => 'required|exists:requisitions,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // $filePath = $request->file('document')->store('public/document');


        if ($request->hasFile('document')) { 
            $file = $request->file('document');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/document', $filename);
        }


        $requisition = Requisition::find($request->requisition_id);
        $requisition->document = $filename;
        $requisition->save();

        Toastr::success('Document uploaded successfully!');
        
        return redirect()->back();

    }










    // public function pendingPurchaseApprove($id)
    // {
    //     $requisition = Requisition::findOrFail($id);
    //     $requisition->update(['status' => 2]);
    
    //     Toastr::success('Approve successfully.', 'Success');
    //     return redirect()->route('pending.purcahse.requisition');
    // }


    // public function pendingPurchaseApprove($id)
    // {
    //     $requisition = Requisition::findOrFail($id);
    //     $requisition->update([
    //         'status' => 2,
    //         'purchase_approve' => 1,
    //     ]);
    
    //     Toastr::success('Approved successfully.', 'Success');
    //     return redirect()->route('pending.purcahse.requisition');
    // }
    


    //oky
    // public function pendingPurchaseApproveCheck($id)
    // {

    //     $requisition = Requisition::findOrFail($id);
    //     $requisitionItems = RequisitionItem::where('requisition_id', $requisition->id)
    //         ->where('purchase', '=', 1) 
    //         ->get();

    //     $allDetails = [];

    //     foreach ($requisitionItems as $item) {
    //         $productId = $item->product_id;
    //         $demandAmount = $item->demand_amount;

    //         $product = Product::find($productId);
    //         $productName = $product ? $product->name : "Unknown Product";

    //         $warehouseBranchProduct = Branch_Product::whereHas('branch', function ($query) {
    //                 $query->where('type', 'Warehouse');
    //             })
    //             ->where('product_id', $productId)
    //             ->first();

    //         $stock = $warehouseBranchProduct ? $warehouseBranchProduct->stock : 0;

    //         $allDetails[] = [
    //             'ppreq_id' => $requisition->id,
    //             'product_id' => $productId,
    //             'product_name' => $productName,
    //             'stock' => $stock,
    //             'demand_amount' => $demandAmount,
    //             'is_insufficient' => $demandAmount > $stock,
    //         ];
    //     }

    //     session()->flash('allDetailsdelivery', $allDetails);
    //     session()->flash('showModal', true);

    //     return redirect()->back();

    // }



       //Last date 04-25-2025
      //warehouse demand all not subtract
    // public function pendingPurchaseApproveCheck($id)
    // {
    //     $requisition = Requisition::findOrFail($id);
    //     $requisitionItems = RequisitionItem::where('requisition_id', $requisition->id)
    //         ->where('purchase', '=', 1)
    //         ->get();
    
    //     $allDetails = [];
    
    //     foreach ($requisitionItems as $item) {
    //         $productId = $item->product_id;
    //         $demandAmount = $item->demand_amount;
    
    //         $product = Product::find($productId);
    //         $productName = $product ? $product->name : "Unknown Product";

    
    //         // $warehouseBranchProduct = Branch_Product::whereHas('branch', function ($query) {
    //         //         $query->where('type', 'Warehouse');
    //         //     })
    //         //     ->where('product_id', $productId)
    //         //     ->first();
    
    //         // $stock = $warehouseBranchProduct ? $warehouseBranchProduct->stock : 0;


    //         $warehouseStocks = Branch_Product::whereHas('branch', function ($query) {
    //                 $query->where('type', 'Warehouse');
    //             })
    //             ->where('product_id', $productId)
    //             ->get()
    //             ->groupBy('product_id');

    //         $totalStock = 0;
    //         $batchDetails = [];

    //         if ($warehouseStocks->isNotEmpty()) {
    //             foreach ($warehouseStocks as $batches) {
    //                 foreach ($batches as $batch) {
    //                     $totalStock += $batch->stock;
    //                     $batchDetails[] = "(Batch: {$batch->batch}, {$batch->stock})";
    //                 }
    //             }
    //         }

    //         $headofficeApproval = $item->headoffice_approval;
    
    //         $allDetails[] = [
    //             'ppreq_id' => $requisition->id,
    //             'product_id' => $productId,
    //             'product_name' => $productName,
    //             'stock' => $totalStock,
    //             'demand_amount' => $demandAmount,
    //             'is_insufficient' => $demandAmount > $totalStock,
    //             'headoffice_approval' => $headofficeApproval,
    //             'status' => $headofficeApproval ? 'Pending Approval' : '',
    //         ];
    //     }
    
    //     session()->flash('allDetailsdelivery', $allDetails);
    //     session()->flash('showModal', true);
    
    //     return redirect()->back();
    // }

    
    public function pendingPurchaseApproveCheck($id)
    {
        $requisition = Requisition::findOrFail($id);
        $requisitionItems = RequisitionItem::where('requisition_id', $requisition->id)
            ->where('purchase', '=', 1)
            ->get();
    
        $allDetails = [];
    
        // Check if this requisition is from a warehouse branch
        $isWarehouseRequisition = $requisition->branch->type === 'Warehouse';
    
        foreach ($requisitionItems as $item) {
            $productId = $item->product_id;
            $demandAmount = $item->demand_amount;
    
            $product = Product::find($productId);
            $productName = $product ? $product->name : "Unknown Product";
    
            $warehouseStocks = Branch_Product::whereHas('branch', function ($query) {
                    $query->where('type', 'Warehouse');
                })
                ->where('product_id', $productId)
                ->get()
                ->groupBy('product_id');
    
            $totalStock = 0;
            $batchDetails = [];
    
            if ($warehouseStocks->isNotEmpty()) {
                foreach ($warehouseStocks as $batches) {
                    foreach ($batches as $batch) {
                        $totalStock += $batch->stock;
                        $batchDetails[] = "(Batch: {$batch->batch}, {$batch->stock})";
                    }
                }
            }
    
            $headofficeApproval = $item->headoffice_approval;
    
            // For warehouse requisitions, show full demand amount regardless of stock
            $displayDemandAmount = $isWarehouseRequisition ? $demandAmount : $demandAmount;
    
            $allDetails[] = [
                'ppreq_id' => $requisition->id,
                'product_id' => $productId,
                'product_name' => $productName,
                'stock' => $totalStock,
                'demand_amount' => $displayDemandAmount,
                'is_insufficient' => $isWarehouseRequisition ? false : ($demandAmount > $totalStock),
                'headoffice_approval' => $headofficeApproval,
                'status' => $headofficeApproval ? 'Pending Approval' : '',
            ];
        }
    
        session()->flash('allDetailsdelivery', $allDetails);
        session()->flash('showModal', true);
    
        return redirect()->back();
    }












    // public function pendingPurchaseApprove($id)
    // {
    //     $selectedProducts = json_decode(request('selected_products'), true);
    
    //     if (!$selectedProducts || !is_array($selectedProducts)) {
    //         Toastr::error('No products selected.', 'Error');
    //         return redirect()->back();
    //     }
    
    //     $requisition = Requisition::findOrFail($id);
    //     $requisition->update([
    //         'status' => 2,
    //         'purchase_approve' => 1,
    //         'pending_purchase_status' => 1,
    //     ]);
    
    //     $requisitionItems = RequisitionItem::where('requisition_id', $id)
    //         ->where('purchase', 1)
    //         ->whereIn('product_id', $selectedProducts)
    //         ->get();
    
    //     foreach ($requisitionItems as $item) {
    //         $productId = $item->product_id;
    //         $stockLevel = $item->demand_amount;
    
    //         $warehouseBranchProduct = Branch_Product::whereHas('branch', function ($query) {
    //                 $query->where('type', 'Warehouse');
    //             })
    //             ->where('product_id', $productId)
    //             ->first();
    
    //         if ($warehouseBranchProduct) {
    //             $warehouseBranchProduct->stock += $stockLevel;
    //             $warehouseBranchProduct->save();
    //         }
    
    //         $item->update(['purchase' => 2]);
    //     }
    
    //     Toastr::success('Approved successfully.', 'Success');
    //     return redirect()->route('pending.purcahse.requisition');
    // }
    


    //done and work before batch price 
    // public function pendingPurchaseApprove($id)
    // {
    //     $selectedProducts = json_decode(request('selected_products'), true);
    
    //     if (!$selectedProducts || !is_array($selectedProducts)) {
    //         Toastr::error('No products selected.', 'Error');
    //         return redirect()->back();
    //     }
    
    //     $requisition = Requisition::findOrFail($id);
    
    //     $allRequisitionItems = RequisitionItem::where('requisition_id', $id)
    //         ->where('purchase', 1)
    //         ->pluck('product_id')
    //         ->toArray();
    
    //     $isAllApproved = empty(array_diff($allRequisitionItems, $selectedProducts));
    
    //     $requisition->update([
    //         'status' => 2,
    //         'purchase_approve' => 1,
    //         'pending_purchase_status' => $isAllApproved ? 1 : 0,
    //     ]);

    //     $requisitionItems = RequisitionItem::where('requisition_id', $id)
    //         ->where('purchase', 1)
    //         ->whereIn('product_id', $selectedProducts)
    //         ->get();
    
    //     foreach ($requisitionItems as $item) {
    //         $productId = $item->product_id;
    //         $stockLevel = $item->demand_amount;
    
    //         $warehouseBranchProduct = Branch_Product::whereHas('branch', function ($query) {
    //                 $query->where('type', 'Warehouse');
    //             })
    //             ->where('product_id', $productId)
    //             ->first();
    
    //         if ($warehouseBranchProduct) {
    //             $warehouseBranchProduct->stock += $stockLevel;
    //             $warehouseBranchProduct->save();
    //         }
    
    //         $item->update(['purchase' => 2]);
    //     }
    
    //     Toastr::success('Approved successfully.', 'Success');
    //     return redirect()->route('pending.purcahse.requisition');
    // }
    


     //workinmg before demandquantity - warehouse stock wise product
    // public function pendingPurchaseApprove($id)
    // {
    //     $selectedProducts = json_decode(request('selected_products'), true);
    //     $prices = json_decode(request('prices'), true);  
    
    //     if (!$selectedProducts || !is_array($selectedProducts)) {
    //         Toastr::error('No products selected.', 'Error');
    //         return redirect()->back();
    //     }
    
    //     $requisition = Requisition::findOrFail($id);
    
    //     $allRequisitionItems = RequisitionItem::where('requisition_id', $id)
    //         ->where('purchase', 1)
    //         ->pluck('product_id')
    //         ->toArray();
    
    //     $isAllApproved = empty(array_diff($allRequisitionItems, $selectedProducts));
    
    //     $requisition->update([
    //         'status' => 2,
    //         'purchase_approve' => 1,
    //         'pending_purchase_status' => $isAllApproved ? 1 : 0,
    //     ]);
    
    //     $requisitionItems = RequisitionItem::where('requisition_id', $id)
    //         ->where('purchase', 1)
    //         ->whereIn('product_id', $selectedProducts)
    //         ->get();
    
    //     foreach ($requisitionItems as $item) {
    //         $productId = $item->product_id;
    //         $stockLevel = $item->demand_amount;
    
    //         $inputPrice = $prices[$productId] ?? null;
    
    //         if (!$inputPrice) {
    //             Toastr::error('Price is required for product ' . $productId, 'Error');
    //             return redirect()->back();
    //         }
    
    //         $warehouseBranchProduct = Branch_Product::whereHas('branch', function ($query) {
    //                 $query->where('type', 'Warehouse');
    //             })
    //             ->where('product_id', $productId)
    //             ->first();  

                
    //         if ($warehouseBranchProduct) {
    //             if ($warehouseBranchProduct->price == $inputPrice) {
    //                 $existingDetails = $warehouseBranchProduct->details_stockin 
    //                     ? json_decode($warehouseBranchProduct->details_stockin, true) 
    //                     : [];

    //                 if (!is_array($existingDetails)) {
    //                     $existingDetails = [];
    //                 }

    //                 $existingDetails[] = [
    //                     'requisition' => $requisition->id,
    //                     'quantity'    => $stockLevel,
    //                     'date'        => now()->format('d-m-Y'),
    //                     'store_by'    => auth()->id(),
    //                 ];

    //                 $warehouseBranchProduct->stock += $stockLevel;
    //                 $warehouseBranchProduct->details_stockin = json_encode($existingDetails);
    //                 $warehouseBranchProduct->save();




    //                 ProductLedger::create([
    //                     'entry_date' => now()->format('Y-m-d'),
    //                     'narration' => 'StockIn by purchaseteam',
    //                     'type' => 'stockin',
    //                     'user_id' => auth()->id(),
    //                     'branch_id' => $warehouseBranchProduct->branch_id,
    //                     'product_id' => $productId,
    //                     'quantity' => $stockLevel,
    //                     'price' => $inputPrice,
    //                     'batch' => $warehouseBranchProduct->batch,
    //                     'requisition_id' => $requisition->id,
    //                 ]);  






    //             } else {

    //                 $lastBatch = Branch_Product::latest('batch')->value('batch');

    //                 $newBranchProduct = Branch_Product::create([
    //                     'branch_id'  => $warehouseBranchProduct->branch_id,
    //                     'product_id' => $productId,
    //                     'price'      => $inputPrice,
    //                     'stock'      => $stockLevel,
    //                     'batch'      => $lastBatch ? $lastBatch + 1 : '', 

    //                     'details_stockin' => json_encode([
    //                         'requisition' => $requisition->id,
    //                         'quantity'    => $stockLevel,
    //                         'date'        => now()->format('d-m-Y'),
    //                         'store_by'    => auth()->id(),
    //                     ]),
                        
    //                 ]);







    //                 ProductLedger::create([
    //                     'entry_date' => now()->format('Y-m-d'),
    //                     'narration' => 'StockIn by purchaseteam',
    //                     'type' => 'stockin',
    //                     'user_id' => auth()->id(),
    //                     'branch_id'  => $warehouseBranchProduct->branch_id,
    //                     'product_id' => $productId,
    //                     'quantity' => $stockLevel,
    //                     'price' => $inputPrice,
    //                     'batch' => $newBranchProduct->batch,
    //                     'requisition_id' => $requisition->id,
    //                 ]);






    //             }
    //         }
    
    //         $item->update(['purchase' => 2]);
    //     }
    
    //     Toastr::success('Approved successfully.', 'Success');
    //     return redirect()->route('pending.purcahse.requisition'); 
      
    // }
    

    




    // public function pendingPurchaseApprove($id)
    // {
    //     $selectedProducts = json_decode(request('selected_products'), true);
    //     $prices = json_decode(request('prices'), true);  

    //     if (!$selectedProducts || !is_array($selectedProducts)) {
    //         Toastr::error('No products selected.', 'Error');
    //         return redirect()->back();
    //     }

    //     $requisition = Requisition::findOrFail($id);

    //     $allRequisitionItems = RequisitionItem::where('requisition_id', $id)
    //         ->where('purchase', 1)
    //         ->pluck('product_id')
    //         ->toArray();

    //     $isAllApproved = empty(array_diff($allRequisitionItems, $selectedProducts));

    //     $requisition->update([
    //         'status' => 2,
    //         'purchase_approve' => 1,
    //         'pending_purchase_status' => $isAllApproved ? 1 : 0,
    //     ]);

    //     $requisitionItems = RequisitionItem::where('requisition_id', $id)
    //         ->where('purchase', 1)
    //         ->whereIn('product_id', $selectedProducts)
    //         ->get();

    //     $warehouseStockData = [];

    //     foreach ($requisitionItems as $item) {
    //         $productId = $item->product_id;
    //         $demandAmount = $item->demand_amount;
    //         $inputPrice = $prices[$productId] ?? null;

    //         if (!$inputPrice) {
    //             Toastr::error('Price is required for product ' . $productId, 'Error');
    //             return redirect()->back();
    //         }

    //         $warehouseStock = Branch_Product::whereHas('branch', function ($query) {
    //                 $query->where('type', 'Warehouse');
    //             })
    //             ->where('product_id', $productId)
    //             ->sum('stock'); 

    //         $warehouseStockData[$productId] = $warehouseStock;

    //         if ($demandAmount <= $warehouseStock) {
    //             $stockLevel = $demandAmount;
    //         } else {
    //             $stockLevel = $demandAmount - $warehouseStock;
    //         }

    //         $warehouseBranchProduct = Branch_Product::whereHas('branch', function ($query) {
    //                 $query->where('type', 'Warehouse');
    //             })
    //             ->where('product_id', $productId)
    //             ->first();

    //         if ($warehouseBranchProduct) {
    //             if ($warehouseBranchProduct->price == $inputPrice) {
    //                 $existingDetails = $warehouseBranchProduct->details_stockin 
    //                     ? json_decode($warehouseBranchProduct->details_stockin, true) 
    //                     : [];
    //                 if (!is_array($existingDetails)) {
    //                     $existingDetails = [];
    //                 }
    //                 $existingDetails[] = [
    //                     'requisition' => $requisition->id,
    //                     'quantity'    => $stockLevel,
    //                     'date'        => now()->format('d-m-Y'),
    //                     'store_by'    => auth()->id(),
    //                 ];

    //                 $warehouseBranchProduct->stock += $stockLevel;
    //                 $warehouseBranchProduct->details_stockin = json_encode($existingDetails);
    //                 $warehouseBranchProduct->save();

    //                 ProductLedger::create([
    //                     'entry_date'     => now()->format('Y-m-d'),
    //                     'narration'      => 'purchaseteam',
    //                     'type'           => 'stockin',
    //                     'user_id'        => auth()->id(),
    //                     'branch_id'      => $warehouseBranchProduct->branch_id,
    //                     'product_id'     => $productId,
    //                     'quantity'       => $stockLevel,
    //                     'price'          => $inputPrice,
    //                     'batch'          => $warehouseBranchProduct->batch,
    //                     'requisition_id' => $requisition->id,
    //                 ]);
    //             } else {
    //                 $lastBatch = Branch_Product::latest('batch')->value('batch');

    //                 $newBranchProduct = Branch_Product::create([
    //                     'branch_id'  => $warehouseBranchProduct->branch_id,
    //                     'product_id' => $productId,
    //                     'price'      => $inputPrice,
    //                     'stock'      => $stockLevel,
    //                     'batch'      => $lastBatch ? $lastBatch + 1 : '', 

    //                     'details_stockin' => json_encode([
    //                         'requisition' => $requisition->id,
    //                         'quantity'    => $stockLevel,
    //                         'date'        => now()->format('d-m-Y'),
    //                         'store_by'    => auth()->id(),
    //                     ]),
    //                 ]);

    //                 ProductLedger::create([
    //                     'entry_date'     => now()->format('Y-m-d'),
    //                     'narration'      => 'purchaseteam',
    //                     'type'           => 'stockin',
    //                     'user_id'        => auth()->id(),
    //                     'branch_id'      => $warehouseBranchProduct->branch_id,
    //                     'product_id'     => $productId,
    //                     'quantity'       => $stockLevel,
    //                     'price'          => $inputPrice,
    //                     'batch'          => $newBranchProduct->batch,
    //                     'requisition_id' => $requisition->id,
    //                 ]);
    //             }
    //         }

    //         // $item->update(['purchase' => 2]);
    //         // $item->update(['new_price' => $inputPrice]);

    //         $item->update([
    //             'purchase' => 2,
    //             'new_price' => $inputPrice,
    //         ]);
            
    //     }


    //     Toastr::success('Approved successfully.', 'Success');
    //     return redirect()->route('pending.purcahse.requisition'); 
    // }




      //without bank cash due transaction
    // public function pendingPurchaseApprove($id)
    // {
    //     $selectedProducts = json_decode(request('selected_products'), true);
    //     $prices = json_decode(request('prices'), true);  
    //     $paymentMethod = request('payment_method');
    
    //     if (!$selectedProducts || !is_array($selectedProducts)) {
    //         Toastr::error('No products selected.', 'Error');
    //         return redirect()->back();
    //     }
    
    //     // Generate unique invoice number
    //     $invoiceNo = 'INV-' . strtoupper(uniqid());
    
    //     $requisition = Requisition::findOrFail($id);
    
    //     $allRequisitionItems = RequisitionItem::where('requisition_id', $id)
    //         ->where('purchase', 1)
    //         ->pluck('product_id')
    //         ->toArray();
    
    //     $isAllApproved = empty(array_diff($allRequisitionItems, $selectedProducts));
    
    //     $requisition->update([
    //         'status' => 2,
    //         'purchase_approve' => 1,
    //         'pending_purchase_status' => $isAllApproved ? 1 : 0,
    //         'invoice_no' => $invoiceNo,
    //         'payment_method' => $paymentMethod,
    //     ]);
    
    //     $requisitionItems = RequisitionItem::where('requisition_id', $id)
    //         ->where('purchase', 1)
    //         ->whereIn('product_id', $selectedProducts)
    //         ->get();
    
    //     $warehouseStockData = [];
    
    //     foreach ($requisitionItems as $item) {
    //         $productId = $item->product_id;
    //         $demandAmount = $item->demand_amount;
    //         $inputPrice = $prices[$productId] ?? null;
    
    //         if (!$inputPrice) {
    //             Toastr::error('Price is required for product ' . $productId, 'Error');
    //             return redirect()->back();
    //         }
    
    //         $warehouseStock = Branch_Product::whereHas('branch', function ($query) {
    //                 $query->where('type', 'Warehouse');
    //             })
    //             ->where('product_id', $productId)
    //             ->sum('stock'); 
    
    //         $warehouseStockData[$productId] = $warehouseStock;
    
    //         if ($demandAmount <= $warehouseStock) {
    //             $stockLevel = $demandAmount;
    //         } else {
    //             $stockLevel = $demandAmount - $warehouseStock;
    //         }
    
    //         $warehouseBranchProduct = Branch_Product::whereHas('branch', function ($query) {
    //                 $query->where('type', 'Warehouse');
    //             })
    //             ->where('product_id', $productId)
    //             ->first();
    
    //         if ($warehouseBranchProduct) {
    //             if ($warehouseBranchProduct->price == $inputPrice) {
    //                 $existingDetails = $warehouseBranchProduct->details_stockin 
    //                     ? json_decode($warehouseBranchProduct->details_stockin, true) 
    //                     : [];
    //                 if (!is_array($existingDetails)) {
    //                     $existingDetails = [];
    //                 }
    //                 $existingDetails[] = [
    //                     'requisition' => $requisition->id,
    //                     'quantity'    => $stockLevel,
    //                     'date'        => now()->format('d-m-Y'),
    //                     'store_by'    => auth()->id(),
    //                 ];
    
    //                 $warehouseBranchProduct->stock += $stockLevel;
    //                 $warehouseBranchProduct->details_stockin = json_encode($existingDetails);
    //                 $warehouseBranchProduct->save();
    
    //                 ProductLedger::create([
    //                     'entry_date'     => now()->format('Y-m-d'),
    //                     'narration'      => 'purchaseteam',
    //                     'type'           => 'stockin',
    //                     'user_id'        => auth()->id(),
    //                     'branch_id'      => $warehouseBranchProduct->branch_id,
    //                     'product_id'     => $productId,
    //                     'quantity'       => $stockLevel,
    //                     'price'          => $inputPrice,
    //                     'batch'          => $warehouseBranchProduct->batch,
    //                     'requisition_id' => $requisition->id,
    //                     'invoice_no'     => $invoiceNo,
    //                     'payment_method' => $paymentMethod,
    //                 ]);
    //             } else {
    //                 $lastBatch = Branch_Product::latest('batch')->value('batch');
    
    //                 $newBranchProduct = Branch_Product::create([
    //                     'branch_id'  => $warehouseBranchProduct->branch_id,
    //                     'product_id' => $productId,
    //                     'price'      => $inputPrice,
    //                     'stock'      => $stockLevel,
    //                     'batch'      => $lastBatch ? $lastBatch + 1 : '', 
    
    //                     'details_stockin' => json_encode([
    //                         'requisition' => $requisition->id,
    //                         'quantity'    => $stockLevel,
    //                         'date'        => now()->format('d-m-Y'),
    //                         'store_by'    => auth()->id(),
    //                     ]),
    //                 ]);
    
    //                 ProductLedger::create([
    //                     'entry_date'     => now()->format('Y-m-d'),
    //                     'narration'      => 'purchaseteam',
    //                     'type'           => 'stockin',
    //                     'user_id'        => auth()->id(),
    //                     'branch_id'      => $warehouseBranchProduct->branch_id,
    //                     'product_id'     => $productId,
    //                     'quantity'       => $stockLevel,
    //                     'price'          => $inputPrice,
    //                     'batch'          => $newBranchProduct->batch,
    //                     'requisition_id' => $requisition->id,
    //                     'invoice_no'     => $invoiceNo,
    //                     'payment_method' => $paymentMethod,
    //                 ]);
    //             }
    //         }
    
    //         $item->update([
    //             'purchase' => 2,
    //             'new_price' => $inputPrice,
    //         ]);
    //     }
    
    //     Toastr::success('Approved successfully.', 'Success');
    //     return redirect()->route('pending.purcahse.requisition'); 
    // }




      //Last date 04-25-2025
      //warehouse demand all not subtract
    // public function pendingPurchaseApprove($id)
    // {
    //     $selectedProducts = json_decode(request('selected_products'), true);
    //     $prices = json_decode(request('prices'), true);  
    //     $paymentMethod = request('payment_method');
    
    //     if (!$selectedProducts || !is_array($selectedProducts)) {
    //         Toastr::error('No products selected.', 'Error');
    //         return redirect()->back();
    //     }
    
    //     // Generate unique invoice number
    //     $invoiceNo = 'INV-' . strtoupper(uniqid());
    
    //     $requisition = Requisition::findOrFail($id);
    
    //     $allRequisitionItems = RequisitionItem::where('requisition_id', $id)
    //         ->where('purchase', 1)
    //         ->pluck('product_id')
    //         ->toArray();
    
    //     $isAllApproved = empty(array_diff($allRequisitionItems, $selectedProducts));
    
    //     // Map payment method to chart of account name
    //     $paymentMethodMap = [
    //         'cash' => 'Cash in hand',
    //         'Bank' => 'Bank',
    //         'due'  => 'Accounts Receivable',
    //     ];
    //     $paymentMethodDisplay = $paymentMethodMap[strtolower($paymentMethod)] ?? $paymentMethod;
    //     $paymentMethodDisplay = ucfirst(strtolower($paymentMethod));
    
    //     // Find chart of account
    //     $chartAccount = ChartOfAccount::where('name', 'like', $paymentMethodDisplay)
    //         ->orWhere('name', 'like', '%' . $paymentMethodDisplay . '%')
    //         ->first(['id', 'code']);
        
    //     if (!$chartAccount) {
    //         $chartAccount = ChartOfAccount::where('name', 'like', '%Cash%')
    //             ->orWhere('name', 'like', '%Bank%')
    //             ->first(['id', 'code']);
            
    //         if (!$chartAccount) {
    //             $chartAccount = ChartOfAccount::first(['id', 'code']);
                
    //             if (!$chartAccount) {
    //                 Toastr::error("No Chart of Accounts configured in the system!", 'Error');
    //                 return redirect()->back();
    //             }
    //         }
    //     }
    
    //     $requisition->update([
    //         'status' => 2,
    //         'purchase_approve' => 1,
    //         'pending_purchase_status' => $isAllApproved ? 1 : 0,
    //         'invoice_no' => $invoiceNo,
    //         'payment_method' => $paymentMethod,
    //         'chart_of_account_id' => $chartAccount->id,
    //         'chart_of_account_code' => $chartAccount->code,
    //     ]);
    
    //     $requisitionItems = RequisitionItem::where('requisition_id', $id)
    //         ->where('purchase', 1)
    //         ->whereIn('product_id', $selectedProducts)
    //         ->get();
    
    //     $warehouseStockData = [];
    
    //     foreach ($requisitionItems as $item) {
    //         $productId = $item->product_id;
    //         $demandAmount = $item->demand_amount;
    //         $inputPrice = $prices[$productId] ?? null;
    
    //         if (!$inputPrice) {
    //             Toastr::error('Price is required for product ' . $productId, 'Error');
    //             return redirect()->back();
    //         }
    
    //         $warehouseStock = Branch_Product::whereHas('branch', function ($query) {
    //                 $query->where('type', 'Warehouse');
    //             })
    //             ->where('product_id', $productId)
    //             ->sum('stock'); 
    
    //         $warehouseStockData[$productId] = $warehouseStock;
    
    //         if ($demandAmount <= $warehouseStock) {
    //             $stockLevel = $demandAmount;
    //         } else {
    //             $stockLevel = $demandAmount - $warehouseStock;
    //         }
    
    //         $warehouseBranchProduct = Branch_Product::whereHas('branch', function ($query) {
    //                 $query->where('type', 'Warehouse');
    //             })
    //             ->where('product_id', $productId)
    //             ->first();
    
    //         if ($warehouseBranchProduct) {
    //             if ($warehouseBranchProduct->price == $inputPrice) {
    //                 $existingDetails = $warehouseBranchProduct->details_stockin 
    //                     ? json_decode($warehouseBranchProduct->details_stockin, true) 
    //                     : [];
    //                 if (!is_array($existingDetails)) {
    //                     $existingDetails = [];
    //                 }
    //                 $existingDetails[] = [
    //                     'requisition' => $requisition->id,
    //                     'quantity'    => $stockLevel,
    //                     'date'        => now()->format('d-m-Y'),
    //                     'store_by'    => auth()->id(),
    //                 ];
    
    //                 $warehouseBranchProduct->stock += $stockLevel;
    //                 $warehouseBranchProduct->details_stockin = json_encode($existingDetails);
    //                 $warehouseBranchProduct->save();
    
    //                 ProductLedger::create([
    //                     'entry_date'            => now()->format('Y-m-d'),
    //                     'narration'             => 'purchaseteam',
    //                     'type'                  => 'stockin',
    //                     'user_id'               => auth()->id(),
    //                     'branch_id'             => $warehouseBranchProduct->branch_id,
    //                     'product_id'            => $productId,
    //                     'quantity'              => $stockLevel,
    //                     'price'                 => $inputPrice,
    //                     'batch'                 => $warehouseBranchProduct->batch,
    //                     'requisition_id'        => $requisition->id,
    //                     'invoice_no'            => $invoiceNo,
    //                     'payment_method'        => $paymentMethod,
    //                     'chart_of_account_id'   => $chartAccount->id,
    //                     'chart_of_account_code' => $chartAccount->code,
    //                 ]);
    //             } else {
    //                 $lastBatch = Branch_Product::latest('batch')->value('batch');
    
    //                 $newBranchProduct = Branch_Product::create([
    //                     'branch_id'  => $warehouseBranchProduct->branch_id,
    //                     'product_id' => $productId,
    //                     'price'      => $inputPrice,
    //                     'stock'      => $stockLevel,
    //                     'batch'      => $lastBatch ? $lastBatch + 1 : '', 
    
    //                     'details_stockin' => json_encode([
    //                         'requisition' => $requisition->id,
    //                         'quantity'    => $stockLevel,
    //                         'date'        => now()->format('d-m-Y'),
    //                         'store_by'    => auth()->id(),
    //                     ]),
    //                 ]);
    
    //                 ProductLedger::create([
    //                     'entry_date'            => now()->format('Y-m-d'),
    //                     'narration'             => 'purchaseteam',
    //                     'type'                  => 'stockin',
    //                     'user_id'               => auth()->id(),
    //                     'branch_id'             => $warehouseBranchProduct->branch_id,
    //                     'product_id'            => $productId,
    //                     'quantity'              => $stockLevel,
    //                     'price'                 => $inputPrice,
    //                     'batch'                 => $newBranchProduct->batch,
    //                     'requisition_id'        => $requisition->id,
    //                     'invoice_no'            => $invoiceNo,
    //                     'payment_method'        => $paymentMethod,
    //                     'chart_of_account_id'   => $chartAccount->id,
    //                     'chart_of_account_code' => $chartAccount->code,
    //                 ]);
    //             }
    //         }
    
    //         $item->update([
    //             'purchase' => 2,
    //             'new_price' => $inputPrice,
    //         ]);
    //     }
    
    //     Toastr::success('Approved successfully.', 'Success');
    //     return redirect()->route('pending.purcahse.requisition'); 
    // }




    //not work proper for journaul entry vroiucher 
    // public function pendingPurchaseApprove($id)
    // {
    //     $selectedProducts = json_decode(request('selected_products'), true);
    //     $prices = json_decode(request('prices'), true);  
    //     $paymentMethod = request('payment_method');

    //     if (!$selectedProducts || !is_array($selectedProducts)) {
    //         Toastr::error('No products selected.', 'Error');
    //         return redirect()->back();
    //     }

    //     $invoiceNo = 'INV-' . strtoupper(uniqid());

    //     $requisition = Requisition::findOrFail($id);
        
    //     $isWarehouseRequisition = $requisition->branch->type === 'Warehouse';

    //     $allRequisitionItems = RequisitionItem::where('requisition_id', $id)
    //         ->where('purchase', 1)
    //         ->pluck('product_id')
    //         ->toArray();

    //     $isAllApproved = empty(array_diff($allRequisitionItems, $selectedProducts));

    //     $paymentMethodMap = [
    //         'cash' => 'Cash In Hand',
    //         'Bank' => 'Cash at Bank',
    //         'due'  => 'Accounts Receivable',
    //     ];
    //     $paymentMethodDisplay = $paymentMethodMap[strtolower($paymentMethod)] ?? $paymentMethod;
    //     $paymentMethodDisplay = ucfirst(strtolower($paymentMethod));

    //     $chartAccount = ChartOfAccount::where('name', 'like', $paymentMethodDisplay)
    //         ->orWhere('name', 'like', '%' . $paymentMethodDisplay . '%')
    //         ->first(['id', 'code']);
        
    //     if (!$chartAccount) {
    //         $chartAccount = ChartOfAccount::where('name', 'like', '%Cash%')
    //             ->orWhere('name', 'like', '%Bank%')
    //             ->first(['id', 'code']);
            
    //         if (!$chartAccount) {
    //             $chartAccount = ChartOfAccount::first(['id', 'code']);
                
    //             if (!$chartAccount) {
    //                 Toastr::error("No Chart of Accounts configured in the system!", 'Error');
    //                 return redirect()->back();
    //             }
    //         }
    //     }

    //     $requisition->update([
    //         'status' => 2,
    //         'purchase_approve' => 1,
    //         'pending_purchase_status' => $isAllApproved ? 1 : 0,
    //         'invoice_no' => $invoiceNo,
    //         'payment_method' => $paymentMethod,
    //         'chart_of_account_id' => $chartAccount->id,
    //         'chart_of_account_code' => $chartAccount->code,
    //     ]);

    //     $requisitionItems = RequisitionItem::where('requisition_id', $id)
    //         ->where('purchase', 1)
    //         ->whereIn('product_id', $selectedProducts)
    //         ->get();

    //     $warehouseStockData = [];

    //     foreach ($requisitionItems as $item) {
    //         $productId = $item->product_id;
    //         $productdescription = $item->description;
    //         $demandAmount = $item->demand_amount;
    //         $inputPrice = $prices[$productId] ?? null;

    //         if (!$inputPrice) {
    //             Toastr::error('Price is required for product ' . $productId, 'Error');
    //             return redirect()->back();
    //         }

    //         $warehouseStock = Branch_Product::whereHas('branch', function ($query) {
    //                 $query->where('type', 'Warehouse');
    //             })
    //             ->where('product_id', $productId)
    //             ->sum('stock'); 

    //         $warehouseStockData[$productId] = $warehouseStock;

    //         $stockLevel = $isWarehouseRequisition ? $demandAmount : ($demandAmount <= $warehouseStock ? $demandAmount : $demandAmount - $warehouseStock);

    //         $warehouseBranchProduct = Branch_Product::whereHas('branch', function ($query) {
    //                 $query->where('type', 'Warehouse');
    //             })
    //             ->where('product_id', $productId)
    //             ->first();

    //         if ($warehouseBranchProduct) {
    //             if ($warehouseBranchProduct->price == $inputPrice) {
    //                 $existingDetails = $warehouseBranchProduct->details_stockin 
    //                     ? json_decode($warehouseBranchProduct->details_stockin, true) 
    //                     : [];
    //                 if (!is_array($existingDetails)) {
    //                     $existingDetails = [];
    //                 }
    //                 $existingDetails[] = [
    //                     'requisition' => $requisition->id,
    //                     'quantity'    => $stockLevel,
    //                     'date'        => now()->format('d-m-Y'),
    //                     'store_by'    => auth()->id(),
    //                 ];

    //                 $warehouseBranchProduct->stock += $stockLevel;
    //                 $warehouseBranchProduct->details_stockin = json_encode($existingDetails);
    //                 $warehouseBranchProduct->save();

    //                 ProductLedger::create([
    //                     'entry_date'            => now()->format('Y-m-d'),
    //                     'narration'             => 'purchaseteam',
    //                     'type'                  => 'stockin',
    //                     'user_id'               => auth()->id(),
    //                     'branch_id'             => $warehouseBranchProduct->branch_id,
    //                     'product_id'            => $productId,
    //                     'quantity'              => $stockLevel,
    //                     'price'                 => $inputPrice,
    //                     'batch'                 => $warehouseBranchProduct->batch,
    //                     'requisition_id'        => $requisition->id,
    //                     'invoice_no'            => $invoiceNo,
    //                     'payment_method'        => $paymentMethod,
    //                     'chart_of_account_id'   => $chartAccount->id,
    //                     'chart_of_account_code' => $chartAccount->code,
    //                 ]);
    //             } else {
    //                 $lastBatch = Branch_Product::latest('batch')->value('batch');

    //                 $newBranchProduct = Branch_Product::create([
    //                     'branch_id'  => $warehouseBranchProduct->branch_id,
    //                     'product_id' => $productId,
    //                     'price'      => $inputPrice,
    //                     'stock'      => $stockLevel,
    //                     'batch'      => $lastBatch ? $lastBatch + 1 : '', 

    //                     'details_stockin' => json_encode([
    //                         'requisition' => $requisition->id,
    //                         'quantity'    => $stockLevel,
    //                         'date'        => now()->format('d-m-Y'),
    //                         'store_by'    => auth()->id(),
    //                     ]),
    //                 ]);

    //                 ProductLedger::create([
    //                     'entry_date'            => now()->format('Y-m-d'),
    //                     'narration'             => 'purchaseteam',
    //                     'type'                  => 'stockin',
    //                     'user_id'               => auth()->id(),
    //                     'branch_id'             => $warehouseBranchProduct->branch_id,
    //                     'product_id'            => $productId,
    //                     'quantity'              => $stockLevel,
    //                     'price'                 => $inputPrice,
    //                     'batch'                 => $newBranchProduct->batch,
    //                     'requisition_id'        => $requisition->id,
    //                     'invoice_no'            => $invoiceNo,
    //                     'payment_method'        => $paymentMethod,
    //                     'chart_of_account_id'   => $chartAccount->id,
    //                     'chart_of_account_code' => $chartAccount->code,
    //                 ]);
    //             }
    //         } 

    //         $item->update([
    //             'purchase' => 2,
    //             'new_price' => $inputPrice,
    //         ]);
    //     }


    //         $journalEntry = JournalEntry::create([
    //             'date'        => now()->toDateString(),
    //             'reference'   => $invoiceNo,
    //             'description' => 'Purchase for requisition #' . $id,
    //             'journal_id'  => (JournalEntry::max('journal_id') ?? 99) + 1,
    //             'created_by'  => auth()->id(),
    //         ]);

    //         $totalAmount = 0;
    //         $journalItems = [];

    //         foreach ($requisitionItems as $item) {
    //             $productId = $item->product_id;
    //             $productdescription = $item->product_description;
    //             $demandAmount = $item->demand_amount;
    //             $inputPrice = $prices[$productId] ?? null;

    //             $product = Product::find($productId);
    //             $productChartAccount = null;
                
    //             if ($product && $product->code) {
    //                 $productChartAccount = ProductAccountMap::where('product_code', $product->code)
    //                     ->first(['id', 'account_expense_code']); 
    //                 $productChartAccount = ChartOfAccount::where('code', $productChartAccount->account_expense_code)
    //                     ->first(['id']); 
    //             }

    //             if ($productChartAccount) {
    //                 $amount = $stockLevel * $inputPrice;
    //                 $totalAmount += $amount;
                    
    //                 $journalItems[] = [
    //                     'journal' => $journalEntry->id,
    //                     'account' => $productChartAccount->id,
    //                     'debit'   => $amount,
    //                     'credit'  => 0.00,
    //                     'description' => $productdescription,
    //                 ];
    //             }

    //         }

    //         if ($totalAmount > 0) {
    //             foreach ($journalItems as $item) {
    //                 JournalItem::create($item);
    //             }
    //             JournalItem::create([
    //                 'journal' => $journalEntry->id,
    //                 'account' => $chartAccount->id,
    //                 'debit'   => 0.00,
    //                 'credit'  => $totalAmount,
    //                 'description' => 'Payment by ' . $paymentMethod,
    //             ]);
    //         }

    //     Toastr::success('Approved successfully.', 'Success');
    //     return redirect()->route('pending.purcahse.requisition'); 
        
    // }
    



    

    public function pendingPurchaseApprove($id)
    {
        $selectedProducts = json_decode(request('selected_products'), true);
        $prices = json_decode(request('prices'), true);  
        $paymentMethod = request('payment_method');
    
        $invoiceNo = 'INV-' . strtoupper(uniqid());
    
        $requisition = Requisition::findOrFail($id);
        $isWarehouseRequisition = $requisition->branch->type === 'Warehouse';
    
        $allRequisitionItems = RequisitionItem::where('requisition_id', $id)
            ->where('purchase', 1)
            ->pluck('product_id')
            ->toArray();
    
        $isAllApproved = empty(array_diff($allRequisitionItems, $selectedProducts));
    
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
    
        $requisition->update([
            'status' => 2,
            'purchase_approve' => 1,
            'pending_purchase_status' => $isAllApproved ? 1 : 0,
            'invoice_no' => $invoiceNo,
            'payment_method' => $paymentMethod,
            'chart_of_account_id' => $chartAccount->id,
            'chart_of_account_code' => $chartAccount->code,
        ]);
    
        $requisitionItems = RequisitionItem::where('requisition_id', $id)
            ->where('purchase', 1)
            ->whereIn('product_id', $selectedProducts)
            ->get();
    
        $warehouseStockData = [];
    
        $journalEntry = JournalEntry::create([
            'date'        => now()->toDateString(),
            'reference'   => $invoiceNo,
            'description' => 'Purchase for requisition #' . $id,
            'journal_id'  => (JournalEntry::max('journal_id') ?? 99) + 1,
            'created_by'  => auth()->id(),
        ]);
    
        $totalAmount = 0;
    
        foreach ($requisitionItems as $item) {
            $productId = $item->product_id;
            $productdescription = $item->product_description;
            $demandAmount = $item->demand_amount;
            $inputPrice = $prices[$productId];
    
            $warehouseStock = Branch_Product::whereHas('branch', function ($query) {
                    $query->where('type', 'Warehouse');
                })
                ->where('product_id', $productId)
                ->sum('stock');
    
            $warehouseStockData[$productId] = $warehouseStock;
    
            $stockLevel = $isWarehouseRequisition
                ? $demandAmount
                : ($demandAmount <= $warehouseStock ? $demandAmount : $demandAmount - $warehouseStock);
    
            $warehouseBranchProduct = Branch_Product::whereHas('branch', function ($query) {
                    $query->where('type', 'Warehouse');
                })
                ->where('product_id', $productId)
                ->first();
    
            if ($warehouseBranchProduct) {
                if ($warehouseBranchProduct->price == $inputPrice) {
                    $existingDetails = $warehouseBranchProduct->details_stockin
                        ? json_decode($warehouseBranchProduct->details_stockin, true)
                        : [];
                    if (!is_array($existingDetails)) {
                        $existingDetails = [];
                    }
    
                    $existingDetails[] = [
                        'requisition' => $requisition->id,
                        'quantity'    => $stockLevel,
                        'date'        => now()->format('d-m-Y'),
                        'store_by'    => auth()->id(),
                    ];
    
                    $warehouseBranchProduct->stock += $stockLevel;
                    $warehouseBranchProduct->details_stockin = json_encode($existingDetails);
                    $warehouseBranchProduct->save();
    
                    $batch = $warehouseBranchProduct->batch; 

                } else {
                    $lastBatch = Branch_Product::latest('batch')->value('batch');
                    $batch = $lastBatch ? $lastBatch + 1 : 1;
    
                    Branch_Product::create([
                        'branch_id'  => $warehouseBranchProduct->branch_id,
                        'product_id' => $productId,
                        'price'      => $inputPrice,
                        'stock'      => $stockLevel,
                        'batch'      => $batch,
                        'details_stockin' => json_encode([
                            'requisition' => $requisition->id,
                            'quantity'    => $stockLevel,
                            'date'        => now()->format('d-m-Y'),
                            'store_by'    => auth()->id(),
                        ]),
                    ]);
                }
    
                ProductLedger::create([
                    'entry_date'            => now()->format('Y-m-d'),
                    'narration'             => 'purchaseteam',
                    'type'                  => 'stockin',
                    'user_id'               => auth()->id(),
                    'branch_id'             => $warehouseBranchProduct->branch_id,
                    'product_id'            => $productId,
                    'quantity'              => $stockLevel,
                    'price'                 => $inputPrice,
                    'batch'                 => $batch,
                    'requisition_id'        => $requisition->id,
                    'invoice_no'            => $invoiceNo,
                    'payment_method'        => $paymentMethod,
                    'chart_of_account_id'   => $chartAccount->id,
                    'chart_of_account_code' => $chartAccount->code,
                ]);
            }
    
            $product = Product::find($productId);
            $productChartAccount = null;
    
            if ($product && $product->code) {
                $map = ProductAccountMap::where('product_code', $product->code)->first(['account_expense_code']);
                if ($map && $map->account_expense_code) {
                    $productChartAccount = ChartOfAccount::where('code', $map->account_expense_code)->first(['id']);
                }
            }
    
            if ($productChartAccount) {
                $amount = $stockLevel * $inputPrice;
                $totalAmount += $amount;
    
                JournalItem::create([
                    'journal'     => $journalEntry->id,
                    'account'     => $productChartAccount->id,
                    'debit'       => $amount,
                    'credit'      => 0.00,
                    'description' => $productdescription,
                    'date'        => $journalEntry->date, // <-- added line
                ]);
            }
    

            // $item->update([
            //     'purchase'   => 2,
            //     'newprice_qty'  => $inputPrice,
            // ]);

            $item->update([
                'purchase' => 2,
                'newprice_qty' => json_encode([
                    'new_price' => $inputPrice,
                    'quantity' => (string) $stockLevel,
                ]),
            ]);

        }
    
        if ($totalAmount > 0) {
            JournalItem::create([
                'journal'     => $journalEntry->id,
                'account'     => $chartAccount->id,
                'debit'       => 0.00,
                'credit'      => $totalAmount,
                'description' => 'Payment by ' . $paymentMethod,
                'date'        => $journalEntry->date, // <-- added line
            ]);
        }
    
        Toastr::success('Approved successfully.', 'Success');
        return redirect()->route('pending.purcahse.requisition');
    }












    //approved for headoffice 

    // public function sendToApprove($id)
    // {
    //     $requisition = Requisition::findOrFail($id);
    //     $requisition->update([
    //         'status' => 7,
    //     ]);

    //     Toastr::success('Sent for Approval successfully.', 'Success');
    //     return redirect()->route('pending.purcahse.requisition');
    // } 
    
    




    // public function sendToApprove($id, Request $request)
    // {
    //     $selectedProductIds = explode(',', $request->input('selected_products', ''));
    
    //     if (empty($selectedProductIds) || !is_array($selectedProductIds)) {
    //         Toastr::error('No products selected.', 'Error');
    //         return redirect()->back();
    //     }
    
    //     $requisition = Requisition::findOrFail($id);
    
    //     $isAllSelected = RequisitionItem::where('requisition_id', $requisition->id)
    //         ->where('purchase', 1)
    //         ->whereNotIn('product_id', $selectedProductIds)
    //         ->doesntExist();
    
    //     $requisition->update([
    //         'pending_purchase_status' => $isAllSelected ? 3 : 0,
    //     ]);
    
    //     Toastr::success('Sent for Approval successfully.', 'Success');
    //     return redirect()->route('pending.purcahse.requisition');
        
    // }
    
    
    public function sendToApprove($id, Request $request)
    {
        $selectedProductIds = explode(',', $request->input('selected_products', ''));
    
        if (empty($selectedProductIds) || !is_array($selectedProductIds)) {
            Toastr::error('No products selected.', 'Error');
            return redirect()->back();
        }
    
        $requisition = Requisition::findOrFail($id);
    
        RequisitionItem::where('requisition_id', $requisition->id)
            ->whereIn('product_id', $selectedProductIds)
            ->update(['headoffice_approval' => 1]);
    
        $isAllSelected = RequisitionItem::where('requisition_id', $requisition->id)
            ->where('purchase', 1)
            ->whereNotIn('product_id', $selectedProductIds)
            ->doesntExist();

        $requisition->update([
            'pending_purchase_status' => $isAllSelected ? 3 : 0,
            'pending_approval_status_headoffice' => 0,
        ]);
    
        Toastr::success('Sent for Approval successfully.', 'Success');
        return redirect()->route('pending.purcahse.requisition');
    }
    
















    public function sendToapproveCheck($id)
    {
        $requisition = Requisition::findOrFail($id);
        $requisitionItems = RequisitionItem::where('requisition_id', $requisition->id)
            ->where('purchase', '=', 1) 
            ->get();

        $allDetails = [];

        foreach ($requisitionItems as $item) {
            $productId = $item->product_id;
            $demandAmount = $item->demand_amount;

            $product = Product::find($productId);
            $productName = $product ? $product->name : "Unknown Product";


            // $warehouseBranchProduct = Branch_Product::whereHas('branch', function ($query) {
            //         $query->where('type', 'Warehouse');
            //     })
            //     ->where('product_id', $productId)
            //     ->first();

            // $stock = $warehouseBranchProduct ? $warehouseBranchProduct->stock : 0; 

            $warehouseStocks = Branch_Product::whereHas('branch', function ($query) {
                    $query->where('type', 'Warehouse');
                })
                ->where('product_id', $productId)
                ->get()
                ->groupBy('product_id');

            $totalStock = 0;
            $batchDetails = [];

            if ($warehouseStocks->isNotEmpty()) {
                foreach ($warehouseStocks as $batches) {
                    foreach ($batches as $batch) {
                        $totalStock += $batch->stock;
                        $batchDetails[] = "(Batch: {$batch->batch}, {$batch->stock})";
                    }
                }
            }

            $allDetails[] = [
                'ppappvreq_id' => $requisition->id,
                'product_id' => $productId,
                'product_name' => $productName,
                'stock' => $totalStock,
                'demand_amount' => $demandAmount,
                'is_insufficient' => $demandAmount > $totalStock,
                'headoffice_approval' => $item->headoffice_approval, // Add this line to check the approval status
            ];
        }

        session()->flash('allDetailsapprovedfor', $allDetails);
        session()->flash('approvalModal', true);

        return redirect()->back();
    }












    //new -- click delivery decrement from own stock

    // public function delivery($id)
    // {
    //     $requisition = Requisition::findOrFail($id);
    //     $requisition->update(['status' => 1]); 
    
    //     $requisitionItems = RequisitionItem::where('requisition_id', $requisition->id)->get();
    
    //     foreach ($requisitionItems as $item) {
    //         $productId = $item->product_id;
    //         $stockLevel = $item->demand_amount;
    //         $branchId = $requisition->branch_id;
    
           
    //         $warehouseBranchProduct = Branch_Product::whereHas('branch', function ($query) {
    //                 $query->where('type', 'Warehouse');
    //             })
    //             ->where('product_id', $productId)
    //             ->first();

    //         if ($warehouseBranchProduct) {
    //             $warehouseBranchProduct->stock -= $stockLevel;
    //             $warehouseBranchProduct->save();
    //         }

    //     }
    
    //     Toastr::success('Delivery updated successfully.', 'Success');
    //     return redirect()->route('order.list');
    // }





    // public function delivery($id)
    // {
    //     $requisition = Requisition::findOrFail($id);

    //     $insufficientProducts = json_decode(request()->get('insufficient_products', '[]'), true);

    //     if (!is_array($insufficientProducts)) {
    //         $insufficientProducts = [];
    //     }

    //     // $requisitionItems = RequisitionItem::where('requisition_id', $requisition->id)->get();  

    //     $requisitionItems = RequisitionItem::where('requisition_id', $requisition->id)
    //                                    ->where('delivery', 0)  // Ensure only undelivered items are fetched
    //                                    ->get();
                                       

    //     foreach ($requisitionItems as $item) {
    //         $productId = $item->product_id;
    //         $stockLevel = $item->demand_amount;
    //         $branchId = $requisition->branch_id;

    //         if (in_array($productId, $insufficientProducts) || $item->purchase == 1) {
    //             continue;
    //         }

    //         $warehouseBranchProduct = Branch_Product::whereHas('branch', function ($query) {
    //                 $query->where('type', 'Warehouse');
    //             })
    //             ->where('product_id', $productId)
    //             ->first();

    //         if ($warehouseBranchProduct) {
    //             $warehouseBranchProduct->stock -= $stockLevel;
    //             $warehouseBranchProduct->save();
    //         }

    //         $item->update(['delivery' => 1]);
    //     }

    //     $allDelivered = $requisitionItems->every(fn($item) => $item->delivery == 1);

    //     $requisition->update([
    //         'status' => 1,
    //         'partial_delivery' => $allDelivered ? 1 : 0,
    //     ]);

    //     Toastr::success('Delivery updated successfully.', 'Success');
    //     return redirect()->route('order.list');
    // }  




    //done and work

    // public function delivery($id)
    // {
    //     $requisition = Requisition::findOrFail($id);
    
    //     $insufficientProducts = json_decode(request()->get('insufficient_products', '[]'), true);
    
    //     if (!is_array($insufficientProducts)) {
    //         $insufficientProducts = [];
    //     }
    
    //     // Fetch requisition items
    //     $requisitionItems = RequisitionItem::where('requisition_id', $requisition->id)
    //                                        ->where('delivery', 0)  // Ensure only undelivered items are fetched
    //                                        ->get();
    
    //     foreach ($requisitionItems as $item) {
    //         $productId = $item->product_id;
    //         $stockLevel = $item->demand_amount;
    //         $branchId = $requisition->branch_id;
    
    //         // Skip if insufficient stock or purchase == 1
    //         if (in_array($productId, $insufficientProducts) || $item->purchase == 1 || $item->reject == 1) {
    //             continue;
    //         }
    
    //         $warehouseBranchProduct = Branch_Product::whereHas('branch', function ($query) {
    //                 $query->where('type', 'Warehouse');
    //             })
    //             ->where('product_id', $productId)
    //             ->first();
    
    //         if ($warehouseBranchProduct) {
    //             $warehouseBranchProduct->stock -= $stockLevel;
    //             $warehouseBranchProduct->save();
    //         }
    
    //         $item->update(['delivery' => 1]);
    //     }
    
    //     $allDelivered = $requisitionItems->every(fn($item) => $item->delivery == 1);
    
    //     $requisition->update([
    //         'status' => 1,
    //         'partial_delivery' => $allDelivered ? 1 : 0,
    //     ]);
    
    //     Toastr::success('Delivery updated successfully.', 'Success');
    //     return redirect()->route('order.list');
    // }
    


      //before multiple price and stock-deamnd so need quantity
    // public function delivery($id)
    // {
    //     $requisition = Requisition::findOrFail($id);
    
    //     $insufficientProducts = json_decode(request()->get('insufficient_products', '[]'), true);
    
    //     if (!is_array($insufficientProducts)) {
    //         $insufficientProducts = [];
    //     }
    
    //     // Fetch requisition items
    //     $requisitionItems = RequisitionItem::where('requisition_id', $requisition->id)
    //                                        ->where('delivery', 0)  // Ensure only undelivered items are fetched
    //                                        ->get();
    
    //     foreach ($requisitionItems as $item) {
    //         $productId = $item->product_id;
    //         $stockLevel = $item->demand_amount;
    
    //         // Skip if insufficient stock, purchase == 1, or reject == 1
    //         if (in_array($productId, $insufficientProducts) || $item->purchase == 1 || $item->reject == 1) {
    //             continue;
    //         }
    
    //         $warehouseBranchProduct = Branch_Product::whereHas('branch', function ($query) {
    //                 $query->where('type', 'Warehouse');
    //             })
    //             ->where('product_id', $productId)
    //             ->first();
    
    //         if ($warehouseBranchProduct) {
    //             $warehouseBranchProduct->stock -= $stockLevel;
    //             $warehouseBranchProduct->save();
    //         }
    
    //         $item->update(['delivery' => 1]);
    //     }
    
    //     $allDelivered = $requisitionItems->every(fn($item) => $item->delivery == 1);
    
    //     $requisition->update([
    //         'status' => 1,
    //         'partial_delivery' => $allDelivered ? 1 : 0,
    //     ]);
     
    //     $requisitionItemsDoneAll = RequisitionItem::where('requisition_id', $requisition->id)->get();

    //     $allProcessed = $requisitionItemsDoneAll->every(fn($item) => $item->delivery == 1 || $item->reject == 1);
    
    //     $requisition->update([
    //         'alldone_status' => $allProcessed ? 1 : 0,
    //     ]);

    //     Toastr::success('Delivery updated successfully.', 'Success');
    //     return redirect()->route('order.list');

    // }
    


    //working before bacth wise stockout price wise 
    // public function delivery($id)
    // {
    //     $requisition = Requisition::findOrFail($id);
    //     $insufficientProducts = json_decode(request()->get('insufficient_products', '[]'), true);
    
    //     if (!is_array($insufficientProducts)) {
    //         $insufficientProducts = [];
    //     }
    
    //     // Fetch requisition items
    //     $requisitionItems = RequisitionItem::where('requisition_id', $requisition->id)
    //                                        ->where('delivery', 0) // Ensure only undelivered items are fetched
    //                                        ->get();
    
    //     foreach ($requisitionItems as $item) {
    //         $productId = $item->product_id;
    //         $demandAmount = $item->demand_amount;
    
    //         // Skip if insufficient stock, purchase == 1, or reject == 1
    //         if (in_array($productId, $insufficientProducts) || $item->purchase == 1 || $item->reject == 1) {
    //             continue;
    //         }
    
    //         // Fetch batch-wise stock from warehouse branches
    //         $warehouseBatches = Branch_Product::whereHas('branch', function ($query) {
    //                 $query->where('type', 'Warehouse');
    //             })
    //             ->where('product_id', $productId)
    //             ->orderBy('batch', 'asc') // Deduct from older batches first
    //             ->get();
    
    //         $remainingDemand = $demandAmount;
    
    //         foreach ($warehouseBatches as $batch) {
    //             if ($remainingDemand <= 0) {
    //                 break;
    //             }
    
    //             if ($batch->stock > 0) {
    //                 $deductAmount = min($batch->stock, $remainingDemand);
    //                 $batch->stock -= $deductAmount;
    //                 $batch->save();
    
    //                 $remainingDemand -= $deductAmount;
    //             }
    //         }
    
    //         // If stock fulfilled, mark item as delivered
    //         if ($remainingDemand == 0) {
    //             $item->update(['delivery' => 1]);
    //         }
    //     }
    
    //     // Check if all items are delivered
    //     $allDelivered = $requisitionItems->every(fn($item) => $item->delivery == 1);
    
    //     $requisition->update([
    //         'status' => 1,
    //         'partial_delivery' => $allDelivered ? 1 : 0,
    //     ]);
    
    //     $requisitionItemsDoneAll = RequisitionItem::where('requisition_id', $requisition->id)->get();
    
    //     $allProcessed = $requisitionItemsDoneAll->every(fn($item) => $item->delivery == 1 || $item->reject == 1);
    
    //     $requisition->update([
    //         'alldone_status' => $allProcessed ? 1 : 0,
    //     ]);
    
    //     Toastr::success('Delivery updated successfully.', 'Success');
    //     return redirect()->route('order.list');
    // }
    






    
    //all done before invoice and payment 
    // public function delivery($id)
    // {
    //     $requisition = Requisition::findOrFail($id);
    //     $insufficientProducts = json_decode(request()->get('insufficient_products', '[]'), true);
    
    //     if (!is_array($insufficientProducts)) {
    //         $insufficientProducts = [];
    //     }
    
    //     // Fetch requisition items
    //     $requisitionItems = RequisitionItem::where('requisition_id', $requisition->id)
    //                                        ->where('delivery', 0) // Ensure only undelivered items are fetched
    //                                        ->get();
    
    //     foreach ($requisitionItems as $item) {
    //         $productId = $item->product_id;
    //         $demandAmount = $item->demand_amount;
    
    //         // Skip if insufficient stock, purchase == 1, or reject == 1
    //         if (in_array($productId, $insufficientProducts) || $item->purchase == 1 || $item->reject == 1) {
    //             continue;
    //         }
    
    //         // Fetch batch-wise stock from warehouse branches
    //         $warehouseBatches = Branch_Product::whereHas('branch', function ($query) {
    //                 $query->where('type', 'Warehouse');
    //             })
    //             ->where('product_id', $productId)
    //             ->orderBy('batch', 'asc') // Deduct from older batches first
    //             ->get();
    
    //         $remainingDemand = $demandAmount;
    
    //         foreach ($warehouseBatches as $batch) {
    //             if ($remainingDemand <= 0) {
    //                 break;
    //             }
    
    //             if ($batch->stock > 0) {
    //                 $deductAmount = min($batch->stock, $remainingDemand);
    //                 $batch->stock -= $deductAmount;
    //                 $batch->save();
    
    //                 // New code starts here:
    //                 // Update the details_stockout for the batch
    //                 $existingDetails = $batch->details_stockout 
    //                     ? json_decode($batch->details_stockout, true) 
    //                     : [];
    //                 if (!is_array($existingDetails)) {
    //                     $existingDetails = [];
    //                 }
    
    //                 $existingDetails[] = [
    //                     'requisition' => $requisition->id,
    //                     'quantity'    => $deductAmount,
    //                     'date'        => now()->format('d-m-Y'),
    //                     'store_by'    => auth()->id(),
    //                 ];
    
    //                 $batch->details_stockout = json_encode($existingDetails);
    //                 $batch->save();
    
    //                 // Log the transaction in ProductLedger
    //                 ProductLedger::create([
    //                     'entry_date'     => now()->format('Y-m-d'),
    //                     'narration'      => 'warehouse',
    //                     'type'           => 'stockout',
    //                     'user_id'        => auth()->id(),
    //                     'branch_id'      => $batch->branch_id, // use the branch ID from the batch
    //                     'product_id'     => $productId,
    //                     'quantity'       => $deductAmount,
    //                     'price'          => $batch->price, // Use the price of the batch
    //                     'batch'          => $batch->batch,
    //                     'requisition_id' => $requisition->id,
    //                 ]);
    
    //                 // End of the new code
    
    //                 $remainingDemand -= $deductAmount;
    //             }
    //         }
    
    //         // If stock fulfilled, mark item as delivered
    //         if ($remainingDemand == 0) {
    //             $item->update(['delivery' => 1]);
    //         }
    //     }
    
    //     // Check if all items are delivered
    //     $allDelivered = $requisitionItems->every(fn($item) => $item->delivery == 1);
    
    //     $requisition->update([
    //         'status' => 1,
    //         'partial_delivery' => $allDelivered ? 1 : 0,
    //     ]);
    
    //     $requisitionItemsDoneAll = RequisitionItem::where('requisition_id', $requisition->id)->get();
    
    //     $allProcessed = $requisitionItemsDoneAll->every(fn($item) => $item->delivery == 1 || $item->reject == 1);
    
    //     $requisition->update([
    //         'alldone_status' => $allProcessed ? 1 : 0,
    //     ]);
    
    //     Toastr::success('Delivery updated successfully.', 'Success');
    //     return redirect()->route('order.list');
    // }
    



    // public function delivery($id)
    // {
    //     $requisition = Requisition::findOrFail($id);
    //     $insufficientProducts = json_decode(request()->get('insufficient_products', '[]'), true);
    //     $paymentMethod = request()->get('payment_method', 'cash');

    //     if (!is_array($insufficientProducts)) {
    //         $insufficientProducts = [];
    //     }

    //     // Fetch requisition items
    //     $requisitionItems = RequisitionItem::where('requisition_id', $requisition->id)
    //                                     ->where('delivery', 0)
    //                                     ->get();

    //     // Generate unique invoice number
    //     $invoiceNumber = 'INV-'.now()->format('YmdHis').'-'.str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);

    //     foreach ($requisitionItems as $item) {
    //         $productId = $item->product_id;
    //         $demandAmount = $item->demand_amount;

    //         // Skip if insufficient stock, purchase == 1, or reject == 1
    //         if (in_array($productId, $insufficientProducts) || $item->purchase == 1 || $item->reject == 1) {
    //             continue;
    //         }

    //         // Fetch batch-wise stock from warehouse branches
    //         $warehouseBatches = Branch_Product::whereHas('branch', function ($query) {
    //                 $query->where('type', 'Warehouse');
    //             })
    //             ->where('product_id', $productId)
    //             ->orderBy('batch', 'asc')
    //             ->get();

    //         $remainingDemand = $demandAmount;

    //         foreach ($warehouseBatches as $batch) {
    //             if ($remainingDemand <= 0) {
    //                 break;
    //             }

    //             if ($batch->stock > 0) {
    //                 $deductAmount = min($batch->stock, $remainingDemand);
    //                 $batch->stock -= $deductAmount;
    //                 $batch->save();

    //                 // Update the details_stockout for the batch
    //                 $existingDetails = $batch->details_stockout 
    //                     ? json_decode($batch->details_stockout, true) 
    //                     : [];
                    
    //                 if (!is_array($existingDetails)) {
    //                     $existingDetails = [];
    //                 }

    //                 $existingDetails[] = [
    //                     'requisition' => $requisition->id,
    //                     'quantity'    => $deductAmount,
    //                     'date'        => now()->format('d-m-Y'),
    //                     'store_by'    => auth()->id(),
    //                     'invoice_no' => $invoiceNumber,
    //                 ];

    //                 $batch->details_stockout = json_encode($existingDetails);
    //                 $batch->save();

    //                 // Log the transaction in ProductLedger
    //                 ProductLedger::create([
    //                     'entry_date'     => now()->format('Y-m-d'),
    //                     'narration'      => 'warehouse',
    //                     'type'           => 'stockout',
    //                     'user_id'        => auth()->id(),
    //                     'branch_id'      => $batch->branch_id,
    //                     'product_id'     => $productId,
    //                     'quantity'       => $deductAmount,
    //                     'price'          => $batch->price,
    //                     'batch'          => $batch->batch,
    //                     'requisition_id' => $requisition->id,
    //                     'payment_method' => $paymentMethod,
    //                     'invoice_no'     => $invoiceNumber,
    //                 ]);

    //                 $remainingDemand -= $deductAmount;
    //             }
    //         }

    //         // If stock fulfilled, mark item as delivered
    //         if ($remainingDemand == 0) {
    //             $item->update(['delivery' => 1]);
    //         }
    //     }

    //     // Check if all items are delivered
    //     $allDelivered = $requisitionItems->every(fn($item) => $item->delivery == 1);

    //     $requisition->update([
    //         'status' => 1,
    //         'partial_delivery' => $allDelivered ? 1 : 0,
    //     ]);

    //     $requisitionItemsDoneAll = RequisitionItem::where('requisition_id', $requisition->id)->get();

    //     $allProcessed = $requisitionItemsDoneAll->every(fn($item) => $item->delivery == 1 || $item->reject == 1);

    //     $requisition->update([
    //         'alldone_status' => $allProcessed ? 1 : 0,
    //     ]);

    //     Toastr::success('Delivery updated successfully.', 'Success');
    //     return redirect()->route('order.list');
    // }





    //not proper journal enrty vroucher 
    // public function delivery($id)
    // {
    //     $requisition = Requisition::findOrFail($id);
    //     $insufficientProducts = json_decode(request()->get('insufficient_products', '[]'), true);
    //     $paymentMethod = request()->get('payment_method');

    //     if (!is_array($insufficientProducts)) {
    //         $insufficientProducts = [];
    //     }

    //     $requisitionItems = RequisitionItem::where('requisition_id', $requisition->id)
    //                                     ->where('delivery', 0)
    //                                     ->get();

    //     // $invoiceNumber = 'INV-'.now()->format('YmdHis').'-'.str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);

    //     $invoiceNumber = 'INV-' . strtoupper(uniqid());

    //     foreach ($requisitionItems as $item) {
    //         $productId = $item->product_id;
    //         $productdescription = $item->description;
    //         $demandAmount = $item->demand_amount;

    //         if (in_array($productId, $insufficientProducts) || $item->purchase == 1 || $item->reject == 1) {
    //             continue;
    //         }

    //         $warehouseBatches = Branch_Product::whereHas('branch', function ($query) {
    //                 $query->where('type', 'Warehouse');
    //             })
    //             ->where('product_id', $productId)
    //             ->orderBy('batch', 'asc')
    //             ->get();

    //         $remainingDemand = $demandAmount;

    //         foreach ($warehouseBatches as $batch) {
    //             if ($remainingDemand <= 0) {
    //                 break;
    //             }

    //             if ($batch->stock > 0) {
    //                 $deductAmount = min($batch->stock, $remainingDemand);
    //                 $batch->stock -= $deductAmount;
    //                 $batch->save();

    //                 $existingDetails = $batch->details_stockout 
    //                     ? json_decode($batch->details_stockout, true) 
    //                     : [];

    //                 if (!is_array($existingDetails)) {
    //                     $existingDetails = [];
    //                 }

    //                 $existingDetails[] = [
    //                     'requisition' => $requisition->id,
    //                     'quantity'    => $deductAmount,
    //                     'date'        => now()->format('d-m-Y'),
    //                     'store_by'    => auth()->id(),
    //                     'invoice_no'  => $invoiceNumber,
    //                 ];

    //                 $batch->details_stockout = json_encode($existingDetails);
    //                 $batch->save();

    //                 $paymentMethodMap = [
    //                     'cash' => 'Cash In Hand',
    //                     'Bank' => 'Cash at Bank',
    //                     'due'  => 'Accounts Receivable',
    //                 ];
    //                 $paymentMethodDisplay = $paymentMethodMap[strtolower($paymentMethod)] ?? $paymentMethod;

    //                 $paymentMethodDisplay = ucfirst(strtolower($paymentMethod));

    //                 $chartAccount = ChartOfAccount::where('name', 'like', $paymentMethodDisplay)
    //                     ->orWhere('name', 'like', '%' . $paymentMethodDisplay . '%')
    //                     ->first(['id', 'code']);
                    
    //                 if (!$chartAccount) {
    //                     $chartAccount = ChartOfAccount::where('name', 'like', '%Cash%')
    //                         ->orWhere('name', 'like', '%Bank%')
    //                         ->first(['id', 'code']);
                        
    //                     if (!$chartAccount) {
    //                         $chartAccount = ChartOfAccount::first(['id', 'code']);
                            
    //                         if (!$chartAccount) {
    //                             Toastr::error("No Chart of Accounts configured in the system!", 'Error');
    //                             return redirect()->back();
    //                         }
    //                     }
    //                 }
                    
    //                 ProductLedger::create([
    //                     'entry_date'            => now()->format('Y-m-d'),
    //                     'narration'             => 'warehouse',
    //                     'type'                  => 'stockout',
    //                     'user_id'               => auth()->id(),
    //                     'branch_id'             => $batch->branch_id,
    //                     'product_id'            => $productId,
    //                     'quantity'              => $deductAmount,
    //                     'price'                 => $batch->price,
    //                     'batch'                 => $batch->batch,
    //                     'requisition_id'        => $requisition->id,
    //                     'payment_method'        => request()->get('payment_method'),
    //                     'invoice_no'            => $invoiceNumber,
    //                     'chart_of_account_id'   => $chartAccount->id,
    //                     'chart_of_account_code' => $chartAccount->code,
    //                 ]);

    //                 $remainingDemand -= $deductAmount;
    //             }
    //         }

    //         if ($remainingDemand == 0) {
    //             $item->update(['delivery' => 1]);
    //         }
    //     }

    //     $allDelivered = $requisitionItems->every(fn($item) => $item->delivery == 1);

    //     $requisition->update([
    //         'status' => 1,
    //         'partial_delivery' => $allDelivered ? 1 : 0,
    //     ]);

    //     $requisitionItemsDoneAll = RequisitionItem::where('requisition_id', $requisition->id)->get();

    //     $allProcessed = $requisitionItemsDoneAll->every(fn($item) => $item->delivery == 1 || $item->reject == 1);

    //     $requisition->update([
    //         'alldone_status' => $allProcessed ? 1 : 0,
    //     ]);









    //   //correction
    //     $journalEntry = JournalEntry::create([
    //         'date'        => now()->toDateString(),
    //         'reference'   => $invoiceNumber,
    //         'description' => 'Sales for requisition #' . $id,
    //         'journal_id'  => (JournalEntry::max('journal_id') ?? 99) + 1,
    //         'created_by'  => auth()->id(),
    //     ]);

    //     $totalAmount = 0;
    //     $journalItems = [];

    //     foreach ($requisitionItems as $item) {
    //         $productId = $item->product_id;
    //         $productdescription = $item->product_description;
    //         $demandAmount = $item->demand_amount;
    //         $inputPrice = $prices[$productId] ?? null;

    //         $product = Product::find($productId);
    //         $productChartAccount = null;
            
    //         if ($product && $product->code) {
    //             $productChartAccount = ProductAccountMap::where('product_code', $product->code)
    //                 ->first(['id', 'account_income_code']); 
    //             $productChartAccount = ChartOfAccount::where('code', $productChartAccount->account_income_code)
    //                 ->first(['id']); 
    //         }

    //         if ($productChartAccount) {
    //             $amount = $demandAmount * $batch->price;
    //             $totalAmount += $amount;
                
    //             $journalItems[] = [
    //                 'journal' => $journalEntry->id,
    //                 'account' => $productChartAccount->id,
    //                 'debit'   => 0.00,
    //                 'credit'  => $amount,
    //                 'description' => $productdescription,
    //             ];
    //         }

    //     }

    //     if ($totalAmount > 0) {
    //         foreach ($journalItems as $item) {
    //             JournalItem::create($item);
    //         }
    //         JournalItem::create([
    //             'journal' => $journalEntry->id,
    //             'account' => $chartAccount->id,
    //             'debit'   => $totalAmount,
    //             'credit'  => 0.00,
    //             'description' => 'Payment by ' . $paymentMethod,
    //         ]);
    //     }







        



    //     Toastr::success('Delivery updated successfully.', 'Success');
    //     return redirect()->route('order.list');
    // }








    //Done and all okay last
    // public function delivery($id)
    // {
    //     $requisition = Requisition::findOrFail($id);
    //     $insufficientProducts = json_decode(request()->get('insufficient_products', '[]'), true);
    //     $paymentMethod = request()->get('payment_method');
    
    //     if (!is_array($insufficientProducts)) {
    //         $insufficientProducts = [];
    //     }
    
    //     $requisitionItems = RequisitionItem::where('requisition_id', $requisition->id)
    //                                     ->where('delivery', 0)
    //                                     ->get();
    
    //     $invoiceNumber = 'INV-' . strtoupper(uniqid());
    
    //     // Add journal entry at the beginning
    //     $journalEntry = JournalEntry::create([
    //         'date'        => now()->toDateString(),
    //         'reference'   => $invoiceNumber,
    //         'description' => 'Sales for requisition #' . $id,
    //         'journal_id'  => (JournalEntry::max('journal_id') ?? 99) + 1,
    //         'created_by'  => auth()->id(),
    //     ]);
    
    //     $totalAmount = 0;
    //     $journalItems = [];
    
    //     foreach ($requisitionItems as $item) {
    //         $productId = $item->product_id;
    //         $productdescription = $item->product_description;
    //         $demandAmount = $item->demand_amount;
    
    //         if (in_array($productId, $insufficientProducts) || $item->purchase == 1 || $item->reject == 1) {
    //             continue;
    //         }
    
    //         $warehouseBatches = Branch_Product::whereHas('branch', function ($query) {
    //                 $query->where('type', 'Warehouse');
    //             })
    //             ->where('product_id', $productId)
    //             ->orderBy('batch', 'asc')
    //             ->get();
    
    //         $remainingDemand = $demandAmount;
    
    //         foreach ($warehouseBatches as $batch) {
    //             if ($remainingDemand <= 0) {
    //                 break;
    //             }
    
    //             if ($batch->stock > 0) {
    //                 $deductAmount = min($batch->stock, $remainingDemand);
    //                 $batch->stock -= $deductAmount;
    //                 $batch->save();
    
    //                 $existingDetails = $batch->details_stockout 
    //                     ? json_decode($batch->details_stockout, true) 
    //                     : [];
    
    //                 if (!is_array($existingDetails)) {
    //                     $existingDetails = [];
    //                 }
    
    //                 $existingDetails[] = [
    //                     'requisition' => $requisition->id,
    //                     'quantity'    => $deductAmount,
    //                     'date'        => now()->format('d-m-Y'),
    //                     'store_by'    => auth()->id(),
    //                     'invoice_no'  => $invoiceNumber,
    //                 ];
    
    //                 $batch->details_stockout = json_encode($existingDetails);
    //                 $batch->save();
    
    //                 $paymentMethodDisplay = ucfirst(strtolower($paymentMethod));
    //                 $chartAccount = ChartOfAccount::where('name', 'like', $paymentMethodDisplay)
    //                     ->orWhere('name', 'like', '%' . $paymentMethodDisplay . '%')
    //                     ->first(['id', 'code']);
    
    //                 if (!$chartAccount) {
    //                     $chartAccount = ChartOfAccount::where('name', 'like', '%Cash%')
    //                         ->orWhere('name', 'like', '%Bank%')
    //                         ->first(['id', 'code']);
    
    //                     if (!$chartAccount) {
    //                         $chartAccount = ChartOfAccount::first(['id', 'code']);
                            
    //                         if (!$chartAccount) {
    //                             Toastr::error("No Chart of Accounts configured in the system!", 'Error');
    //                             return redirect()->back();
    //                         }
    //                     }
    //                 }
    
    //                 ProductLedger::create([
    //                     'entry_date'            => now()->format('Y-m-d'),
    //                     'narration'             => 'warehouse',
    //                     'type'                  => 'stockout',
    //                     'user_id'               => auth()->id(),
    //                     'branch_id'             => $batch->branch_id,
    //                     'product_id'            => $productId,
    //                     'quantity'              => $deductAmount,
    //                     'price'                 => $batch->price,
    //                     'batch'                 => $batch->batch,
    //                     'requisition_id'        => $requisition->id,
    //                     'payment_method'        => request()->get('payment_method'),
    //                     'invoice_no'            => $invoiceNumber,
    //                     'chart_of_account_id'   => $chartAccount->id,
    //                     'chart_of_account_code' => $chartAccount->code,
    //                 ]);
    
    //                 // Calculate amount using batch price
    //                 $amount = $deductAmount * $batch->price;
    //                 $totalAmount += $amount;
    
    //                 // Prepare journal item for this batch
    //                 $product = Product::find($productId);
    //                 $productChartAccount = null;
                    
    //                 if ($product && $product->code) {
    //                     $map = ProductAccountMap::where('product_code', $product->code)
    //                         ->first(['account_income_code']);
    //                     if ($map && $map->account_income_code) {
    //                         $productChartAccount = ChartOfAccount::where('code', $map->account_income_code)
    //                             ->first(['id']);
    //                     }
    //                 }
    
    //                 if ($productChartAccount) {
    //                     $journalItems[] = [
    //                         'journal' => $journalEntry->id,
    //                         'account' => $productChartAccount->id,
    //                         'debit'   => 0.00,
    //                         'credit'  => $amount,
    //                         'description' => $productdescription,
    //                         'date'    => $journalEntry->date, // <-- added line
    //                         // 'description' => $productdescription . ' (Batch: ' . $batch->batch . ')',
    //                     ];
    //                 }
    
    //                 $remainingDemand -= $deductAmount;
    //             }
    //         }
    
    //         if ($remainingDemand == 0) {
    //             $item->update(['delivery' => 1]);
    //         }
    //     }
    
    //     // Create all journal items
    //     foreach ($journalItems as $item) {
    //         JournalItem::create($item);
    //     }
    
    //     // Create the contra entry for payment
    //     if ($totalAmount > 0) {
    //         JournalItem::create([
    //             'journal' => $journalEntry->id,
    //             'account' => $chartAccount->id,
    //             'debit'   => $totalAmount,
    //             'credit'  => 0.00,
    //             // 'description' => 'Payment by ' . $paymentMethod,
    //             'description' => 'Received by ' . $paymentMethod,
    //             'date'    => $journalEntry->date, // <-- added line
    //         ]);
    //     }
    
    //     $allDelivered = $requisitionItems->every(fn($item) => $item->delivery == 1);
    
    //     $requisition->update([
    //         'status' => 1,
    //         'partial_delivery' => $allDelivered ? 1 : 0,
    //     ]);
    
    //     $requisitionItemsDoneAll = RequisitionItem::where('requisition_id', $requisition->id)->get();
    
    //     $allProcessed = $requisitionItemsDoneAll->every(fn($item) => $item->delivery == 1 || $item->reject == 1);
    
    //     $requisition->update([
    //         'alldone_status' => $allProcessed ? 1 : 0,
    //     ]);
    
    //     Toastr::success('Delivery updated successfully.', 'Success');
    //     return redirect()->route('order.list');
    // }



    

    public function delivery($id)
    {
        $requisition = Requisition::findOrFail($id);
        $insufficientProducts = json_decode(request()->get('insufficient_products', '[]'), true);
        $paymentMethod = request()->get('payment_method');

        if (!is_array($insufficientProducts)) {
            $insufficientProducts = [];
        }

        $requisitionItems = RequisitionItem::where('requisition_id', $requisition->id)
                                        ->where('delivery', 0)
                                        ->get();

        $invoiceNumber = 'INV-' . strtoupper(uniqid());

        $journalEntry = JournalEntry::create([
            'date'        => now()->toDateString(),
            'reference'   => $invoiceNumber,
            'description' => 'Sales for requisition #' . $id,
            'journal_id'  => (JournalEntry::max('journal_id') ?? 99) + 1,
            'created_by'  => auth()->id(),
        ]);

        $totalAmount = 0;
        $journalItems = [];

        foreach ($requisitionItems as $item) {
            $productId = $item->product_id;
            $productdescription = $item->product_description;
            $demandAmount = $item->demand_amount;

            if (in_array($productId, $insufficientProducts) || $item->purchase == 1 || $item->reject == 1) {
                continue;
            }

            $warehouseBatches = Branch_Product::whereHas('branch', function ($query) {
                    $query->where('type', 'Warehouse');
                })
                ->where('product_id', $productId)
                ->orderBy('batch', 'asc')
                ->get();

            $remainingDemand = $demandAmount;

            foreach ($warehouseBatches as $batch) {
                if ($remainingDemand <= 0) {
                    break;
                }

                if ($batch->stock > 0) {
                    $deductAmount = min($batch->stock, $remainingDemand);
                    $batch->stock -= $deductAmount;
                    $batch->save();

                    $existingDetails = $batch->details_stockout 
                        ? json_decode($batch->details_stockout, true) 
                        : [];

                    if (!is_array($existingDetails)) {
                        $existingDetails = [];
                    }

                    $existingDetails[] = [
                        'requisition' => $requisition->id,
                        'quantity'    => $deductAmount,
                        'date'        => now()->format('d-m-Y'),
                        'store_by'    => auth()->id(),
                        'invoice_no'  => $invoiceNumber,
                    ];

                    $batch->details_stockout = json_encode($existingDetails);
                    $batch->save();

                    $paymentMethodDisplay = ucfirst(strtolower($paymentMethod));
                    $chartAccount = ChartOfAccount::where('name', 'like', $paymentMethodDisplay)
                        ->orWhere('name', 'like', '%' . $paymentMethodDisplay . '%')
                        ->first(['id', 'code']);

                    if (!$chartAccount) {
                        $chartAccount = ChartOfAccount::where('name', 'like', '%Cash%')
                            ->orWhere('name', 'like', '%Bank%')
                            ->first(['id', 'code']);

                        if (!$chartAccount) {
                            $chartAccount = ChartOfAccount::first(['id', 'code']);
                            
                            if (!$chartAccount) {
                                Toastr::error("No Chart of Accounts configured in the system!", 'Error');
                                return redirect()->back();
                            }
                        }
                    }

                    ProductLedger::create([
                        'entry_date'            => now()->format('Y-m-d'),
                        'narration'             => 'warehouse',
                        'type'                  => 'stockout',
                        'user_id'               => auth()->id(),
                        'branch_id'             => $batch->branch_id,
                        'product_id'            => $productId,
                        'quantity'              => $deductAmount,
                        'price'                 => $batch->price,
                        'batch'                 => $batch->batch,
                        'requisition_id'        => $requisition->id,
                        'payment_method'        => request()->get('payment_method'),
                        'invoice_no'            => $invoiceNumber,
                        'chart_of_account_id'   => $chartAccount->id,
                        'chart_of_account_code' => $chartAccount->code,
                    ]);

                    $amount = $deductAmount * $batch->price;
                    $totalAmount += $amount;

                    $product = Product::find($productId);
                    $productChartAccount = null;
                    
                    if ($product && $product->code) {
                        $map = ProductAccountMap::where('product_code', $product->code)
                            ->first(['account_income_code']);
                        if ($map && $map->account_income_code) {
                            $productChartAccount = ChartOfAccount::where('code', $map->account_income_code)
                                ->first(['id']);
                        }
                    }

                    if ($productChartAccount) {
                        $journalItems[] = [
                            'journal' => $journalEntry->id,
                            'account' => $productChartAccount->id,
                            'debit'   => 0.00,
                            'credit'  => $amount,
                            'description' => $productdescription,
                            'date'    => $journalEntry->date,
                        ];
                    }

                    $remainingDemand -= $deductAmount;
                }
            }

            if ($remainingDemand == 0) {
                $item->update(['delivery' => 1]);
            }
        }

        foreach ($journalItems as $item) {
            JournalItem::create($item);
        }

        if ($totalAmount > 0) {
            JournalItem::create([
                'journal' => $journalEntry->id,
                'account' => $chartAccount->id,
                'debit'   => $totalAmount,
                'credit'  => 0.00,
                'description' => 'Received by ' . $paymentMethod,
                'date'    => $journalEntry->date,
            ]);
        }

        $allDelivered = $requisitionItems->every(fn($item) => $item->delivery == 1);

        $requisition->update([
            'status' => 1,
            'partial_delivery' => $allDelivered ? 1 : 0,
        ]);

        $requisitionItemsDoneAll = RequisitionItem::where('requisition_id', $requisition->id)->get();

        $allProcessed = $requisitionItemsDoneAll->every(fn($item) => $item->delivery == 1 || $item->reject == 1);

        $requisition->update([
            'alldone_status' => $allProcessed ? 1 : 0,
        ]);

        $processedItems = RequisitionItem::where('requisition_id', $requisition->id)
            ->where(function($query) {
                $query->where('delivery', 1)
                    ->orWhere('reject', 1);
            })
            ->get();
        
        foreach ($processedItems as $item) {
            $productId = $item->product_id;
            
            $ledgerEntries = ProductLedger::where('requisition_id', $requisition->id)
                ->where('product_id', $productId)
                ->where('type', 'stockout')
                ->get();
            
            if ($ledgerEntries->isNotEmpty()) {
                $priceQuantityData = [];
                $branchId = null;
                
                foreach ($ledgerEntries as $entry) {
                    $priceQuantityData[] = [
                        "price" => $entry->price,
                        "quantity" => $entry->quantity
                    ];
                    
                    if (is_null($branchId)) {
                        $branchId = $entry->branch_id;
                    }
                }
                
                BranchHeadofficeLog::create([
                    'branch_id' => $requisition->branch_id,
                    'requisition_id' => $requisition->id,
                    'product_id' => $productId,
                    'price_quantity' => json_encode($priceQuantityData),
                    'date' => now(),
                    'user_id' => $requisition->user_id
                ]);
            }
        }

        Toastr::success('Delivery updated successfully.', 'Success');
        return redirect()->route('order.list');
    }














    // public function deliveryCheck($id)
    // {
    //     $requisition = Requisition::findOrFail($id);
    //     $requisitionItems = RequisitionItem::where('requisition_id', $requisition->id)->get();
    
    //     $allDetails = [];
    
    //     foreach ($requisitionItems as $item) {
    //         $productId = $item->product_id;
    //         $demandAmount = $item->demand_amount;
    
    //         $product = Product::find($productId);
    //         $productName = $product ? $product->name : "Unknown Product";
    
    //         $warehouseBranchProduct = Branch_Product::whereHas('branch', function ($query) {
    //                 $query->where('type', 'Warehouse');
    //             })
    //             ->where('product_id', $productId)
    //             ->first();
    
    //         $stock = $warehouseBranchProduct ? $warehouseBranchProduct->stock : 0;
    
    //         $allDetails[] = [
    //             'product_id' => $productId, // Add this line
    //             'product_name' => $productName,
    //             'stock' => $stock,
    //             'demand_amount' => $demandAmount,
    //             'is_insufficient' => $demandAmount > $stock,
    //         ];
    //     }
    
    //     session()->flash('allDetails', $allDetails);
    //     session()->flash('showModal', true);
    
    //     return redirect()->back();
    // }
    

    

    //done and working

    // public function deliveryCheck($id)
    // {
    //     $requisition = Requisition::findOrFail($id);
    //     $requisitionItems = RequisitionItem::where('requisition_id', $requisition->id)
    //         ->where('delivery', '!=', 1) 
    //         ->where('reject', '!=', 1)  
    //         ->get();

    //     $allDetails = [];

    //     foreach ($requisitionItems as $item) {
    //         $productId = $item->product_id;
    //         $demandAmount = $item->demand_amount;

    //         $product = Product::find($productId);
    //         $productName = $product ? $product->name : "Unknown Product";

    //         $warehouseBranchProduct = Branch_Product::whereHas('branch', function ($query) {
    //                 $query->where('type', 'Warehouse');
    //             })
    //             ->where('product_id', $productId)
    //             ->first();

    //         $stock = $warehouseBranchProduct ? $warehouseBranchProduct->stock : 0;

    //         $allDetails[] = [
    //             'dreq_id' => $requisition->id,
    //             'product_id' => $productId,
    //             'product_name' => $productName,
    //             'stock' => $stock,
    //             'demand_amount' => $demandAmount,
    //             'is_insufficient' => $demandAmount > $stock,
    //             'purchase' => $item->purchase ?? 0,
    //         ];
    //     }

    //     session()->flash('allDetailsdelivery', $allDetails);
    //     session()->flash('showModal', true);

    //     return redirect()->back();
    // }


   
     //before multiple price and stock-deamnd so need quantity

    // public function deliveryCheck($id)
    // {
    //     $requisition = Requisition::findOrFail($id);
    //     $requisitionItems = RequisitionItem::where('requisition_id', $requisition->id)
    //         ->where('delivery', '!=', 1) 
    //         ->where('reject', '!=', 1)  
    //         ->get();
    
    //     $allDetails = [];
    //     $allInProgress = true; // Assume all are in progress
    
    //     foreach ($requisitionItems as $item) {
    //         $productId = $item->product_id;
    //         $demandAmount = $item->demand_amount;
    
    //         $product = Product::find($productId);
    //         $productName = $product ? $product->name : "Unknown Product";
    
    //         $warehouseBranchProduct = Branch_Product::whereHas('branch', function ($query) {
    //                 $query->where('type', 'Warehouse');
    //             })
    //             ->where('product_id', $productId)
    //             ->first();
    
    //         $stock = $warehouseBranchProduct ? $warehouseBranchProduct->stock : 0;
    
    //         $isInProgress = $item->purchase == 1;
    //         if (!$isInProgress) {
    //             $allInProgress = false; // If any item is NOT in progress, set to false
    //         }
    
    //         $allDetails[] = [
    //             'dreq_id' => $requisition->id,
    //             'product_id' => $productId,
    //             'product_name' => $productName,
    //             'stock' => $stock,
    //             'demand_amount' => $demandAmount,
    //             'is_insufficient' => $demandAmount > $stock,
    //             'purchase' => $item->purchase ?? 0,
    //         ];
    //     }
    
    //     session()->flash('allDetailsdelivery', $allDetails);
    //     session()->flash('showModal', true);
    //     session()->flash('allInProgress', $allInProgress); // Pass this to session
    
    //     return redirect()->back();
    // }
    







    public function deliveryCheck($id)
    {
        $requisition = Requisition::findOrFail($id);
        $requisitionItems = RequisitionItem::where('requisition_id', $requisition->id)
            ->where('delivery', '!=', 1)
            ->where('reject', '!=', 1)
            ->get();

        $allDetails = [];
        $allInProgress = true;

        foreach ($requisitionItems as $item) {
            $productId = $item->product_id;
            $demandAmount = $item->demand_amount;

            $product = Product::find($productId);
            $productName = $product ? $product->name : "Unknown Product";

            // Fetch batch-wise stock details for warehouse branches
            $warehouseStocks = Branch_Product::whereHas('branch', function ($query) {
                    $query->where('type', 'Warehouse');
                })
                ->where('product_id', $productId)
                ->get()
                ->groupBy('product_id');

            $totalStock = 0;
            $batchDetails = [];

            if ($warehouseStocks->isNotEmpty()) {
                foreach ($warehouseStocks as $batches) {
                    foreach ($batches as $batch) {
                        $totalStock += $batch->stock;
                        $batchDetails[] = "(Batch: {$batch->batch}, {$batch->stock})";
                    }
                }
            }

            $isInProgress = $item->purchase == 1;
            if (!$isInProgress) {
                $allInProgress = false;
            }

            $allDetails[] = [
                'dreq_id' => $requisition->id,
                'product_id' => $productId,
                'product_name' => $productName,
                'stock' => $totalStock,
                'batch_details' => implode(' ', $batchDetails), // Combine batch info into a string
                'demand_amount' => $demandAmount,
                'is_insufficient' => $demandAmount > $totalStock,
                'purchase' => $item->purchase ?? 0,
            ];
        }

        session()->flash('allDetailsdelivery', $allDetails);
        session()->flash('showModal', true);
        session()->flash('allInProgress', $allInProgress);

        return redirect()->back();
    }
















    


    public function pendingList()
    {
        $requisitions = Requisition::where('status', 1)->get();
        return view('requisition.pendinglist', compact('requisitions'));
    }
    
    public function pendingListView($id)
    {
        $requisitionheading = Requisition::where('status', 1)->find($id);
        $requisitionlist = RequisitionItem::where('requisition_id', $id)->get();

        return view('requisition.pendinglistview', compact('requisitionheading', 'requisitionlist'));
    }



  //old only stock add

    // public function pendingListInstock($id)
    // {
    //     $requisition = Requisition::findOrFail($id);

    //     $requisition->update(['status' => 3]);

    //     $requisitionItems = RequisitionItem::where('requisition_id', $requisition->id)->get();
        
    //     foreach ($requisitionItems as $item) {
    //         $productId = $item->product_id;
    //         $stockLevel = $item->demand_amount; 
    //         $branchId = $requisition->branch_id;
            
    //         $branchProduct = Branch_Product::where('branch_id', $branchId)
    //                                     ->where('product_id', $productId)
    //                                     ->first();
            
    //         if ($branchProduct) {
    //             $branchProduct->stock += $stockLevel;
    //             $branchProduct->save();
    //         } else {
    //             Branch_Product::create([
    //                 'branch_id' => $branchId,
    //                 'product_id' => $productId,
    //                 'stock' => $stockLevel,
    //             ]);
    //         }
    //     }

    //     Toastr::success('Stock updated successfully.', 'Success');

    //     return redirect()->route('requisition.pending-list');
    // }




    public function pendingListInstock($id)
    {
        $requisition = Requisition::findOrFail($id);
        $requisition->update(['status' => 3]);
        $requisitionItems = RequisitionItem::where('requisition_id', $requisition->id)->get();
    
        foreach ($requisitionItems as $item) {
            $productId = $item->product_id;
            $stockLevel = $item->demand_amount;
            $branchId = $requisition->branch_id;
    
            $branchProduct = Branch_Product::where('branch_id', $branchId)
                                           ->where('product_id', $productId)
                                           ->first();
    
            if ($branchProduct) {
                $branchProduct->stock += $stockLevel;
                $branchProduct->save();
            } else {
                Branch_Product::create([
                    'branch_id' => $branchId,
                    'product_id' => $productId,
                    'stock' => $stockLevel,
                ]);
            }
    
            $warehouseBranchProduct = Branch_Product::whereHas('branch', function ($query) {
                    $query->where('type', 'Warehouse');
                })
                ->where('product_id', $productId)
                ->first();
    
            if ($warehouseBranchProduct) {
                $warehouseBranchProduct->stock -= $stockLevel;
                $warehouseBranchProduct->save();
            }
        }
    
        Toastr::success('Stock updated successfully.', 'Success');
    
        return redirect()->route('requisition.pending-list');
    }
    


    


    

    public function purchaseRequisition()
    {
        $requisitions = Requisition::where('status', 2)->get();

        return view('requisition.purchaselist', compact('requisitions'));
    }


    public function purchaseRequisitionView($id)
    {
        $requisitionheading = Requisition::where('status', 2)->find($id);
        $requisitionlist = RequisitionItem::where('requisition_id', $id)->get();

        return view('requisition.purchaselistview', compact('requisitionheading', 'requisitionlist'));
    }


    public function updateAcceptdelivery($id)
    {
        $requisition = Requisition::findOrFail($id);
        $requisition->update(['status' => 1]);
    
        Toastr::success('Collection updated successfully.', 'Success');
        return redirect()->route('purcahse.requisition');
    }



    // public function stockList()
    // {
    //     $user_id = Auth::id();
    //     $user = DB::table('users')->where('id', $user_id)->first();
    //     $branchId = $user->branch_id;
    //     $branch = DB::table('branch')->where('id', $branchId)->first();
    //     $branchType = $branch->type;
    
    //     if ($branchType === 'Headoffice' || $branchType === 'Warehouse') {
    //         $requisitions = Branch_Product::select('branch_id', DB::raw('SUM(stock) as total_stock'))
    //             ->groupBy('branch_id')
    //             ->get();
    //     } else {
    //         $requisitions = Branch_Product::select('branch_id', DB::raw('SUM(stock) as total_stock'))
    //             ->where('branch_id', $branchId)
    //             ->groupBy('branch_id')
    //             ->get();
    //     }
    
    //     return view('requisition.stocklist', compact('requisitions'));
    // }


    // public function stockList()
    // {
    //     $user_id = Auth::id();
    //     $user = DB::table('users')->where('id', $user_id)->first();
    //     $branchId = $user->branch_id;
    //     $branch = DB::table('branch')->where('id', $branchId)->first();
    //     $branchType = $branch->type;

    //     if ($branchType === 'Headoffice' || $branchType === 'Warehouse') {
    //         $requisitions = Branch_Product::select('branch_id', DB::raw('SUM(stock) as total_stock'))
    //             ->groupBy('branch_id')
    //             ->with('branch')
    //             ->get();
    //     } else {
    //         $requisitions = Branch_Product::select('branch_id', DB::raw('SUM(stock) as total_stock'))
    //             ->where('branch_id', $branchId)
    //             ->groupBy('branch_id')
    //             ->with('branch')
    //             ->get();
    //     }

    //     $requisitions = $requisitions->filter(function ($requisition) {
    //         return $requisition->branch !== null;
    //     });

    //     return view('requisition.stocklist', compact('requisitions'));
    // }





    // public function stockList()
    // {
    //     $user_id = Auth::id();
    //     $user = DB::table('users')->where('id', $user_id)->first();
    //     $branchId = $user->branch_id;
    //     $branch = DB::table('branch')->where('id', $branchId)->first();
    //     $branchType = $branch->type;
    
    //     if ($branchType === 'Headoffice') {
    //         $requisitions = Branch_Product::select('branch_id', DB::raw('SUM(stock) as total_stock'))
    //             ->groupBy('branch_id')
    //             ->with('branch')
    //             ->get();
    //     } elseif ($branchType === 'Warehouse') {
    //         $requisitions = Branch_Product::select('branch_id', DB::raw('SUM(stock) as total_stock'))
    //             ->where('branch_id', $branchId)
    //             ->groupBy('branch_id')
    //             ->with('branch')
    //             ->get();
    //     } else {
    //         $requisitions = Branch_Product::select('branch_id', DB::raw('SUM(stock) as total_stock'))
    //             ->where('branch_id', $branchId)
    //             ->groupBy('branch_id')
    //             ->with('branch')
    //             ->get();
    //     }
    //     $requisitions = $requisitions->filter(function ($requisition) {
    //         return $requisition->branch !== null;
    //     });
    
    //     return view('requisition.stocklist', compact('requisitions'));
    // }
    



    public function stockList()
    {
        $user_id = Auth::id();
        $user = DB::table('users')->where('id', $user_id)->first();
        $branchId = $user->branch_id;
        $branch = DB::table('branch')->where('id', $branchId)->first();
        $branchType = $branch->type;
    
        $showTotalStock = $branchType === 'Headoffice';
    
        if ($branchType === 'Headoffice') {

            $requisitions = Branch_Product::select('branch_id', DB::raw('SUM(stock) as total_stock'))
                ->whereHas('branch', function ($query) {
                    $query->where('type', '!=', 'PurchaseTeam');
                })
                ->groupBy('branch_id')
                ->with('branch')
                ->get();


        } elseif ($branchType === 'Warehouse') {
            $requisitions = Branch_Product::select('branch_id', DB::raw('SUM(stock) as total_stock'))
                ->where('branch_id', $branchId)
                ->groupBy('branch_id')
                ->with('branch')
                ->get();
        } else {
            $requisitions = Branch_Product::select('branch_id', DB::raw('SUM(stock) as total_stock'))
                ->where('branch_id', $branchId)
                ->groupBy('branch_id')
                ->with('branch')
                ->get();
        }
    
        $requisitions = $requisitions->filter(function ($requisition) {
            return $requisition->branch !== null;
        });
    
        return view('requisition.stocklist', compact('requisitions', 'showTotalStock'));
    }
    







    

    // public function stockView($branch_id)
    // {
    //     $branch = DB::table('branch')->where('id', $branch_id)->first();
    //     $products = Branch_Product::where('branch_id', $branch_id)->get();

    //     return view('requisition.stockview', compact('branch', 'products'));
    // }


    //old work before multiple price

    // public function stockView($branch_id)
    // {
    //     $user = Auth::user();
    //     $branch = DB::table('branch')->where('id', $branch_id)->first();
    //     $products = Branch_Product::where('branch_id', $branch_id)->get();
    //     $role = $user->role_name;
    //     return view('requisition.stockview', compact('branch', 'products', 'role'));
    // }




    //okay old before return show
    // public function stockView($branch_id)
    // {
    //     $user = Auth::user();
    //     $branch = DB::table('branch')->where('id', $branch_id)->first();
        
    //     $products = Branch_Product::where('branch_id', $branch_id)
    //         ->orderBy('product_id')
    //         ->orderBy('batch') 
    //         ->get();

    //     $role = $user->role_name; 

    //     $paymentMethods = ChartOfAccount::whereIn('id', [54, 55, 58])->get(); 


    //     $productReturnCounts = DB::table('product_returns')
    //         ->where('branch_id', auth()->user()->branch_id)
    //         ->where('user_id', auth()->id())
    //         ->where('status', 0)
    //         ->select('product_id', DB::raw('count(*) as total'))
    //         ->groupBy('product_id')
    //         ->pluck('total', 'product_id'); 


        
    //     return view('requisition.stockview', compact('branch', 'products', 'role', 'paymentMethods', 'productReturnCounts'));
    // }


    
    

    // public function stockView($branch_id)
    // {
    //     $user = Auth::user();
    //     $branch = DB::table('branch')->where('id', $branch_id)->first();

    //     $products = Branch_Product::where('branch_id', $branch_id)
    //         ->orderBy('product_id')
    //         ->orderBy('batch')
    //         ->get();

    //     $role = $user->role_name;
    //     $paymentMethods = ChartOfAccount::whereIn('id', [54, 55, 58])->get();

    //     $productReturnCounts = collect(); 

    //     if ($role === 'Admin') {
    //         $users = DB::table('users')
    //             ->join('branch', 'users.branch_id', '=', 'branch.id')
    //             ->whereIn('branch.type', ['Branch', 'Headoffice'])
    //             ->pluck('users.id');

    //         $productReturnCounts = DB::table('product_returns')
    //             ->where('status', 0)
    //             ->whereIn('user_id', $users)
    //             ->select('product_id', DB::raw('count(*) as total'))
    //             ->groupBy('product_id')
    //             ->pluck('total', 'product_id');
    //     } else {
    //         $userBranch = DB::table('branch')->where('id', $user->branch_id)->first();

    //         if ($userBranch->type === 'Branch' || $userBranch->type === 'Headoffice') {
    //             $productReturnCounts = DB::table('product_returns')
    //                 ->where('branch_id', $user->branch_id)
    //                 ->where('user_id', $user->id)
    //                 ->where('status', 0)
    //                 ->select('product_id', DB::raw('count(*) as total'))
    //                 ->groupBy('product_id')
    //                 ->pluck('total', 'product_id');
    //         }

    //         // If Warehouse, do nothing (returns empty)
    //     }

    //     return view('requisition.stockview', compact('branch', 'products', 'role', 'paymentMethods', 'productReturnCounts'));
    // }



   //okay but admin can't see warehouse 
    // public function stockView($branch_id)
    // {
    //     $user = Auth::user();
    //     $branch = DB::table('branch')->where('id', $branch_id)->first();

    //     $products = Branch_Product::where('branch_id', $branch_id)
    //         ->orderBy('product_id')
    //         ->orderBy('batch')
    //         ->get();

    //     $role = $user->role_name;
    //     $paymentMethods = ChartOfAccount::whereIn('id', [54, 55, 58])->get();

    //     $productReturnCounts = collect();

    //     if ($role === 'Admin') {
    //         if ($branch->type !== 'Warehouse') {
    //             $userIdsInBranch = DB::table('users')
    //                 ->where('branch_id', $branch_id)
    //                 ->pluck('id');

    //             $productReturnCounts = DB::table('product_returns')
    //                 ->where('branch_id', $branch_id)
    //                 ->whereIn('user_id', $userIdsInBranch)
    //                 ->where('status', 0)
    //                 // ->select('product_id', DB::raw('count(*) as total'))
    //                 ->select('product_id', DB::raw('SUM(return_quantity) as total'))
    //                 ->groupBy('product_id')
    //                 ->pluck('total', 'product_id');
    //         }
    //     } else {
    //         $userBranch = DB::table('branch')->where('id', $user->branch_id)->first();

    //         $userIdsInBranch = DB::table('users')
    //                 ->where('branch_id', $branch_id)
    //                 ->pluck('id');


    //         if ($userBranch->type === 'Branch') {
    //             $productReturnCounts = DB::table('product_returns')
    //                 ->where('branch_id', $user->branch_id)
    //                 ->where('user_id', $user->id)
    //                 ->where('status', 0)
    //                 // ->select('product_id', DB::raw('count(*) as total'))
    //                 ->select('product_id', DB::raw('SUM(return_quantity) as total'))
    //                 ->groupBy('product_id')
    //                 ->pluck('total', 'product_id');
    //         }
    //         elseif ($userBranch->type === 'Headoffice') {

    //             $productReturnCounts = DB::table('product_returns')
    //                 ->where('branch_id', $branch_id)
    //                 ->whereIn('user_id', $userIdsInBranch)
    //                 ->where('status', 0)
    //                 // ->select('product_id', DB::raw('count(*) as total'))
    //                 ->select('product_id', DB::raw('SUM(return_quantity) as total'))
    //                 ->groupBy('product_id')
    //                 ->pluck('total', 'product_id');

    //         } 

    //         elseif ($userBranch->type === 'Warehouse') {

    //             $productReturnCounts = DB::table('product_return_warehouses')
    //                 ->where('branch_id', $branch_id)
    //                 ->whereIn('user_id', $userIdsInBranch)
    //                 ->where('status', 0)
    //                 ->select('product_id', 'price', DB::raw('SUM(return_quantity) as total'))
    //                 ->groupBy('product_id', 'price')
    //                 ->get()
    //                 ->mapWithKeys(function ($item) {
    //                     return [ $item->product_id . '_' . $item->price => $item->total ];
    //                 });
    //         }
            

    //     }

    //     return view('requisition.stockview', compact('branch', 'products', 'role', 'paymentMethods', 'productReturnCounts'));
    // }



    //all okay but headoffice can't see the warehgouse return 
    // public function stockView($branch_id)
    // {
    //     $user = Auth::user();
    //     $branch = DB::table('branch')->where('id', $branch_id)->first();

    //     $products = Branch_Product::where('branch_id', $branch_id)
    //         ->orderBy('product_id')
    //         ->orderBy('batch')
    //         ->get();

    //     $role = $user->role_name;
    //     $paymentMethods = ChartOfAccount::whereIn('id', [54, 55, 58])->get();

    //     $productReturnCounts = collect();
    //     $userIdsInBranch = DB::table('users')
    //         ->where('branch_id', $branch_id)
    //         ->pluck('id');

    //     if ($role === 'Admin') {
    //         if ($branch->type === 'Warehouse') {
    //             $productReturnCounts = DB::table('product_return_warehouses')
    //                 ->where('branch_id', $branch_id)
    //                 ->whereIn('user_id', $userIdsInBranch)
    //                 ->where('status', 0)
    //                 ->select('product_id', 'price', DB::raw('SUM(return_quantity) as total'))
    //                 ->groupBy('product_id', 'price')
    //                 ->get()
    //                 ->mapWithKeys(function ($item) {
    //                     return [$item->product_id . '_' . $item->price => $item->total];
    //                 });
    //         } else {
    //             $productReturnCounts = DB::table('product_returns')
    //                 ->where('branch_id', $branch_id)
    //                 ->whereIn('user_id', $userIdsInBranch)
    //                 ->where('status', 0)
    //                 ->select('product_id', DB::raw('SUM(return_quantity) as total'))
    //                 ->groupBy('product_id')
    //                 ->pluck('total', 'product_id');
    //         }
    //     } else {
    //         $userBranch = DB::table('branch')->where('id', $user->branch_id)->first();

    //         if ($userBranch->type === 'Branch') {
    //             $productReturnCounts = DB::table('product_returns')
    //                 ->where('branch_id', $user->branch_id)
    //                 ->where('user_id', $user->id)
    //                 ->where('status', 0)
    //                 ->select('product_id', DB::raw('SUM(return_quantity) as total'))
    //                 ->groupBy('product_id')
    //                 ->pluck('total', 'product_id');
    //         } elseif ($userBranch->type === 'Headoffice') {
    //             $productReturnCounts = DB::table('product_returns')
    //                 ->where('branch_id', $branch_id)
    //                 ->whereIn('user_id', $userIdsInBranch)
    //                 ->where('status', 0)
    //                 ->select('product_id', DB::raw('SUM(return_quantity) as total'))
    //                 ->groupBy('product_id')
    //                 ->pluck('total', 'product_id');
    //         } elseif ($userBranch->type === 'Warehouse') {
    //             $productReturnCounts = DB::table('product_return_warehouses')
    //                 ->where('branch_id', $branch_id)
    //                 ->whereIn('user_id', $userIdsInBranch)
    //                 ->where('status', 0)
    //                 ->select('product_id', 'price', DB::raw('SUM(return_quantity) as total'))
    //                 ->groupBy('product_id', 'price')
    //                 ->get()
    //                 ->mapWithKeys(function ($item) {
    //                     return [$item->product_id . '_' . $item->price => $item->total];
    //                 });
    //         }
    //     }

    //     return view('requisition.stockview', compact(
    //         'branch',
    //         'products',
    //         'role',
    //         'paymentMethods',
    //         'productReturnCounts'
    //     ));
    // }




    public function stockView($branch_id)
    {
        $user = Auth::user();
        $branch = DB::table('branch')->where('id', $branch_id)->first();

        $products = Branch_Product::where('branch_id', $branch_id)
            ->orderBy('product_id')
            ->orderBy('batch')
            ->get();

        $role = $user->role_name;
        $paymentMethods = ChartOfAccount::whereIn('id', [54, 55, 58])->get();

        $productReturnCounts = collect();
        $userIdsInBranch = DB::table('users')
            ->where('branch_id', $branch_id)
            ->pluck('id');

        // For Admin or Headoffice users
        if ($role === 'Admin' || ($role === 'Headoffice' && $user->branch->type === 'Headoffice')) {
            if ($branch->type === 'Warehouse') {
                $productReturnCounts = DB::table('product_return_warehouses')
                    ->where('branch_id', $branch_id)
                    ->whereIn('user_id', $userIdsInBranch)
                    ->where('status', 0)
                    ->select('product_id', 'price', DB::raw('SUM(return_quantity) as total'))
                    ->groupBy('product_id', 'price')
                    ->get()
                    ->mapWithKeys(function ($item) {
                        return [$item->product_id . '_' . $item->price => $item->total];
                    });
            } else {
                $productReturnCounts = DB::table('product_returns')
                    ->where('branch_id', $branch_id)
                    ->whereIn('user_id', $userIdsInBranch)
                    ->where('status', 0)
                    ->select('product_id', DB::raw('SUM(return_quantity) as total'))
                    ->groupBy('product_id')
                    ->pluck('total', 'product_id');
            }
        } else {
            // For other users (existing logic remains the same)
            $userBranch = DB::table('branch')->where('id', $user->branch_id)->first();

            if ($userBranch->type === 'Branch') {
                $productReturnCounts = DB::table('product_returns')
                    ->where('branch_id', $user->branch_id)
                    ->where('user_id', $user->id)
                    ->where('status', 0)
                    ->select('product_id', DB::raw('SUM(return_quantity) as total'))
                    ->groupBy('product_id')
                    ->pluck('total', 'product_id');
            } elseif ($userBranch->type === 'Warehouse') {
                $productReturnCounts = DB::table('product_return_warehouses')
                    ->where('branch_id', $branch_id)
                    ->whereIn('user_id', $userIdsInBranch)
                    ->where('status', 0)
                    ->select('product_id', 'price', DB::raw('SUM(return_quantity) as total'))
                    ->groupBy('product_id', 'price')
                    ->get()
                    ->mapWithKeys(function ($item) {
                        return [$item->product_id . '_' . $item->price => $item->total];
                    });
            }
        }

        return view('requisition.stockview', compact(
            'branch',
            'products',
            'role',
            'paymentMethods',
            'productReturnCounts'
        ));
    }













    //working and done before price multiple
    // public function addStock(Request $request)
    // {
    //     // dd($request->all());
    
    //     $request->validate([
    //         'product_id' => 'required',
    //         'stock' => 'required|integer|min:1',
    //         'branch_id' => 'required',
    //     ]);
    
    //     $branchProduct = Branch_Product::where('branch_id', $request->branch_id)
    //         ->where('product_id', $request->product_id)
    //         ->first();
    
    //     if ($branchProduct) {
    //         $branchProduct->stock += $request->stock;
    //         $branchProduct->save();
    
    //         Toastr::success('Stock updated successfully!');
    //     } else {
    //         Toastr::error('Product not found in the selected branch!');
    //     }
    
    //     // return redirect()->route('stock.list');
    //     return redirect()->route('stock.view', ['branch_id' => $request->branch_id]);
    // }
    



    //before invoice and chart of account 
    // public function addStock(Request $request)
    // {
    //     $request->validate([
    //         'product_id' => 'required',
    //         'batch' => 'required', // Validate batch input
    //         'stock' => 'required|integer|min:1',
    //         'branch_id' => 'required',
    //     ]);
    
    //     $branchProduct = Branch_Product::where('branch_id', $request->branch_id)
    //         ->where('product_id', $request->product_id)
    //         ->where('batch', $request->batch) // Ensure batch is considered
    //         ->first();
    
    //     if ($branchProduct) {  

    //         $existingDetails = $branchProduct->details_stockin 
    //             ? json_decode($branchProduct->details_stockin, true) 
    //             : [];

    //         if (!is_array($existingDetails)) {
    //                 $existingDetails = [];
    //             }

    //             $existingDetails[] = [
    //                 'requisition' => 'warehouse',
    //                 'quantity'    => $request->stock,
    //                 'date'        => now()->format('d-m-Y'),
    //                 'store_by'    => auth()->id(),
    //             ];

    //         $branchProduct->stock += $request->stock;
    //         $branchProduct->details_stockin = json_encode($existingDetails);
    //         $branchProduct->save(); 

    //         ProductLedger::create([
    //             'entry_date' => now()->format('Y-m-d'),
    //             'narration' => 'warehouse',
    //             'type' => 'stockin',
    //             'user_id' => auth()->id(),
    //             'branch_id' => $branchProduct->branch_id,
    //             'product_id' => $branchProduct->product_id,
    //             'quantity' => $request->stock,
    //             'price' => $branchProduct->price,
    //             'batch' => $branchProduct->batch,
    //             'requisition_id' => 'own',
    //         ]);  

    //         Toastr::success('Stock updated successfully!'); 
    //     } else {
    //         Toastr::error('Product with this batch not found in the selected branch!');
    //     }
    
    //     return redirect()->route('stock.view', ['branch_id' => $request->branch_id]);
    // }
    



     //last before chart of accounts with two table journal iteam and entry

    // public function addStock(Request $request)
    // {
    //     $request->validate([
    //         'product_id' => 'required',
    //         'batch' => 'required',
    //         'stock' => 'required|integer|min:1',
    //         'branch_id' => 'required',
    //         'payment_method' => 'required', // Add validation for payment method
    //     ]);
    
    //     // Generate invoice number
    //     $invoiceNo = 'INV-' . strtoupper(uniqid());
    //     $paymentMethod = $request->payment_method;
    
    //     // Map payment method to chart of account name
    //     $paymentMethodMap = [
    //         'cash' => 'Cash In Hand',
    //         'Bank' => 'Cash at Bank',
    //         'due'  => 'Accounts Receivable',
    //     ];
    //     $paymentMethodDisplay = $paymentMethodMap[strtolower($paymentMethod)] ?? $paymentMethod;
    //     $paymentMethodDisplay = ucfirst(strtolower($paymentMethod));
    
    //     // Find chart of account
    //     $chartAccount = ChartOfAccount::where('name', 'like', $paymentMethodDisplay)
    //         ->orWhere('name', 'like', '%' . $paymentMethodDisplay . '%')
    //         ->first(['id', 'code']);
        
    //     if (!$chartAccount) {
    //         $chartAccount = ChartOfAccount::where('name', 'like', '%Cash%')
    //             ->orWhere('name', 'like', '%Bank%')
    //             ->first(['id', 'code']);
            
    //         if (!$chartAccount) {
    //             $chartAccount = ChartOfAccount::first(['id', 'code']);
                
    //             if (!$chartAccount) {
    //                 Toastr::error("No Chart of Accounts configured in the system!", 'Error');
    //                 return redirect()->back();
    //             }
    //         }
    //     }
    
    //     $branchProduct = Branch_Product::where('branch_id', $request->branch_id)
    //         ->where('product_id', $request->product_id)
    //         ->where('batch', $request->batch)
    //         ->first();
    
    //     if ($branchProduct) {  
    //         $existingDetails = $branchProduct->details_stockin 
    //             ? json_decode($branchProduct->details_stockin, true) 
    //             : [];
    
    //         if (!is_array($existingDetails)) {
    //             $existingDetails = [];
    //         }
    
    //         $existingDetails[] = [
    //             'requisition' => 'warehouse',
    //             'quantity'    => $request->stock,
    //             'date'        => now()->format('d-m-Y'),
    //             'store_by'    => auth()->id(),
    //             'invoice_no'  => $invoiceNo,
    //         ];
    
    //         $branchProduct->stock += $request->stock;
    //         $branchProduct->details_stockin = json_encode($existingDetails);
    //         $branchProduct->save(); 
    
    //         ProductLedger::create([
    //             'entry_date'            => now()->format('Y-m-d'),
    //             'narration'             => 'warehouse',
    //             'type'                 => 'stockin',
    //             'user_id'              => auth()->id(),
    //             'branch_id'            => $branchProduct->branch_id,
    //             'product_id'           => $branchProduct->product_id,
    //             'quantity'             => $request->stock,
    //             'price'               => $branchProduct->price,
    //             'batch'               => $branchProduct->batch,
    //             'requisition_id'      => 'own',
    //             'invoice_no'          => $invoiceNo,
    //             'payment_method'      => $paymentMethod,
    //             'chart_of_account_id' => $chartAccount->id,
    //             'chart_of_account_code' => $chartAccount->code,
    //         ]);  
    
    //         Toastr::success('Stock updated successfully!'); 
    //     } else {
    //         Toastr::error('Product with this batch not found in the selected branch!');
    //     }
    
    //     return redirect()->route('stock.view', ['branch_id' => $request->branch_id]);
    // }





    public function addStock(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'batch' => 'required',
            'stock' => 'required|integer|min:1',
            'branch_id' => 'required',
            'payment_method' => 'required',
        ]);

        $invoiceNo = 'INV-' . strtoupper(uniqid());
        $paymentMethod = $request->payment_method;

        $paymentMethodMap = [
            'cash' => 'Cash In Hand',
            'Bank' => 'Cash at Bank',
            'due'  => 'Accounts Receivable',
        ];
        $paymentMethodDisplay = $paymentMethodMap[strtolower($paymentMethod)] ?? $paymentMethod;
        $paymentMethodDisplay = ucfirst(strtolower($paymentMethod));

        $chartAccount = ChartOfAccount::where('name', 'like', $paymentMethodDisplay)
            ->orWhere('name', 'like', '%' . $paymentMethodDisplay . '%')
            ->first(['id', 'code']);

        if (!$chartAccount) {
            $chartAccount = ChartOfAccount::where('name', 'like', '%Cash%')
                ->orWhere('name', 'like', '%Bank%')
                ->first(['id', 'code']);

            if (!$chartAccount) {
                $chartAccount = ChartOfAccount::first(['id', 'code']);

                if (!$chartAccount) {
                    Toastr::error("No Chart of Accounts configured in the system!", 'Error');
                    return redirect()->back();
                }
            }
        }

        $branchProduct = Branch_Product::where('branch_id', $request->branch_id)
            ->where('product_id', $request->product_id)
            ->where('batch', $request->batch)
            ->first();

        if ($branchProduct) {
            $existingDetails = $branchProduct->details_stockin
                ? json_decode($branchProduct->details_stockin, true)
                : [];

            if (!is_array($existingDetails)) {
                $existingDetails = [];
            }

            $existingDetails[] = [
                'requisition' => 'warehouse',
                'quantity'    => $request->stock,
                'date'        => now()->format('d-m-Y'),
                'store_by'    => auth()->id(),
                'invoice_no'  => $invoiceNo,
            ];

            $branchProduct->stock += $request->stock;
            $branchProduct->details_stockin = json_encode($existingDetails);
            $branchProduct->save();

            ProductLedger::create([
                'entry_date'            => now()->format('Y-m-d'),
                'narration'             => 'warehouse',
                'type'                  => 'stockin',
                'user_id'               => auth()->id(),
                'branch_id'             => $branchProduct->branch_id,
                'product_id'            => $branchProduct->product_id,
                'quantity'              => $request->stock,
                'price'                 => $branchProduct->price,
                'batch'                 => $branchProduct->batch,
                'requisition_id'        => 'own',
                'invoice_no'            => $invoiceNo,
                'payment_method'        => $paymentMethod,
                'chart_of_account_id'   => $chartAccount->id,
                'chart_of_account_code' => $chartAccount->code,
            ]);

            // ======= New Journal Entry =======
            $journalEntry = JournalEntry::create([
                'date'        => now()->toDateString(),
                'reference'   => $invoiceNo,
                'description' => 'Purchase by warehouse own',
                'journal_id'  => (JournalEntry::max('journal_id') ?? 99) + 1,
                'created_by'  => auth()->id(),
            ]);

            $product = Product::find($request->product_id);
            $productChartAccount = null;

            if ($product && $product->code) {
                $map = ProductAccountMap::where('product_code', $product->code)->first(['account_expense_code']);
                if ($map && $map->account_expense_code) {
                    $productChartAccount = ChartOfAccount::where('code', $map->account_expense_code)->first(['id']);
                }
            }

            $totalAmount = 0;
            if ($productChartAccount) {
                $amount = $request->stock * $branchProduct->price;
                $totalAmount += $amount;

                JournalItem::create([
                    'journal'     => $journalEntry->id,
                    'account'     => $productChartAccount->id,
                    'debit'       => $amount,
                    'credit'      => 0.00,
                    'description' => $product->name ?? 'Stock In',
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

            Toastr::success('Stock updated successfully!');
        } else {
            Toastr::error('Product with this batch not found in the selected branch!');
        }

        return redirect()->route('stock.view', ['branch_id' => $request->branch_id]);
    }












    // public function reject(Request $request)
    // {
    //     $request->validate([
    //         'requisition_id' => 'required|exists:requisitions,id',
    //         'reject_note' => 'required|string|max:255',
    //     ]);
    
    //     Requisition::where('id', $request->requisition_id)
    //         ->update([
    //             'status' => 5, 
    //             'reject_note' => $request->reject_note,
    //         ]);
    
    //     Toastr::success('Requisition rejected successfully.', 'Success');
    //     return redirect()->route('order.list');
    // }  



    //done and working 

    // public function reject(Request $request, $id)
    // {
    //     $request->validate([
    //         'items' => 'required|array',
    //         'note' => 'required|string|max:255',
    //     ]);
    
    //     $requisition = Requisition::findOrFail($id);
    
    //     $selectedProductIds = $request->input('items');
    //     $rejectNote = $request->input('note');
    
    //     RequisitionItem::where('requisition_id', $requisition->id)
    //         ->whereIn('product_id', $selectedProductIds)
    //         ->update([
    //             'reject' => 1,
    //             'reject_note' => $rejectNote,
    //         ]);
    
    //     $totalItems = RequisitionItem::where('requisition_id', $requisition->id)->count();
    //     $rejectedItems = RequisitionItem::where('requisition_id', $requisition->id)->where('reject', 1)->count();
    
    //     $isPartialReject = $rejectedItems === $totalItems ? 1 : 0;
    
    //     $requisition->update([
    //         'status' => 5,
    //         'partial_reject' => $isPartialReject,
    //     ]);
    
    //     return response()->json(['message' => 'Requisition rejected successfully.']);
    // }  




    public function reject(Request $request, $id)
    {
        $request->validate([
            'items' => 'required|array',
            'note' => 'required|string|max:255',
        ]);
    
        $requisition = Requisition::findOrFail($id);
    
        $selectedProductIds = $request->input('items');
        $rejectNote = $request->input('note');
    
        RequisitionItem::where('requisition_id', $requisition->id)
            ->whereIn('product_id', $selectedProductIds)
            ->update([
                'reject' => 1,
                'reject_note' => $rejectNote,
            ]);
    
        $totalItems = RequisitionItem::where('requisition_id', $requisition->id)->count();
        $rejectedItems = RequisitionItem::where('requisition_id', $requisition->id)->where('reject', 1)->count();
    
        $isPartialReject = $rejectedItems === $totalItems ? 1 : 0;
    
        $requisition->update([
            'status' => 5,
            'partial_reject' => $isPartialReject,
        ]);


        $requisitionItemsDoneAll = RequisitionItem::where('requisition_id', $requisition->id)->get();

        $allProcessed = $requisitionItemsDoneAll->every(fn($item) => $item->delivery == 1 || $item->reject == 1);
    
        $requisition->update([
            'alldone_status' => $allProcessed ? 1 : 0,
        ]);
    
        return response()->json(['message' => 'Requisition rejected successfully.']); 

    }
    
    









     //done and working before price and batch wise product stock sum
    // public function rejectCheck($id)
    // {

    //     $requisition = Requisition::findOrFail($id);
    //     $requisitionItems = RequisitionItem::where('requisition_id', $requisition->id)
    //         ->where('delivery', '!=', 1) 
    //         ->where('reject', '!=', 1)  
    //         ->where('purchase', '!=', 1)  
    //         ->get();

    //     $allDetails = [];

    //     foreach ($requisitionItems as $item) {
    //         $productId = $item->product_id;
    //         $demandAmount = $item->demand_amount;

    //         $product = Product::find($productId);
    //         $productName = $product ? $product->name : "Unknown Product";

    //         $warehouseBranchProduct = Branch_Product::whereHas('branch', function ($query) {
    //                 $query->where('type', 'Warehouse');
    //             })
    //             ->where('product_id', $productId)
    //             ->first();

    //         $stock = $warehouseBranchProduct ? $warehouseBranchProduct->stock : 0;

    //         $allDetails[] = [
    //             'rjreq_id' => $requisition->id,
    //             'product_id' => $productId,
    //             'product_name' => $productName,
    //             'stock' => $stock,
    //             'demand_amount' => $demandAmount,
    //             'is_insufficient' => $demandAmount > $stock,
    //             'purchase' => $item->purchase ?? 0,
    //         ];
    //     }

    //     session()->flash('allDetailsreject', $allDetails);
    //     session()->flash('rejectsModal', true);

    //     return redirect()->back();


    // }
    






    public function rejectCheck($id)
    {
        $requisition = Requisition::findOrFail($id);
        $requisitionItems = RequisitionItem::where('requisition_id', $requisition->id)
            ->where('delivery', '!=', 1) 
            ->where('reject', '!=', 1)  
            ->where('purchase', '!=', 1)  
            ->get();

        $allDetails = [];

        foreach ($requisitionItems as $item) {
            $productId = $item->product_id;
            $demandAmount = $item->demand_amount;

            $product = Product::find($productId);
            $productName = $product ? $product->name : "Unknown Product";

            $warehouseStocks = Branch_Product::whereHas('branch', function ($query) {
                    $query->where('type', 'Warehouse');
                })
                ->where('product_id', $productId)
                ->get()
                ->groupBy('product_id');

            $totalStock = 0;
            $batchDetails = [];

            if ($warehouseStocks->isNotEmpty()) {
                foreach ($warehouseStocks as $batches) {
                    foreach ($batches as $batch) {
                        $totalStock += $batch->stock;
                        $batchDetails[] = "(Batch: {$batch->batch}, {$batch->stock})";
                    }
                }
            }

            $allDetails[] = [
                'rjreq_id' => $requisition->id,
                'product_id' => $productId,
                'product_name' => $productName,
                'stock' => $totalStock,
                'demand_amount' => $demandAmount,
                'is_insufficient' => $demandAmount > $totalStock,
                'purchase' => $item->purchase ?? 0,
            ];
        }

        session()->flash('allDetailsreject', $allDetails);
        session()->flash('rejectsModal', true);

        return redirect()->back();


    }





















    // public function pendingpurchaseReject(Request $request)
    // {
    //     $request->validate([
    //         'requisition_id' => 'required|exists:requisitions,id',
    //         'reject_note' => 'required|string|max:255',
    //     ]);
    
    //     Requisition::where('id', $request->requisition_id)
    //         ->update([
    //             'status' => 6, 
    //             'purchase_reject' => 1, 
    //             'purchaseteam_reject_note' => $request->reject_note,
    //         ]);
    
    //     Toastr::success('Reject successfully.', 'Success');
    //     return redirect()->route('pending.purcahse.requisition');
    // }




    // this is okay just 'pending_purchase_status' face problem and rest of all are okay
    // public function pendingpurchaseReject(Request $request, $id)
    // {
    //     $request->validate([
    //         'items' => 'required|array',
    //         'note' => 'required|string|max:255',
    //     ]);
    
    //     $requisition = Requisition::findOrFail($id);
    
    //     $selectedProductIds = $request->input('items');
    //     $rejectNote = $request->input('note');
    
    //     RequisitionItem::where('requisition_id', $requisition->id)
    //         ->whereIn('product_id', $selectedProductIds)
    //         ->update([
    //             'reject' => 1,
    //             'purchase' => 0,
    //             'reject_note' => $rejectNote,
    //         ]);
    
    //     $totalItems = RequisitionItem::where('requisition_id', $requisition->id)->count();
    //     $rejectedItems = RequisitionItem::where('requisition_id', $requisition->id)->where('reject', 1)->count();
    
    //     $isPartialReject = $rejectedItems === $totalItems ? 1 : 0;
    
    //     $requisition->update([
    //         'status' => 5,
    //         'partial_reject' => $isPartialReject,
    //         'purchase_reject' => 1,
    //         'pending_purchase_status' => $rejectedItems === $totalItems ? 2 : 0, 
    //     ]);
    
    //     return response()->json(['message' => 'Requisition rejected successfully.']);
    // }   




    public function pendingpurchaseReject(Request $request, $id)
    {
        $request->validate([
            'items' => 'required|array',
            'note' => 'required|string|max:255',
        ]);
    
        $selectedProductIds = $request->input('items');
        $rejectNote = $request->input('note');
    
        $requisition = Requisition::findOrFail($id);
    
        RequisitionItem::where('requisition_id', $requisition->id)
            ->whereIn('product_id', $selectedProductIds)
            ->update([
                'reject' => 1,
                'purchase' => 0,
                'purchase_team_reject' => 1,
                'reject_note' => $rejectNote,
            ]);
    
        $totalItems = RequisitionItem::where('requisition_id', $requisition->id)->count();
        $rejectedItems = RequisitionItem::where('requisition_id', $requisition->id)->where('reject', 1)->count();
    
        $isPartialReject = $rejectedItems === $totalItems ? 1 : 0;
    
        $isAllRejected = RequisitionItem::where('requisition_id', $requisition->id)
            ->where('purchase', 1)
            ->whereNotIn('product_id', $selectedProductIds)
            ->doesntExist();
    
        $requisition->update([
            'status' => 5,
            'partial_reject' => $isPartialReject,
            'purchase_reject' => 1,
            'pending_purchase_status' => $isAllRejected ? 2 : 0,
        ]);  



        $requisitionItemsDoneAll = RequisitionItem::where('requisition_id', $requisition->id)->get();

        $allProcessed = $requisitionItemsDoneAll->every(fn($item) => $item->delivery == 1 || $item->reject == 1);
    
        $requisition->update([
            'alldone_status' => $allProcessed ? 1 : 0,
        ]);
    
        return response()->json(['message' => 'Requisition rejected successfully.']); 

    }
    

    
 









    public function pendingpurchaseRejectCheck($id)
    {
        $requisition = Requisition::findOrFail($id);
        $requisitionItems = RequisitionItem::where('requisition_id', $requisition->id)
            ->where('purchase', '=', 1) 
            ->get();

        $allDetails = [];

        foreach ($requisitionItems as $item) {
            $productId = $item->product_id;
            $demandAmount = $item->demand_amount;

            $product = Product::find($productId);
            $productName = $product ? $product->name : "Unknown Product";


            // $warehouseBranchProduct = Branch_Product::whereHas('branch', function ($query) {
            //         $query->where('type', 'Warehouse');
            //     })
            //     ->where('product_id', $productId)
            //     ->first();

            // $stock = $warehouseBranchProduct ? $warehouseBranchProduct->stock : 0;


            $warehouseStocks = Branch_Product::whereHas('branch', function ($query) {
                    $query->where('type', 'Warehouse');
                })
                ->where('product_id', $productId)
                ->get()
                ->groupBy('product_id');

            $totalStock = 0;
            $batchDetails = [];

            if ($warehouseStocks->isNotEmpty()) {
                foreach ($warehouseStocks as $batches) {
                    foreach ($batches as $batch) {
                        $totalStock += $batch->stock;
                        $batchDetails[] = "(Batch: {$batch->batch}, {$batch->stock})";
                    }
                }
            }




            $allDetails[] = [
                'pprjreq_id' => $requisition->id,
                'product_id' => $productId,
                'product_name' => $productName,
                'stock' => $totalStock,
                'demand_amount' => $demandAmount,
                'is_insufficient' => $demandAmount > $totalStock,
                'headoffice_approval' => $item->headoffice_approval, // Add this line
            ];
        }

        session()->flash('rejectDetails', $allDetails);
        session()->flash('showRejectModal', true);

        return redirect()->back();
    }














    



    // public function pendingApprovallist()
    // {
       
    //     $requisitions = Requisition::where('status', 7)->get();

    //     return view('requisition.approval.pending', compact('requisitions'));
    // }




    // public function pendingApprovallist()
    // {
       
    //     $requisitions = Requisition::where('pending_approval_status_headoffice', 0)->get();

    //     return view('requisition.approval.pending', compact('requisitions'));
    // }


    public function pendingApprovallist()
    {
        $requisitions = Requisition::where('pending_approval_status_headoffice', 0)
            ->where(function ($query) {
                $query->where(function ($q) {
                    $q->whereNull('headoffice_approve')
                      ->whereNull('headoffice_reject');
                })
                ->orWhere(function ($q) {
                    $q->where('headoffice_approve', 1)
                      ->whereNull('headoffice_reject');
                })
                ->orWhere(function ($q) {
                    $q->whereNull('headoffice_approve')
                      ->where('headoffice_reject', 1);
                });
            })
            ->get();
    
        return view('requisition.approval.pending', compact('requisitions'));
    }
    











    public function pendingApprovallistView($id)
    {

        $requisitionheading = Requisition::with('user')->find($id);
        $requisitionlist = RequisitionItem::where('requisition_id', $id)
        ->where('headoffice_approval', 1)
        ->get();

        $productNames = $requisitionlist->pluck('single_product_name');

        $productIds = Product::whereIn('id', $productNames)->pluck('name', 'id')->toArray();

        return view('requisition.approval.view', compact('requisitionheading', 'requisitionlist', 'productIds'));
    }



    
    // public function pendingApprovallistapprove($id)
    // {
       
    //     $requisition = Requisition::findOrFail($id);
    //     $requisition->update([
    //             'status' => 4,
    //             'headoffice_approve' => 1,
    //         ]);

    //     Toastr::success('Approved successfully.', 'Success');
    //     return redirect()->route('pending.approval.list');
    // }





    public function pendingApprovallistapprove($id)
    {
        $requisition = Requisition::findOrFail($id);
        $selectedProductIds = json_decode(request()->get('selected_products'), true);

        $requisitionItems = RequisitionItem::where('requisition_id', $requisition->id)
            ->where('headoffice_approval', '=', 1)
            ->get();

        $allProductIds = $requisitionItems->pluck('product_id')->toArray();
        $allSelected = empty(array_diff($allProductIds, $selectedProductIds));

        $requisition->pending_purchase_status = 4; 
        $requisition->pending_approval_status_headoffice = $allSelected ? 1 : 0; 
        $requisition->headoffice_approve = 1; 
        $requisition->save();

        foreach ($requisitionItems as $item) {
            if (in_array($item->product_id, $selectedProductIds)) {
                $item->headoffice_approval = 0; 
                $item->save();
            }
        }

        session()->flash('toastr', [
            'type' => 'success',
            'message' => 'Approval process completed successfully.'
        ]);

        return redirect()->route('pending.approval.list');
    }











    public function pendingApprovallistapproveCheck($id)
    {
        $requisition = Requisition::findOrFail($id);
        $requisitionItems = RequisitionItem::where('requisition_id', $requisition->id)
            ->where('headoffice_approval', '=', 1)
            ->get(); 

        $allDetails = [];

        foreach ($requisitionItems as $item) {
            $productId = $item->product_id;
            $demandAmount = $item->demand_amount;

            $product = Product::find($productId);
            $productName = $product ? $product->name : "Unknown Product";

            
            // $warehouseBranchProduct = Branch_Product::whereHas('branch', function ($query) {
            //         $query->where('type', 'Warehouse');
            //     })
            //     ->where('product_id', $productId)
            //     ->first();

            // $stock = $warehouseBranchProduct ? $warehouseBranchProduct->stock : 0; 



            $warehouseStocks = Branch_Product::whereHas('branch', function ($query) {
                    $query->where('type', 'Warehouse');
                })
                ->where('product_id', $productId)
                ->get()
                ->groupBy('product_id');

            $totalStock = 0;
            $batchDetails = [];

            if ($warehouseStocks->isNotEmpty()) {
                foreach ($warehouseStocks as $batches) {
                    foreach ($batches as $batch) {
                        $totalStock += $batch->stock;
                        $batchDetails[] = "(Batch: {$batch->batch}, {$batch->stock})";
                    }
                }
            }

            $allDetails[] = [
                'ppreq_id' => $requisition->id,
                'product_id' => $productId,
                'product_name' => $productName,
                'stock' => $totalStock,
                'demand_amount' => $demandAmount,
                'is_insufficient' => $demandAmount > $totalStock,
            ];
        }

        session()->flash('allPendingapproval', $allDetails); 
        session()->flash('showapprovalModal', true); 

        return redirect()->back();
    }









    




    public function pendingApprovalApproveList()
    {
       
        $requisitions = Requisition::where('headoffice_approve', 1)->get();

        return view('requisition.approval.approved', compact('requisitions'));
    }


    
    public function pendingApprovalApproveListView($id)
    {

        $requisitionheading = Requisition::with('user')->find($id);

        $requisitionlist = RequisitionItem::where('requisition_id', $id)
        ->where('headoffice_approval', 0)
        ->get();

        $productNames = $requisitionlist->pluck('single_product_name');

        $productIds = Product::whereIn('id', $productNames)->pluck('name', 'id')->toArray();

        return view('requisition.approval.approvedvieew', compact('requisitionheading', 'requisitionlist', 'productIds'));
    }




    public function pendingApprovalRejectlist()
    {
       
        $requisitions = Requisition::where('headoffice_reject', 1)->get();

        return view('requisition.approval.reject', compact('requisitions'));
    }

    public function pendingApprovalRejectlistView($id)
    {

        $requisitionheading = Requisition::with('user')->find($id);
        $requisitionlist = RequisitionItem::where('requisition_id', $id)
        ->where('headoffice_rejected', 1)
        ->get();

        $productNames = $requisitionlist->pluck('single_product_name');

        $productIds = Product::whereIn('id', $productNames)->pluck('name', 'id')->toArray();

        return view('requisition.approval.rejectview', compact('requisitionheading', 'requisitionlist', 'productIds'));
    }



    // public function pendingApprovallistreject(Request $request)
    // {
    //     $request->validate([
    //         'requisition_id' => 'required|exists:requisitions,id',
    //         'reject_note' => 'required|string|max:255',
    //     ]);
    
    //     Requisition::where('id', $request->requisition_id)
    //         ->update([
    //             'status' => 4, 
    //             'headoffice_reject' => 1, 
    //             'headoffice_reject_note' => $request->reject_note,
    //         ]);
    
    //     Toastr::success('Reject successfully.', 'Success');
    //     return redirect()->route('pending.approval.list');
    // }







    public function pendingApprovallistreject(Request $request, $id)
    {
        $validatedData = $request->validate([
            'selected_products' => 'required|array',
            'reject_note' => 'required|string',
        ]);

        $requisition = Requisition::findOrFail($id);

        $selectedProductIds = $request->selected_products;
        $rejectNote = $request->reject_note;

        RequisitionItem::where('requisition_id', $id)
            ->whereIn('product_id', $selectedProductIds)
            ->update([
                'headoffice_rejected' => 1,
                'headoffice_approval' => null,
                'purchase' => 0,
                'reject' => 1,
                'reject_note' => $rejectNote,
            ]);

        $allRejected = RequisitionItem::where('requisition_id', $id)
            ->where('reject', 1)
            ->count();

        $totalItems = RequisitionItem::where('requisition_id', $id)->count();


        $allchecked = RequisitionItem::where('requisition_id', $id)
            ->where('headoffice_approval', null)
            ->count();
        $totalItemscheck = RequisitionItem::where('requisition_id', $id)->count();



        $requisition->headoffice_reject = 1;
        $requisition->pending_purchase_status = 5;

        // $requisition->pending_approval_status_headoffice = 2;

        $requisition->pending_approval_status_headoffice = ($allchecked === $totalItemscheck) ? 2 : 0;

        $requisition->partial_reject = ($allRejected === $totalItems) ? 1 : 0;
        $requisition->status = 5;
        $requisition->save();  


        $requisitionItemsDoneAll = RequisitionItem::where('requisition_id', $requisition->id)->get();

        $allProcessed = $requisitionItemsDoneAll->every(fn($item) => $item->delivery == 1 || $item->reject == 1);
    
        $requisition->update([
            'alldone_status' => $allProcessed ? 1 : 0,
        ]);


        Toastr::success('Requisition rejected successfully.', 'Success');
        return redirect()->route('pending.approval.list');
    }


















    public function pendingApprovallistrejectCheck($id)
    {
        $requisition = Requisition::findOrFail($id);
        $requisitionItems = RequisitionItem::where('requisition_id', $requisition->id)
            ->where('headoffice_approval', '=', 1)
            ->get();
    
        $allDetails = [];
    
        foreach ($requisitionItems as $item) {
            $productId = $item->product_id;
            $demandAmount = $item->demand_amount;
    
            $product = Product::find($productId);
            $productName = $product ? $product->name : "Unknown Product";

    
            // $warehouseBranchProduct = Branch_Product::whereHas('branch', function ($query) {
            //         $query->where('type', 'Warehouse');
            //     })
            //     ->where('product_id', $productId)
            //     ->first();
    
            // $stock = $warehouseBranchProduct ? $warehouseBranchProduct->stock : 0;


            $warehouseStocks = Branch_Product::whereHas('branch', function ($query) {
                    $query->where('type', 'Warehouse');
                })
                ->where('product_id', $productId)
                ->get()
                ->groupBy('product_id');

            $totalStock = 0;
            $batchDetails = [];

            if ($warehouseStocks->isNotEmpty()) {
                foreach ($warehouseStocks as $batches) {
                    foreach ($batches as $batch) {
                        $totalStock += $batch->stock;
                        $batchDetails[] = "(Batch: {$batch->batch}, {$batch->stock})";
                    }
                }
            }
    
            $allDetails[] = [
                'hrreq_id' => $requisition->id, 
                'product_id' => $productId,
                'product_name' => $productName,
                'stock' => $totalStock,
                'demand_amount' => $demandAmount,
                'is_insufficient' => $demandAmount > $totalStock,
            ];
        }
    
        session()->flash('allRejected', $allDetails); 
        session()->flash('showrejectModal', true); 
    
        return redirect()->back();
    }
    










}
