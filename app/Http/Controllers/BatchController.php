<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Batch;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;

class BatchController extends Controller
{
    /** index page batch list */
    public function batch()
    {
        $batchList = Batch::all();
        return view('batch.batch',compact('batchList'));
    }

    /** index page batch grid */
    public function batchGrid()
    {
        $batchList = Batch::all();
        return view('batch.batch-grid',compact('batchList'));
    }

    /** batch add page */
    public function batchAdd()
    {
        return view('batch.add-batch');
    }
    
    /** batch save record */
    public function batchSave(Request $request)
    {
        $request->validate([
            'name'    => 'required|string',
            'description'     => 'required|string',
        ]);
        
        DB::beginTransaction();
        try {
           
            if(!empty($request->name)) {
                $batch = new Batch;
                $batch->name   = $request->name;
                $batch->description    = $request->description;
                $batch->save();

                Toastr::success('Has been added successfully ','Success');
                DB::commit();
            }

            return redirect()->back();
           
        } catch(\Exception $e) {
            DB::rollback();
            Toastr::error('Fail, Add new batch ','Error');
            return redirect()->back();
        }
    }

    /** view for edit batch */
    public function batchEdit($id)
    {
        $batchEdit = Batch::where('id',$id)->first();
        return view('batch.edit-batch',compact('batchEdit'));
    }

    /** update record */
    public function batchUpdate(Request $request)
    {
        
        DB::beginTransaction();
        try {
           
            $name   = $request->name;
            $description    = $request->description;

            $updatedRecord = [
                'name'    => $name,
                'description'     => $description,
            ];
            Batch::where('id',$request->id)->update($updatedRecord);
            
            Toastr::success('Has been updated successfully ','Success');
            DB::commit();
            return redirect()->back();
           
        } catch(\Exception $e) {
            DB::rollback();
            Toastr::error('Fail, Update batch ','Error');
            return redirect()->back();
        }
    }

    /** batch delete */
    public function batchDelete(Request $request)
    {
        DB::beginTransaction();
        try {
           
            if (!empty($request->id)) {
                Batch::destroy($request->id);
                DB::commit();
                Toastr::success('Batch deleted successfully ','Success');
                return redirect()->back();
            }
    
        } catch(\Exception $e) {
            DB::rollback();
            Toastr::error('Batch deletion Fail ','Error');
            return redirect()->back();
        }
    }

    /** batch view page */
    public function batchView($id)
    {
        $batchView = Batch::where('id',$id)->first();
        return view('batch.batch-view',compact('batchView'));
    }
}
