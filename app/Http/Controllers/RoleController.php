<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// A
use Illuminate\Support\Facades\Route;
// A

use DB;
// use App\Models\Role;

use Brian2694\Toastr\Facades\Toastr;

# A #
// use App\User;
// use app\Models\User; #
// use App\Models\Admin;

// use App\Models\User as RegUser;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
# A #

class RoleController extends Controller
{
    # A #

    public $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            // $this->user = Auth::guard('admin')->user(); # guard name - (user) can be used here
            $this->user = Auth::guard('user')->user();
            return $next($request);
        });
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    # A #

    /** index page */
    public function roleList()
    {
        // if (is_null($this->user) || !$this->user->can('role.view')) {
        //     abort(403, 'Sorry !! You are Unauthorized to view any role !');
        // }

        $roles = Role::all();
        // return view('backend.pages.roles.index', compact('roles'));
        return view('roles.role_list',compact('roles'));
        // // A
        // $routeCollection = Route::getRoutes();
        // // A
        // $roleList = Role::all();
        // return view('roles.role_list',compact('roleList','routeCollection'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    /** role add */
    public function roleAdd()
    {
        // if (is_null($this->user) || !$this->user->can('role.create')) {
        //     abort(403, 'Sorry !! You are Unauthorized to create any role !');
        // }

        $all_permissions  = Permission::all();
        $permission_groups = User::getpermissionGroups();

        return view('roles.role_add', compact('all_permissions', 'permission_groups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveRecord(Request $request)
    {
        // if (is_null($this->user) || !$this->user->can('role.create')) {
        //     abort(403, 'Sorry !! You are Unauthorized to create any role !');
        // }

        // Validation Data
        $request->validate([
            'name' => 'required|max:100|unique:roles'
        ], [
            'name.requried' => 'Please give a role name'
        ]);

        // Process Data
        $role = Role::create(['name' => $request->name, 'guard_name' => 'user']);

        // $role = DB::table('roles')->where('name', $request->name)->first();
        $permissions = $request->input('permissions');

        if (!empty($permissions)) {
            $role->syncPermissions($permissions);
        }
        // session()->flash('success', 'Role has been created !!');
        Toastr::success('Role has been added successfully ','Success');
        return back();
    }
    /** save record # A # pre*/
    // public function saveRecord(Request $request)
    // {
    //     $request->validate([
    //         'role_type' => 'required|string',
    //         // 'class'        => 'required|string',
    //     ]);
        
    //     DB::beginTransaction();
    //     try {
    //             $saveRecord = new Role;
    //             $saveRecord->role_type   = $request->role_type;
    //             // $saveRecord->class          = $request->class;
    //             $saveRecord->save();

    //             Toastr::success('Has been add successfully ','Success');
    //             DB::commit();
    //         return redirect()->back();
           
    //     } catch(\Exception $e) {
    //         \Log::info($e);
    //         DB::rollback();
    //         Toastr::error('fail, Add new record ','Error');
    //         return redirect()->back();
    //     }
    // }
    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function roleEdit(int $id)
    {
        // if (is_null($this->user) || !$this->user->can('role.edit')) {
        //     abort(403, 'Sorry !! You are Unauthorized to edit any role !');
        // }

        $role = Role::findById($id, 'user');
        $all_permissions = Permission::all();
        $permission_groups = User::getpermissionGroups();
        return view('roles.role_edit', compact('role', 'all_permissions', 'permission_groups'));
    }
    /** role edit view # A # pre */
    // public function roleEdit($role_id)
    // {
    //     $roleEdit = Role::where('role_id',$role_id)->first();
    //     return view('roles.role_edit',compact('roleEdit'));
    // }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateRecord(Request $request, int $id)
    {
        // if (is_null($this->user) || !$this->user->can('role.edit')) {
        //     abort(403, 'Sorry !! You are Unauthorized to edit any role !');
        // }

        // TODO: You can delete this in your local. This is for heroku publish.
        // This is only for Super Admin role,
        // so that no-one could delete or disable it by somehow.

        if (auth()->id() !== 1 && $id === 1) {
            // Display an error message if the user is not authorized
            Toastr::error('Fail, Sorry, You are not authorized to edit this role.', 'Error');
            return back();
        }

        // Validation Data
        $request->validate([
            'name' => 'required|max:100|unique:roles,name,' . $id
        ], [
            'name.requried' => 'Please give a role name'
        ]);

        $role = Role::findById($id, 'user');
        $permissions = $request->input('permissions');

        if (!empty($permissions)) {
            $role->name = $request->name;
            $role->save();
            $role->syncPermissions($permissions);
        }

        // session()->flash('success', 'Role has been updated !!');
        Toastr::success('Role has been updated successfully ','Success');
        return back();
    }
    /** update record # A # pre */
    // public function updateRecord(Request $request)
    // {
    //     DB::beginTransaction();
    //     try {
            
    //         $updateRecord = [
    //             'role_type' => $request->role_type,
    //             // 'class'        => $request->class,
    //         ];

    //         Role::where('role_id',$request->role_id)->update($updateRecord);
    //         Toastr::success('Has been update successfully ','Success');
    //         DB::commit();
    //         return redirect()->back();
           
    //     } catch(\Exception $e) {
    //         \Log::info($e);
    //         DB::rollback();
    //         Toastr::error('Fail, update record ','Error');
    //         return redirect()->back();
    //     }
    // }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteRecord(int $id)
    {
        // if (is_null($this->user) || !$this->user->can('role.delete')) {
        //     abort(403, 'Sorry !! You are Unauthorized to delete any role !');
        // }

        // TODO: You can delete this in your local. This is for heroku publish.
        // This is only for Super Admin role,
        // so that no-one could delete or disable it by somehow.

        if ($id === 1) {
            if (auth()->id() === 1) {
                Toastr::error('Fail, Sorry, You are not authorized to delete your own role.', 'Error');
            } else {
                Toastr::error('Fail, Sorry, You are not authorized to delete this role.', 'Error');
            }
            return back();
        }

        
        // $role = Role::findById($id, 'admin'); # ChatGPT -> Auth guard [RegUser] is not defined.
        $role = Role::findById($id, 'user');
        if (!is_null($role)) {
            $role->delete();
        }

        // session()->flash('success', 'Role has been deleted !!');
        Toastr::success('Role has been deleted successfully ','Success');
        return back();
    }
    /** delete record # A # pre */
    // public function deleteRecord(Request $request)
    // {
    //     DB::beginTransaction();
    //     try {

    //         Role::where('role_id',$request->role_id)->delete();
    //         DB::commit();
    //         Toastr::success('Deleted record successfully ','Success');
    //         return redirect()->back();
    //     } catch(\Exception $e) {
    //         DB::rollback();
    //         Toastr::error('Deleted record fail ','Error');
    //         return redirect()->back();
    //     }
    // }

}
