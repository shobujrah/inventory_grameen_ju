<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// A
use Illuminate\Support\Facades\Route;
// A

use DB;
use App\Models\PagePermission;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

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

    public function getPagePermission(Request $request)
    // public function getPagePermission(int $id)
    {
        // $userId = $request->id;
        // $user = User::find($userId);
        // $permissionGroups = User::getPermissionGroups($userId);

        // if ($user) {
        //     $roles = $user->roles;

        //     $permissions = [];

        //     foreach ($roles as $role) {
        //         $permissions = array_merge($permissions, $role->permissions->pluck('name')->toArray());
        //     }

        //     // Return the view with the data
        //     return view('usermanagement.list_users', compact('permissions', 'permissionGroups', 'role'));
        // }
            
        // -----

        
        $userId = $request->id;

        // print_r($_POST);
        // echo $userId;
        // exit();

        // $roles = Role::all();// wait 3:29 PM

        // Find the user by their ID
        $user = User::find($userId);

        $permissionGroups = User::getPermissionGroups($userId); // permissionGroups

        if ($user) {
            // Retrieve roles associated with the user
            $roles = $user->roles; // it was $roles
        
            $permissions = [];
        
            // Loop through each role to retrieve permissions
            foreach ($roles as $role) {
                // Retrieve permissions associated with each role
                $permissions = array_merge($permissions, $role->permissions->pluck('name')->toArray());
            }
            // A
            // foreach ($permissions as $value) {

                // $role = Role::findById($role->id, 'user');
                // $all_permissions = Permission::all();
                // $permission_groups = User::getpermissionGroups();
                // add part
                $all_permissions  = Permission::all();
                $permission_groups = User::getpermissionGroups();

                // {{$person->intern_extern == 0 ? 'checked' : ''}}
                // foreach($all_permissions as $all_permission) {

                // gateaway
                ?>

                
                <form action="<?php echo route('role/update', $role->id); ?>" method="POST">
                
                <?php echo method_field('PUT'); ?>
                <?php echo csrf_field(); ?>
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                <input type="hidden" id="name" value="<?php echo htmlspecialchars($role->name); ?>" name="name" placeholder="Enter a Role Name">

               
                <?php
    // echo $role->id; // A
                // Assuming $all_permissions contains all permissions and -$permissions- is the array containing permissions to be checked
                foreach ($all_permissions as $permission) {
                    $isChecked = in_array($permission->name, $permissions);
                    // Output the checkbox with the checked attribute based on $isChecked
                    echo '<div class="form-check">';
                    echo '<input type="checkbox" class="form-check-input" name="permissions[]" id="checkPermission'.$permission->id.'" value="'.$permission->name.'" '.($isChecked ? 'checked' : '').'>';
                    echo '<label class="form-check-label" for="checkPermission'.$permission->id.'">'.$permission->name.'</label>';
                    echo '</div>';
                }

                ?>
                <div class="row">
                                    <div class="col-6">
                                        <button type="submit" class="btn btn-primary paid-continue-btn" style="width: 100%;">Save</button>
                                    </div>
                                    <div class="col-6">
                                        <a data-bs-dismiss="modal"
                                            class="btn btn-primary paid-cancel-btn">Cancel
                                        </a>
                                    </div>
                                </div>
                <?php

        
            
            // } // }
            return;
            // A
            // Now $permissions contains all permissions associated with the user
            // return $permissions; // A
            // return $roles; # X
            // return view('usermanagement.list_users', compact('roles', 'permissions', 'permissionGroups')); 
            // return view('usermanagement.list_users', compact('role', 'permissions', 'permissionGroups')); // A

        } else {
            // Handle the case where the user is not found
            return [];
        }
        // A
        // exit();
        // $role = Role::findById($id, 'user'); // X
        // $all_permissions = Permission::all(); // X
        // $permission_groups = User::getpermissionGroups(); // X
        // return view('roles.role_edit', compact('role', 'all_permissions', 'permission_groups'));
        // return view('usermanagement.list_users', compact('role', 'all_permissions', 'permission_groups')); // X
        
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
    // public function roleEdit($role_id).

    // public function pagepermissionEdit(Request $request)
    // {
    //     $role_type = $request->role_type;
    //     $pagepermissionEdit = PagePermission::where('role_type',$role_type)->first();
    //     // return view('roles.role_edit',compact('pagepermissionEdit'));
    //     return view('roles.role_list',compact('pagepermissionEdit'));
    // }

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
