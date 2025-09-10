<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Category;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;

class CategoryController extends Controller
{
    /** index page category list */
    public function category()
    {
        $categoryList = Category::all();
        return view('category.category',compact('categoryList'));
    }

    /** index page category grid */
    public function categoryGrid()
    {
        $categoryList = Category::all();
        return view('category.category-grid',compact('categoryList'));
    }

    /** category add page */
    public function categoryAdd()
    {
        return view('category.add-category');
    }
    
    /** category save record */
    public function categorySave(Request $request)
    {
        $request->validate([
            'name'    => 'required|string',
            'description'     => 'required|string',
        ]);
        
        DB::beginTransaction();
        try {
           
            if(!empty($request->name)) {
                $category = new Category;
                $category->name   = $request->name;
                $category->description    = $request->description;
                $category->save();

                Toastr::success('Has been added successfully ','Success');
                DB::commit();
            }

            return redirect()->back();
           
        } catch(\Exception $e) {
            DB::rollback();
            Toastr::error('Fail, Add new category ','Error');
            return redirect()->back();
        }
    }

    /** view for edit category */
    public function categoryEdit($id)
    {
        $categoryEdit = Category::where('id',$id)->first();
        return view('category.edit-category',compact('categoryEdit'));
    }

    /** update record */
    public function categoryUpdate(Request $request)
    {
        DB::beginTransaction();
        try {
           
            $name   = $request->name;
            $description    = $request->description;

            $updateRecord = [
                'name'    => $name,
                'description'     => $description,
            ];
            Category::where('id',$request->id)->update($updateRecord);
            
            Toastr::success('Has been updated successfully ','Success');
            DB::commit();
            return redirect()->back();
           
        } catch(\Exception $e) {
            DB::rollback();
            Toastr::error('Fail, Update category ','Error');
            return redirect()->back();
        }
    }

    /** category delete */
    public function categoryDelete(Request $request)
    {
        DB::beginTransaction();
        try {
           
            if (!empty($request->id)) {
                Category::destroy($request->id);
                DB::commit();
                Toastr::success('Category has been deleted successfully ','Success');
                return redirect()->back();
            }
    
        } catch(\Exception $e) {
            DB::rollback();
            Toastr::error('Category deletion fail ','Error');
            return redirect()->back();
        }
    }

    /** category view page */
    public function categoryView($id)
    {
        $categoryView = Category::where('id',$id)->first();
        return view('category.category-view',compact('categoryView'));
    }
}
