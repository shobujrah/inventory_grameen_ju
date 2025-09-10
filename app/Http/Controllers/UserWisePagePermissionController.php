<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// A
use Illuminate\Support\Facades\Route;
// A

use DB;
use App\Models\PagePermission;

use Brian2694\Toastr\Facades\Toastr;

class PagePermissionController extends Controller
{
    /** index page */
    public function pagepermissionList()
    {
        // A
        $routeCollection = Route::getRoutes();
        // A
        $pagepermissionList = PagePermission::all();
        return view('pagepermissions.pagepermission_list',compact('pagepermissionList','routeCollection'));
    }

    /** pagepermission add */
    public function pagepermissionAdd()
    {
        return view('pagepermissions.pagepermission_add');
    }

    /** save record */
    public function saveRecord(Request $request)
    {
        // $request->validate([
        //     'user_id' => 'required|string',
        //     'role_type' => 'required|string',
        //     'route' => 'required|string',
        // ]);

        // $result = User_favorite::where('user_id',Auth::getUser()->id)
        //                  ->where('item_id',$item->id)
        //                  ->first();

        # for multiple
        // $user_favorites = DB::table('user_favorites')
        //     ->where('user_id', '=', Auth::user()->id)
        //     ->where('item_id', '=', $item->id)
        //     ->first();

        

        # json way starts
        $routes = $request->route;

        // $routesArray = array();
    
        // foreach($routes as $route){
        //    $routesArray[] = $route;
        // }
    
        // json_encode($routesArray);

        # json way ends

        if (!empty($routes)) {
            $page_permissions = DB::table('page_permissions')->where('role_type', '=', $request->role_type)->first();

            if (is_null($page_permissions)) {
                DB::beginTransaction();
                try {
                    $saveRecord = new PagePermission;
                    $saveRecord->user_id = $request->user_id;
                    $saveRecord->role_type = $request->role_type;
                    $saveRecord->route = implode(", ",$routes);
                    $saveRecord->save();

                    Toastr::success('Has been add successfully ','Success');
                    DB::commit();
                    return redirect()->back();
                
                } catch(\Exception $e) {
                    \Log::info($e);
                    DB::rollback();
                    Toastr::error('fail, Add new record ','Error');
                    return redirect()->back();
                }
            } else {
                // $page_permissions->delete();
                PagePermission::where('role_type', $request->role_type)->delete();
                DB::beginTransaction();
                try {
                    $saveRecord = new PagePermission;
                    $saveRecord->user_id = $request->user_id;
                    $saveRecord->role_type = $request->role_type;
                    $saveRecord->route = implode(", ",$routes);
                    $saveRecord->save();

                    Toastr::success('Has been add successfully ','Success');
                    DB::commit();
                    return redirect()->back();
                
                } catch(\Exception $e) {
                    \Log::info($e);
                    DB::rollback();
                    Toastr::error('Fail, Add new record ','Error');
                    return redirect()->back();
                }
            }
        } else {
            Toastr::error('Fail, Page permission is not checked ','Error');
            return redirect()->back();
        }
        
    }

    /** PagePermission edit view */
    // public function roleEdit($role_id)
    public function pagepermissionEdit(Request $request)
    {
        $role_type = $request->role_type;
        $pagepermissionEdit = PagePermission::where('role_type',$role_type)->first();
        // return view('roles.role_edit',compact('pagepermissionEdit'));
        return view('roles.role_list',compact('pagepermissionEdit'));
    }

    /** update record */
    public function updateRecord(Request $request)
    {
        DB::beginTransaction();
        try {
            
            $updateRecord = [
                'user_id' => $request->user_id,
                'role_type' => $request->role_type,
                'route' => $request->route,
            ];

            PagePermission::where('pagepermission_id',$request->pagepermission_id)->update($updateRecord);
            Toastr::success('Has been update successfully ','Success');
            DB::commit();
            return redirect()->back();
           
        } catch(\Exception $e) {
            \Log::info($e);
            DB::rollback();
            Toastr::error('Fail, update record ','Error');
            return redirect()->back();
        }
    }

    /** delete record */
    public function deleteRecord(Request $request)
    {
        DB::beginTransaction();
        try {

            PagePermission::where('pagepermission_id',$request->pagepermission_id)->delete();
            DB::commit();
            Toastr::success('Deleted record successfully ','Success');
            return redirect()->back();
        } catch(\Exception $e) {
            DB::rollback();
            Toastr::error('Deleted record fail ','Error');
            return redirect()->back();
        }
    }

}
