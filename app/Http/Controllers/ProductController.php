<?php

namespace App\Http\Controllers;

use DNS1D;
use App\Models\Branch;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Branch_Product;
use App\Models\ChartOfAccount;
use App\Models\ProductCategory;
use App\Models\RequisitionItem;
use App\Models\ProductAccountMap;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{


    public function productList()
    {
        $productList = Product::all();

        foreach ($productList as $product) {
            if (!empty($product->sku)) {
                $product->barcode = DNS1D::getBarcodeHTML($product->sku, 'C128', 1, 50);
            } else {
                $product->barcode = '<p>SKU not available</p>';
            }
        }

        return view('product.list', compact('productList'));
    }



    // public function productCreate()
    // {
    //     $categories = ProductCategory::all();
    //     return view('product.create', compact('categories'));
    // }
    

    public function productCreate()
    { 

        // $lastProduct = Product::latest()->first();
        // $nextBatchNumber = $lastProduct && $lastProduct->batch ? $lastProduct->batch + 1 : 1001; 


        $lastBatch = Branch_Product::latest('batch')->value('batch');
        $nextBatchNumber = $lastBatch ? $lastBatch + 1 : 1001;

        $categories = ProductCategory::all();
        
        return view('product.create', compact('categories', 'nextBatchNumber')); 

    }




    
    //done and work old before price and batch
    // public function productStore(Request $request)
    // {
    //     $request->validate([
    //         'product_category_id' => 'required',
    //         'name' => 'required|string|max:255',
    //         'price' => 'required',
    //         'description' => 'nullable|string',
    //         'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    //     ]);

    //     $filename = null;

    //     if ($request->hasFile('image')) { 
    //         $file = $request->file('image');
    //         $filename = time() . '_' . $file->getClientOriginalName();
    //         $file->storeAs('public/products', $filename);
    //     } 

    //     $lastProduct = Product::latest()->first();
    //     $nextBatchNumber = $lastProduct && $lastProduct->batch ? $lastProduct->batch + 1 : 1001;

    //     $product = Product::create([
    //         'product_category_id' => $request->product_category_id,
    //         'name' => $request->name,
    //         'description' => $request->description,
    //         'price' => $request->price,
    //         'batch' => $nextBatchNumber, 
    //         'image' => $filename,
    //         'sku' => null,
    //     ]);
    
    //     $product->update([
    //         'sku' => empty($request->sku) 
    //             ? 'GJUS-' . $product->id 
    //             : $request->sku,

    //     ]);
        
    //     $branchs=Branch::select('id')->get();

    //     foreach ($branchs as $branch) {
    //        Branch_Product::create([
    //         'branch_id' => $branch->id,
    //         'product_id' => $product->id,
    //         'stock' => 0
    //        ]);
    //     }

    //     Toastr::success('Product created successfully.', 'Success');
    //     return redirect()->route('product.list');
    // }  





    // public function productStore(Request $request)
    // {
    //     $request->validate([
    //         'product_category_id' => 'required',
    //         'name' => 'required|string|max:255',
    //         'price' => 'required',
    //         'description' => 'nullable|string',
    //         'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    //     ]);

    //     $filename = null;

    //     if ($request->hasFile('image')) { 
    //         $file = $request->file('image');
    //         $filename = time() . '_' . $file->getClientOriginalName();
    //         $file->storeAs('public/products', $filename);
    //     } 

    //     $lastProduct = Product::latest()->first();
    //     $nextBatchNumber = $lastProduct && $lastProduct->batch ? $lastProduct->batch + 1 : 1001;

    //     $product = Product::create([
    //         'product_category_id' => $request->product_category_id,
    //         'name' => $request->name,
    //         'description' => $request->description,
    //         'price' => $request->price,
    //         'batch' => $nextBatchNumber, 
    //         'image' => $filename,
    //         'sku' => null,
    //     ]);
    
    //     $product->update([
    //         'sku' => empty($request->sku) 
    //             ? 'GJUS-' . $product->id 
    //             : $request->sku,

    //     ]);


    //     // Get branches where type = 'Warehouse'
    //     $warehouseBranches = Branch::where('type', 'Warehouse')->get();

    //     // Get all branches
    //     $allBranches = Branch::select('id')->get();

    //     // Store products for warehouse branches with price and batch
    //     foreach ($warehouseBranches as $branch) {
    //         Branch_Product::create([
    //             'branch_id' => $branch->id,
    //             'product_id' => $product->id,
    //             'price' => $product->price,
    //             'stock' => 0,
    //             'batch' => $product->batch
    //         ]);
    //     }

    //     // Store products for other branches as before
    //     foreach ($allBranches as $branch) {
    //         if (!$warehouseBranches->contains('id', $branch->id)) {
    //             Branch_Product::create([
    //                 'branch_id' => $branch->id,
    //                 'product_id' => $product->id,
    //                 'stock' => 0
    //             ]);
    //         }
    //     }

    //     Toastr::success('Product created successfully.', 'Success');
    //     return redirect()->route('product.list'); 

    // }





    // public function productStore(Request $request)
    // {
    //     $request->validate([
    //         'product_category_id' => 'required',
    //         'name' => 'required|string|max:255',
    //         'price' => 'required',
    //         'description' => 'nullable|string',
    //         'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    //     ]);
    
    //     $filename = null;
    
    //     if ($request->hasFile('image')) { 
    //         $file = $request->file('image');
    //         $filename = time() . '_' . $file->getClientOriginalName();
    //         $file->storeAs('public/products', $filename);
    //     } 
    
    //     // Fetch the last batch number from Branch_Product table
    //     $lastBatch = Branch_Product::latest('batch')->value('batch');
    //     $nextBatchNumber = $lastBatch ? $lastBatch + 1 : 1001;
    
    //     $product = Product::create([
    //         'product_category_id' => $request->product_category_id,
    //         'name' => $request->name,
    //         'description' => $request->description,
    //         'price' => $request->price,
    //         'batch' => $nextBatchNumber, 
    //         'image' => $filename,
    //         'sku' => null,
    //     ]);
    
    //     $product->update([
    //         'sku' => empty($request->sku) 
    //             ? 'GJUS-' . $product->id 
    //             : $request->sku,
    //     ]);
    
    //     // Get branches where type = 'Warehouse'
    //     $warehouseBranches = Branch::where('type', 'Warehouse')->get();
    
    //     // Get all branches
    //     $allBranches = Branch::select('id')->get();
    
    //     // Store products for warehouse branches with price and batch
    //     foreach ($warehouseBranches as $branch) {
    //         Branch_Product::create([
    //             'branch_id' => $branch->id,
    //             'product_id' => $product->id,
    //             'price' => $product->price,
    //             'stock' => 0,
    //             'batch' => $product->batch
    //         ]);
    //     }
    
    //     // Store products for other branches as before
    //     foreach ($allBranches as $branch) {
    //         if (!$warehouseBranches->contains('id', $branch->id)) {
    //             Branch_Product::create([
    //                 'branch_id' => $branch->id,
    //                 'product_id' => $product->id,
    //                 'stock' => 0
    //             ]);
    //         }
    //     }
    
    //     Toastr::success('Product created successfully.', 'Success');
    //     return redirect()->route('product.list'); 
    // }
    





   //last
    // public function productStore(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'product_category_id' => 'required',
    //         'name' => 'required|string|max:255|unique:products,name',
    //         'price' => 'required',
    //         'description' => 'nullable|string',
    //         'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    //     ]);
    
    //     if ($validator->fails()) {
    //         if ($validator->errors()->has('name')) {
    //             Toastr::error('This product has already store', 'Error');
    //             return redirect()->back()->withInput();
    //         }
    
    //         return redirect()->back()->withErrors($validator)->withInput();
    //     }

    //     $filename = null;
    //     if ($request->hasFile('image')) {
    //         $file = $request->file('image');
    //         $filename = time() . '_' . $file->getClientOriginalName();
    //         $file->storeAs('public/products', $filename);
    //     } 



    //     // do {
    //     //     $code = rand(10000, 99999);
    //     // } while (Product::where('code', $code)->exists()); 


    //     // Generate code based on last ChartOfAccount code
    //     $lastAccountCode = ChartOfAccount::latest('id')->value('code');
    //     $code = $lastAccountCode ? $lastAccountCode + 1 : 10000;

    //     // Ensure it doesn't already exist in the Product table
    //     while (Product::where('code', $code)->exists()) {
    //         $code++;
    //     }


    
    //     $lastBatch = Branch_Product::latest('batch')->value('batch');
    //     $nextBatchNumber = $lastBatch ? $lastBatch + 1 : 1001;
    //     $product = Product::create([
    //         'product_category_id' => $request->product_category_id,
    //         'name' => $request->name,
    //         'description' => $request->description,
    //         'price' => $request->price,
    //         'batch' => $nextBatchNumber,
    //         'image' => $filename,
    //         'sku' => null,
    //         'code' => $code, 
    //     ]);
    //     $product->update([
    //         'sku' => empty($request->sku)
    //             ? 'GJUS-' . $product->id
    //             : $request->sku,
    //     ]);

    //     $warehouseBranches = Branch::where('type', 'Warehouse')->get();
    //     $allBranches = Branch::select('id')->get();

    //     foreach ($warehouseBranches as $branch) {
    //         Branch_Product::create([
    //             'branch_id' => $branch->id,
    //             'product_id' => $product->id,
    //             'price' => $product->price,
    //             'stock' => 0,
    //             'batch' => $product->batch
    //         ]);
    //     }

    //     foreach ($allBranches as $branch) {
    //         if (!$warehouseBranches->contains('id', $branch->id)) {
    //             Branch_Product::create([
    //                 'branch_id' => $branch->id,
    //                 'product_id' => $product->id,
    //                 'stock' => 0
    //             ]);
    //         }
    //     }

    //     $accountTypes = [4, 5];
    //     foreach ($accountTypes as $type) {
    //         $subType = ($type == 4) ? 19 : 21;
    //         $account = new ChartOfAccount();
    //         $account->name        = $request->name;
    //         // $account->code = (int) ($type . rand(100, 999)); 
    //         $account->code        = $code;
    //         $account->type        = $type;
    //         $account->sub_type    = $subType;
    //         $account->description = $request->description;
    //         $account->is_enabled  = 1;
    //         $account->created_by  = Auth::user()->id;
    //         $account->save();
    //     }
    //     Toastr::success('Product created successfully.', 'Success');
    //     return redirect()->route('product.list'); 
    // }






    //new 

    public function productStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_category_id' => 'required',
            'name' => 'required|string|max:255|unique:products,name',
            'price' => 'required',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'product_type' => 'nullable|string|max:255', 
        ]);
    
        if ($validator->fails()) {
            if ($validator->errors()->has('name')) {
                Toastr::error('This product has already store', 'Error');
                return redirect()->back()->withInput();
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        $filename = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/products', $filename);
        }
    
        $code = 25001;
        while (Product::where('code', $code)->exists()) {
            $code++;
        }
    
        $lastBatch = Branch_Product::latest('batch')->value('batch');
        $nextBatchNumber = $lastBatch ? $lastBatch + 1 : 1001;
    
        $product = Product::create([
            'product_category_id' => $request->product_category_id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'batch' => $nextBatchNumber,
            'image' => $filename,
            'sku' => null,
            'code' => $code,
            'product_type' => $request->product_type,
        ]);
    
        $product->update([
            'sku' => empty($request->sku)
                ? 'GJUS-' . $product->id
                : $request->sku,
        ]);
    
        $warehouseBranches = Branch::where('type', 'Warehouse')->get();
        $allBranches = Branch::select('id')->get();
    
        foreach ($warehouseBranches as $branch) {
            Branch_Product::create([
                'branch_id' => $branch->id,
                'product_id' => $product->id,
                'price' => $product->price,
                'stock' => 0,
                'batch' => $product->batch
            ]);
        }
    
        foreach ($allBranches as $branch) {
            if (!$warehouseBranches->contains('id', $branch->id)) {
                Branch_Product::create([
                    'branch_id' => $branch->id,
                    'product_id' => $product->id,
                    'stock' => 0
                ]);
            }
        }
    
        $accountTypes = [1, 4, 5];
        $accountCodes = [];
        
        foreach ($accountTypes as $type) {
            $lastAccountCode = ChartOfAccount::where('type', $type)->latest('id')->value('code');
            $accountCode = $lastAccountCode + 1;
        
            $subType = ($type == 1) ? 14 : (($type == 4) ? 19 : 21);
        
            $account = new ChartOfAccount();
            $account->name = ($type == 1) ? 'Inventory - ' . $request->name : (($type == 4) ? 'Sales - ' . $request->name : 'Purchase - ' . $request->name);
            $account->code        = $accountCode;
            $account->type        = $type;
            $account->sub_type    = $subType;
            $account->description = $request->description;
            $account->is_enabled  = 1;
            $account->created_by  = Auth::user()->id;
            $account->save();
        
            $accountCodes[$type] = $accountCode;
        }
        
        ProductAccountMap::create([
            'product_category_id'           => $product->product_category_id,
            'product_id'                    => $product->id,
            'product_code'                  => $product->code,
            'product_name'                  => $product->name,
            'account_asset_inventory_code'  => $accountCodes[1] ?? null,
            'account_expense_code'          => $accountCodes[5] ?? null,
            'account_income_code'           => $accountCodes[4] ?? null,
        ]);
        
        Toastr::success('Product created successfully.', 'Success');
        return redirect()->route('product.list');

    }
    

    
    




    public function generateBarcode(Request $request)
    {
        $request->validate([
            'product' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);
    
        $product = Product::find($request->product);
        $quantity = $request->quantity;
        $barcodes = [];
    
        // for ($i = 0; $i < $quantity; $i++) {
        //     $barcodes[] = DNS1D::getBarcodeHTML($product->sku, 'C128', 1, 50);
        // }

        for ($i = 0; $i < $quantity; $i++) {
            $barcodes[] = DNS1D::getBarcodeHTML($product->sku, 'C128', 1.2, 30); // Adjusted to fit 5cm x 2.5cm
        } 

        return view('product.barcodegenerate', compact('barcodes', 'product'));
    }





    // public function ListBarcode()
    // {
    //     $productList = Product::all();
    
    //     foreach ($productList as $product) {
    //         if (!empty($product->sku)) {
    //             try {
    //                 $product->barcode = DNS1D::getBarcodeHTML($product->sku, 'C128', 1, 50);
    //             } catch (\Exception $e) {
    //                 $product->barcode = '<p>Error generating barcode: ' . $e->getMessage() . '</p>';
    //             }
    //         } else {
    //             $product->barcode = '<p>Barcode not available</p>';
    //         }
    //     }
    
    //     return view('product.barcodeproductlist', compact('productList'));
    // }
    


    //okay last 
    // public function ListBarcode()
    // {
    //     $productList = Product::all();

    //     foreach ($productList as $product) {
    //         if (!empty($product->sku)) {
    //             try {
    //                 $cleanSku = trim($product->sku);
    //                 $barcodeHTML = DNS1D::getBarcodeHTML($cleanSku, 'C128', 1, 50);

    //                 $product->barcode = '<div style="width: 150px; height: auto; overflow: hidden;">' 
    //                     . $barcodeHTML 
    //                     . '<p style="text-align: center; font-size: 12px;">' . $cleanSku . '</p>' 
    //                     . '</div>';
    //             } catch (\Exception $e) {
    //                 $product->barcode = '<p>Error generating barcode: ' . $e->getMessage() . '</p>';
    //             }
    //         } else {
    //             $product->barcode = '<p>Barcode not available</p>';
    //         }
    //     }

    //     return view('product.barcodeproductlist', compact('productList'));
    // }



    public function ListBarcode()
    {
        $productList = Product::all();

        foreach ($productList as $product) {
            if (!empty($product->sku)) {
                try {
                    $cleanSku = trim($product->sku);
                    
                    // Adjust scale to fit within 5cm x 2.5cm
                    $barcodeHTML = DNS1D::getBarcodeHTML($cleanSku, 'C128', 1.2, 30); // You can fine-tune the scale here

                    $product->barcode = '
                        <div style="width: 5cm; height: 2.5cm; overflow: hidden; text-align: center;">
                            ' . $barcodeHTML . '
                            <p style="margin: 0; font-size: 10px;">' . $cleanSku . '</p>
                        </div>';
                } catch (\Exception $e) {
                    $product->barcode = '<p>Error generating barcode: ' . $e->getMessage() . '</p>';
                }
            } else {
                $product->barcode = '<p>Barcode not available</p>';
            }
        }

        return view('product.barcodeproductlist', compact('productList'));
    }






    public function productView($id)
    {
        $productView = Product::findOrFail($id);
        return view('product.view',compact('productView'));
    }
      

    // public function productEdit($id)
    // {
    //     $productEdit = Product::findOrFail($id);
    //     return view('product.edit', compact('productEdit'));
    // }


    public function productEdit($id)
    {
        $productEdit = Product::findOrFail($id);
        $categories = ProductCategory::all(); 

        return view('product.edit', compact('productEdit', 'categories'));
    }





    public function productUpdate(Request $request, $id)
    {
        $request->validate([
            'product_category_id' => 'required',
            'name' => 'required|string|max:255',
            'price' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'product_type' => 'required', 
        ]);

        $product = Product::findOrFail($id);

        if ($request->hasFile('image')) { 
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/products', $filename);
            if ($product->image) {
                Storage::delete('public/products/' . $product->image);
            }
            $product->image = $filename;
        }

        $product->update([
            'product_category_id' => $request->product_category_id,
            'name' => $request->name,
            'product_type' => $request->product_type,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $product->image,
            'sku' => empty($request->sku) 
                ? 'GJUS-' . $product->id 
                : $request->sku,
        ]);

        ChartOfAccount::where('code', $product->code)->update([
                'name' => $request->name,
                'description' => $request->description,
            ]);

        Toastr::success('Product updated successfully.', 'Success');
        return redirect()->route('product.list');
    }




    public function productDelete($id)
    {
        $requisitionItemExists = RequisitionItem::where('product_id', $id)->exists();

        $branchProductStock = Branch_Product::where('product_id', $id)->sum('stock');

        if ($requisitionItemExists) {
            Toastr::error('Cannot delete product because it is associated with a requisition item.', 'Error');
            return redirect()->route('product.list');
        }

        if ($branchProductStock > 0) {
            Toastr::error('Cannot delete product because there is stock available.', 'Error');
            return redirect()->route('product.list');
        }

        $branchProducts = Branch_Product::where('product_id', $id)->get();

        $branchProductIds = $branchProducts->pluck('id')->toArray();

        Branch_Product::whereIn('id', $branchProductIds)->delete();

        $productList = Product::findOrFail($id);
        $productList->delete();

        Toastr::success('Product and its associated branch products deleted successfully.', 'Success');
        return redirect()->route('product.list');
    }


    public function getProductDetails($id)
    {
        $product = Product::find($id);

        if ($product) {
            return response()->json([
                'description' => $product->description,
                'price' => $product->price
            ]);
        }

        return response()->json(['error' => 'Product not found'], 404);
    }

   


}
