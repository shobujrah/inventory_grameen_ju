<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductCategory; 
use Brian2694\Toastr\Facades\Toastr;


class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productcategory = ProductCategory::orderBy('id', 'desc')->get();
        return view('productcategory.index', compact('productcategory'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('productcategory.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $validator = \Validator::make(
            $request->all(), [
                'name' => 'required',
            ]
        );

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->route('productcategory.index')->with('error', $messages->first());
        }

        $project                = new ProductCategory();
        $project->name          = $request->name;
        $project->created_by    = \Auth::user()->id;
        $project->save();

        Toastr::success('Category Created!','Success');

        return redirect()->route('productcategory.index');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */


    public function edit(ProductCategory $productcategory)
    {
        return view('productcategory.edit', compact('productcategory'));
    }


    /**
     * Update the specified resource in storage.
     */  


    public function update(Request $request, ProductCategory $productcategory)
    {
        $validator = \Validator::make(
            $request->all(), [
                'name' => 'required',
            ]
        );

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->route('project.index')->with('error', $messages->first());
        }

        $productcategory->name          = $request->name;
        $productcategory->created_by    = \Auth::user()->id;
        $productcategory->save();

        Toastr::success('Category Updated!','Success');
        return redirect()->route('productcategory.index');

    }


    /**
     * Remove the specified resource from storage.
     */  

    public function destroy(ProductCategory $productcategory)
    {
        $isProduct = Product::where('product_category_id', $productcategory->id)->exists();

        if ($isProduct) {
            Toastr::error("This category can't be deleted because under this category has products.", 'Error');
            return redirect()->route('productcategory.index');
        }

        $productcategory->delete();
        Toastr::success('Category successfully deleted.', 'Success');
        return redirect()->route('productcategory.index');
    }



}
