<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Branch;
use App\Models\Product;
use App\Models\Requisition;
use Illuminate\Http\Request;
use App\Models\ProductReturn;
use App\Models\Branch_Product;
use App\Models\ProductExpense;
use App\Models\Project;
use App\Models\RequisitionItem;
use Brian2694\Toastr\Facades\Toastr;

use Milon\Barcode\DNS1D;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
    * Show the application dashboard.
    *
    * @return \Illuminate\Contracts\Support\Renderable
    */
    /** home dashboard */



    public function index()
    {
        $user = auth()->user();
    
        if ($user->role_name === 'Admin' || $user->role_name === 'Headoffice') {    
           
            $requisitions = Requisition::count();
            $purchaseCollections = Requisition::where('status', 2)->count('id');
            $pendingCollections = Requisition::where('status', 1)->count('id');
            $products = Product::count();
            $branchs = Branch::count();
            $projects = Project::count();
            $stocks = Branch_Product::sum('stock');
            $puchaselistsadmincheck = Requisition::where('purchase_approve', 1)->count('id');
            $rejectlistsadmincheck = Requisition::where('purchase_reject', 1)->count('id');


            $alertproducts = ProductReturn::where('notification_status', 0)->count();


            $requisitionho = Requisition::where('user_id', $user->id)->count();
            $completedorderho = Requisition::where('user_id', $user->id)->where('status', 1)->count();
            $pendingapprovalforho = Requisition::where('status', 7)->count('id');
            $approvedapprovalforho = Requisition::where('headoffice_approve', 1)->count('id');
            $rejectapprovalforho = Requisition::where('headoffice_reject', 1)->count('id');
            $forhodamages = ProductReturn::where('user_id', $user->id)->count('id');


            $completedorders = Requisition::where('status', 1)->count('id');
            $expenses = ProductExpense::count();
            $damages = ProductReturn::count();
            

            $damagelist = ProductReturn::count();

            $orderrequest = Requisition::whereHas('branch', function ($query) {
                $query->where('type', '!=', 'Warehouse');
            })
            ->count();

            $puchaselists = Requisition::where('purchase_approve', 1)
            ->whereHas('branch', function ($query) {
                $query->where('type', '!=', 'Warehouse');
            })
            ->count('id');

            $rejectlists = Requisition::where('purchase_reject', 1)
            ->whereHas('branch', function ($query) {
                $query->where('type', '!=', 'Warehouse');
            })
            ->count('id');


            if ($user->role_name === 'Headoffice') {
                $latestRequisitionLists = Requisition::where('user_id', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get();
            } elseif ($user->role_name === 'Admin') {
                $latestRequisitionLists = Requisition::orderBy('created_at', 'desc')
                    ->take(5)
                    ->get();
            } else {
                $latestRequisitionLists = collect(); 
            }
            
        
            $pendingpurchases = Requisition::where('status', 4)->count('id');
            $ptpurchasecollections = Requisition::where('purchase_approve', 1)->count('id');
            $ptpurchasereject = Requisition::where('purchase_reject', 1)->count('id');
            $ptpurchaseapprovebyho = Requisition::where('headoffice_approve', 1)->count('id');
            $ptpurchaserejectho = Requisition::where('headoffice_reject', 1)->count('id');


        } else {


            $requisitionho = Requisition::where('user_id', $user->id)->count();
            $completedorderho = Requisition::where('user_id', $user->id)->where('status', 1)->count();
            $pendingapprovalforho = Requisition::where('status', 7)->count('id');
            $approvedapprovalforho = Requisition::where('headoffice_approve', 1)->count('id');
            $rejectapprovalforho = Requisition::where('headoffice_reject', 1)->count('id');
            $forhodamages = ProductReturn::where('user_id', $user->id)->count('id');
            $puchaselistsadmincheck = Requisition::where('purchase_approve', 1)->count('id');
            $rejectlistsadmincheck = Requisition::where('purchase_reject', 1)->count('id');


            $alertproducts = ProductReturn::where('notification_status', 0)->count();

            
            $requisitions = Requisition::where('user_id', $user->id)->count();
            $purchaseCollections = Requisition::where('user_id', $user->id)->where('status', 2)->count('id');
            $pendingCollections = Requisition::where('user_id', $user->id)->where('status', 1)->count('id');
            $products = Product::count(); 
            $branchs = Branch::count(); 
            $projects = Project::count();
            $stocks = Branch_Product::where('branch_id', $user->branch_id)->sum('stock'); 


            $completedorders = Requisition::where('user_id', $user->id)->where('status', 1)->count('id');
            $expenses = ProductExpense::where('user_id', $user->id)->count('id');
            $damages = ProductReturn::where('user_id', $user->id)->count('id');

            $damagelist = ProductReturn::count();


            $orderrequest = Requisition::whereHas('branch', function ($query) {
                $query->where('type', '!=', 'Warehouse');
            })
            ->count();

            $puchaselists = Requisition::where('purchase_approve', 1)
                ->whereHas('branch', function ($query) {
                    $query->where('type', '!=', 'Warehouse');
                })
                ->count('id');


            $rejectlists = Requisition::where('purchase_reject', 1)
                ->whereHas('branch', function ($query) {
                    $query->where('type', '!=', 'Warehouse');
                })
                ->count('id');

            $latestRequisitionLists = Requisition::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
        
            $pendingpurchases = Requisition::where('status', 4)->count('id');
            $ptpurchasecollections = Requisition::where('purchase_approve', 1)->count('id');
            $ptpurchasereject = Requisition::where('purchase_reject', 1)->count('id');
            $ptpurchaseapprovebyho = Requisition::where('headoffice_approve', 1)->count('id');
            $ptpurchaserejectho = Requisition::where('headoffice_reject', 1)->count('id');
    

        }
    
       
        $latestRequisitions = Requisition::when($user->role_name !== 'Admin', function ($query) use ($user) {
                $query->get();
            })
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
    
        $branchproductsstock = Product::when($user->role_name !== 'Admin', function ($query) use ($user) {
                $query->get();
            })
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

           
            $productLists = Product::orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            

    
        return view('dashboard.home', compact(
            'requisitions',
            'requisitionho',
            'completedorderho',
            'pendingapprovalforho',
            'approvedapprovalforho',
            'rejectapprovalforho',
            'forhodamages',
            'projects',
            'alertproducts',
            'puchaselistsadmincheck',
            'rejectlistsadmincheck',
            'completedorders',
            'expenses',
            'damages',
            'orderrequest',
            'puchaselists',
            'rejectlists',
            'damagelist',
            'productLists',
            'pendingpurchases',
            'ptpurchasecollections',
            'ptpurchasereject',
            'ptpurchaseapprovebyho',
            'ptpurchaserejectho',
            'latestRequisitionLists',
            'purchaseCollections',
            'branchs',
            'stocks',
            'products',
            'pendingCollections',
            'user',
            'latestRequisitions',
            'branchproductsstock'
        ));
    }
    



    public function userProfile()
    {
        $user = auth()->user();
        
        return view('dashboard.profile', compact('user'));
    }



    public function userProfileEdit()
    {
        $user = auth()->user();
        return view('dashboard.editprofile', compact('user'));
    }



    public function userProfileUpdate(Request $request)
    {
        $user = auth()->user();

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'phone_number' => 'required|string|max:20',
            'address' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            $avatarName = time().'.'.$request->avatar->extension();
            $request->avatar->move(public_path('images'), $avatarName);
            $validatedData['avatar'] = $avatarName;
        }

        $user->update($validatedData);

        Toastr::success('Profile updated successfully! ','Success');
        return redirect()->intended('home');

    }




}
