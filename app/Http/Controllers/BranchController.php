<?php

namespace App\Http\Controllers;

use DB;
use App\Models\User;
use App\Models\Branch;
use App\Models\Product;
use App\Models\Requisition;
use Illuminate\Http\Request;
use App\Models\Branch_Product;
use Brian2694\Toastr\Facades\Toastr;


class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::all();
        return view('branch.list', compact('branches'));
    }


    public function create()
    {
        return view('branch.create');
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required',
    //         'address' => 'required',
    //         'type' => 'required',
    //     ]);

    //     Branch::create($request->all());

        
    //     Toastr::success('Branch created successfully.', 'Success');
    //     return redirect()->route('branch.create');
    // }
    


    // public function store(Request $request)
    // {

    //     dd($request->all());

    //     $request->validate([
    //         'name' => 'required',
    //         'address' => 'required',
    //         'type' => 'required',
    //         'mobile_no' => 'required',
    //         'email' => 'required|email|unique:branches,email',
    //     ]);

    //     $branch=Branch::create($request->all());

    //     $products=Product::select('id')->get();

    //     foreach ($products as $product) {
    //        Branch_Product::create([
    //         'branch_id' => $branch->id,
    //         'product_id' => $product->id,
    //         'stock' => 0
    //        ]);
    //     }
        
    //     Toastr::success('Branch created successfully.', 'Success');
    //     return redirect()->route('branch.list');
    // }







    public function store(Request $request)
    {
        // Validate the input
        try {
            $request->validate([
                'name' => 'required',
                'address' => 'required',
                'type' => 'required',
                'mobile_no' => 'required',
                'email' => 'required|email|unique:branch,email',
            ]);
            
            // Create the branch
            $branch = Branch::create($request->all());
    
            // Get all products and associate them with the branch
            $products = Product::select('id')->get();
    
            foreach ($products as $product) {
                Branch_Product::create([
                    'branch_id' => $branch->id,
                    'product_id' => $product->id,
                    'stock' => 0
                ]);
            }
    
            // Show success message
            Toastr::success('Branch created successfully.', 'Success');
            return redirect()->route('branch.list');
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Show Toastr error message for unique email
            Toastr::error('Please provide a unique email address.', 'Error');
            return redirect()->back();
        }
    }
    












    public function show($id)
    {
        $branch = Branch::findOrFail($id);
        return view('branch.show', compact('branch'));
    }

  
    // public function edit($id)
    // {
    //     $branch = Branch::findOrFail($id);
    //     return view('branch.edit', compact('branch'));
    // }


    public function edit($id)
    {
        $branch = Branch::findOrFail($id);
        $types = Branch::select('type')->distinct()->get(); // Get all distinct types
        return view('branch.edit', compact('branch', 'types'));
    }



    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'type' => 'required',
            'mobile_no' => 'required',
            'email' => 'required|email|unique:branch,email,' . $id,

        ]);

        $branch = Branch::findOrFail($id);
        $branch->update($request->all());

        Toastr::success('Branch updated successfully.', 'Success');
        return redirect()->route('branch.list');
    }


    // public function delete($id)
    // {
    //     $branch = Branch::findOrFail($id);
    //     $branch->delete();

    //     Toastr::success('Branch deleted successfully.', 'Success');
    //     return redirect()->route('branch.list');
    // }


    // public function delete($id)
    // {
    //     $branch = Branch::findOrFail($id);

    //     Branch_Product::where('branch_id', $id)->delete();

    //     $branch->delete();

    //     Toastr::success('Branch and its related products deleted successfully.', 'Success');
        
    //     return redirect()->route('branch.list');
    // }



    // public function delete($id)
    // {
    //     $branch = Branch::findOrFail($id);
    
    //     $requisitionExists = Requisition::where('branch_id', $id)->exists();

    //     $branchProductStock = Branch_Product::where('branch_id', $id)->sum('stock');
    
    //     if ($requisitionExists) {
    //         Toastr::error("This branch is under a requisition, so you can't delete it.", 'Error');
    //         return redirect()->route('branch.list');
    //     }

    //     if ($branchProductStock > 0) {
    //         Toastr::error('Cannot delete branch because there is product stock available.', 'Error');
    //         return redirect()->route('branch.list');
    //     }
    //      else {
    //         Branch_Product::where('branch_id', $id)->delete();
    //         $branch->delete();
    
    //         Toastr::success('Branch deleted successfully.', 'Success');
    //         return redirect()->route('branch.list');
    //     }
    // }
    


    public function delete($id)
    {
        $branch = Branch::findOrFail($id);

        $userExists = User::where('branch_id', $id)->exists();
        $requisitionExists = Requisition::where('branch_id', $id)->exists();
        $branchProductStock = Branch_Product::where('branch_id', $id)->sum('stock');
        
        if ($userExists) {
            Toastr::error("This branch cannot be deleted because it is assigned to users.", 'Error');
            return redirect()->route('branch.list');
        }
        
        if ($requisitionExists) {
            Toastr::error("This branch is under a requisition, so you can't delete it.", 'Error');
            return redirect()->route('branch.list');
        }
    
        if ($branchProductStock > 0) {
            Toastr::error('Cannot delete branch because there is product stock available.', 'Error');
            return redirect()->route('branch.list');
        }
        
        Branch_Product::where('branch_id', $id)->delete();
        $branch->delete();
    
        Toastr::success('Branch deleted successfully.', 'Success');
        return redirect()->route('branch.list');
    }
    







}
