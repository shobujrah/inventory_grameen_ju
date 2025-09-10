<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;

class WarehouseController extends Controller
{
    /** index page warehouse list */
    public function warehouse()
    {
        $warehouseList = Warehouse::all();
        return view('warehouse.warehouse',compact('warehouseList'));
    }

    /** index page warehouse grid */
    public function warehouseGrid()
    {
        $warehouseList = Warehouse::all();
        return view('warehouse.warehouse-grid',compact('warehouseList'));
    }

    /** warehouse add page */
    public function warehouseAdd()
    {
        return view('warehouse.add-warehouse');
    }
    
    /** warehouse save record */
    public function warehouseSave(Request $request)
    {
        $request->validate([
            'name'    => 'required|string',
            'tittle'     => 'required|string',
            'address' => 'required|string',
            'status'   => 'required|string',
            'upload'        => 'required|image',
        ]);
        
        DB::beginTransaction();
        try {
           
            $upload_file = rand() . '.' . $request->upload->extension();
            $request->upload->move(storage_path('app/public/warehouse-photos/'), $upload_file);
            if(!empty($request->upload)) {
                $warehouse = new Warehouse;
                $warehouse->name   = $request->name;
                $warehouse->tittle    = $request->tittle;
                $warehouse->address = $request->address;
                $warehouse->status  = $request->status;
                $warehouse->upload = $upload_file;
                $warehouse->save();

                Toastr::success('Has been added successfully ','Success');
                DB::commit();
            }

            return redirect()->back();
           
        } catch(\Exception $e) {
            DB::rollback();
            Toastr::error('Fail, Add new warehouse ','Error');
            return redirect()->back();
        }
    }

    /** view for edit warehouse */
    public function warehouseEdit($id)
    {
        $warehouseEdit = Warehouse::where('id',$id)->first();
        return view('warehouse.edit-warehouse',compact('warehouseEdit'));
    }

    /** update record */
    public function warehouseUpdate(Request $request)
    {
        DB::beginTransaction();
        try {

            if (!empty($request->upload)) {
                unlink(storage_path('app/public/warehouse-photos/'.$request->image_hidden));
                $upload_file = rand() . '.' . $request->upload->extension();
                $request->upload->move(storage_path('app/public/warehouse-photos/'), $upload_file);
            } else {
                $upload_file = $request->image_hidden;
            }
           
            $name   = $request->name;
            $tittle    = $request->tittle;
            $address = $request->address;
            $status  = $request->status;

            $updateRecord = [
                'name'    => $name,
                'tittle'     => $tittle,
                'address' => $address,
                'status'   => $status,
                'upload' => $upload_file,
            ];
            Warehouse::where('id',$request->id)->update($updateRecord);
            
            Toastr::success('Has been updated successfully ','Success');
            DB::commit();
            return redirect()->back();
           
        } catch(\Exception $e) {
            DB::rollback();
            Toastr::error('Fail, Update warehouse ','Error');
            return redirect()->back();
        }
    }

    /** warehouse delete */
    public function warehouseDelete(Request $request)
    {
        DB::beginTransaction();
        try {
           
            if (!empty($request->id)) {
                Warehouse::destroy($request->id);
                unlink(storage_path('app/public/warehouse-photos/'.$request->avatar));
                DB::commit();
                Toastr::success('Warehouse has been deleted successfully ','Success');
                return redirect()->back();
            }
    
        } catch(\Exception $e) {
            DB::rollback();
            Toastr::error('Warehouse deletion failed ','Error');
            return redirect()->back();
        }
    }

    /** warehouse view page */
    public function warehouseView($id)
    {
        $warehouseView = Warehouse::where('id',$id)->first();
        return view('warehouse.warehouse-view',compact('warehouseView'));
    }
}
