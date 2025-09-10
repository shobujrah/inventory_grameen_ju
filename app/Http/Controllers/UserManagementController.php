<?php

namespace App\Http\Controllers;

use Log;
use Auth;
use Session;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Branch;
use Illuminate\Http\Request;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Http;

// A
use Brian2694\Toastr\Facades\Toastr; 
// A

# A #
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
# A #

class UserManagementController extends Controller
{
    public $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('user')->user();
            return $next($request);
        });
    }

    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /** index page */

    public function index()
    {
        $routeCollection = Route::getRoutes();
        return view('usermanagement.list_users',compact('routeCollection'));
    }


    
    public function unverifiedUsers()
    {
        
        return view('usermanagement.verify_users');
    }




    public function userVerify(Request $request)
    {
        DB::beginTransaction();
        try {
            if (Session::get('role_name') === 'Super Admin' || Session::get('role_name') === 'Admin')
            {
                $is_verified = 1; 
                $update = [
                    'is_verified'   => $is_verified,
                ];

                User::where('user_id',$request->user_id)->update($update);

            } else {
                Toastr::error('User authentication failed ','Error');
            }

            DB::commit();
            Toastr::success('User verified successfully ','Success');
            return redirect()->back();
    
        } catch(\Exception $e) {
            Log::info($e);
            DB::rollback();
            Toastr::error('User verified fail ','Error');
            return redirect()->back();
        }
    }


    public function userView($id)
    {
        $roles  = Role::all();
        $types = DB::table('user_types')->get();

        $users = User::where('user_id',$id)->first();

        $branches = Branch::select('id', 'type', 'name')->get();
        
        return view('usermanagement.user_update',compact('users','roles','types', 'branches'));
    }





    public function userUpdate(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = User::find($request->user_pk); 

            $branch = Branch::find($request->branch_id);
            $branch_id = $branch->id;
            $branch_name = $branch->name;
            $branch_type = $branch->type;

            $user_id       = $request->user_id;
            $name          = $request->name;
            $email         = $request->email;
            $position      = $request->position;
            $phone         = $request->phone_number;
            $date_of_birth = $request->date_of_birth;
            $department    = $request->department;
            $status        = $request->status;
            $navigate_to   = $request->navigate_to;
            $blood_group   = $request->blood_group;
            $address       = $request->address;

            $role_name = $request->roles ? $request->roles[0] : null;

            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->storeAs(public_path('/images/'), $filename);
                if ($user->avatar) {
                    Storage::delete(public_path('/images/'), $filename);
                }
                $user->avatar = $filename;
            }

            $update = [
                'user_id'       => $user_id,
                'branch_id'     => $branch_id,
                'branch_name'   => $branch_name,
                'branch_type'   => $branch_type,
                'name'          => $name,
                'role_name'     => $role_name, 
                'email'         => $email,
                'position'      => $position,
                'phone_number'  => $phone,
                'date_of_birth' => $date_of_birth,
                'department'    => $department,
                'status'        => $status,
                'navigate_to'   => $navigate_to,
                'blood_group'   => $blood_group,
                'address'       => $address,
                'avatar'        => $user->avatar,
            ];

            User::where('user_id', $request->user_id)->update($update);

            $user->roles()->detach();
            if ($request->roles) {
                $user->assignRole($request->roles);
            }

            DB::commit();
            Toastr::success('User updated successfully ', 'Success');
            return redirect()->route('list/users');
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('User update failed ', 'Error');
            return redirect()->route('list/users');
        }
    }




    public function userDelete(Request $request)
    {
        DB::beginTransaction();
        try {
            if (Session::get('role_name') === 'Super Admin' || Session::get('role_name') === 'Admin') 
            {
                if (Session::get('role_name') === 'Super Admin' && auth()->user()->id == $request->user_id) {
                    Toastr::error("You can't delete your own account", 'Error');
                    return redirect()->back();
                }
                
                if ($request->avatar == 'photo_defaults.jpg') 
                {
                    User::destroy($request->user_id);
                } 
                else 
                {
                    User::destroy($request->user_id);
                    unlink('images/' . $request->avatar);
                }
            } 
            else 
            {
                Toastr::error('User deletion failed', 'Error');
            }
    
            DB::commit();
            Toastr::success('User has been deleted successfully', 'Success');
            return redirect()->back();
    
        } catch (\Exception $e) {
            Log::info($e);
            DB::rollback();
            Toastr::error('User deletion failed', 'Error');
            return redirect()->back();
        }
    }
    




    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password'     => ['required', new MatchOldPassword],
            'new_password'         => ['required'],
            'new_confirm_password' => ['same:new_password'],
        ]);
    
        User::find(auth()->user()->id)->update(['password'=> Hash::make($request->new_password)]);
        DB::commit(); 
        auth()->logout();
    
        Toastr::success('Password has been changed successfully. Please log in again.', 'Success');
        return redirect()->route('login');
    }
    



    //old all okay before passowrd reset 
    // public function getUsersData(Request $request)
    // {
    //     $draw            = $request->get('draw');
    //     $start           = $request->get("start");
    //     $rowPerPage      = $request->get("length"); 
    //     $columnIndex_arr = $request->get('order');
    //     $columnName_arr  = $request->get('columns');
    //     $order_arr       = $request->get('order');
    //     $search_arr      = $request->get('search');

    //     $columnIndex     = $columnIndex_arr[0]['column']; 
    //     $columnName      = $columnName_arr[$columnIndex]['data']; 
    //     $columnSortOrder = $order_arr[0]['dir']; 
    //     $searchValue     = $search_arr['value'];

    //     $is_verified = 1;

    //     $users = DB::table('users')->where('is_verified', $is_verified); 

    //     $totalRecords = $users->count();

    //     $totalRecordsWithFilter = $users->where(function ($query) use ($searchValue) {
    //         $query->where('name', 'like', '%' . $searchValue . '%');
    //         $query->orWhere('email', 'like', '%' . $searchValue . '%');
    //         $query->orWhere('position', 'like', '%' . $searchValue . '%');
    //         $query->orWhere('phone_number', 'like', '%' . $searchValue . '%');
    //         $query->orWhere('status', 'like', '%' . $searchValue . '%');
    //     })->count();

    //     if ($columnName == 'name') {
    //         $columnName = 'name';
    //     }
    //     $records = $users->orderBy($columnName, $columnSortOrder)
    //         ->where(function ($query) use ($searchValue) {
    //             $query->where('name', 'like', '%' . $searchValue . '%');
    //             $query->orWhere('email', 'like', '%' . $searchValue . '%');
    //             $query->orWhere('position', 'like', '%' . $searchValue . '%');
    //             $query->orWhere('phone_number', 'like', '%' . $searchValue . '%');
    //             $query->orWhere('status', 'like', '%' . $searchValue . '%');
    //         })
    //         ->skip($start)
    //         ->take($rowPerPage)
    //         ->get();
    //     $data_arr = [];
        
    //     foreach ($records as $key => $record) {
    //         $modify = '
    //             <td class="text-right">
    //                 <div class="dropdown dropdown-action">
    //                     <a href="" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
    //                         <i class="fas fa-ellipsis-v ellipse_color"></i>
    //                     </a>
    //                     <div class="dropdown-menu dropdown-menu-right">
    //                         <a class="dropdown-item" href="'.url('users/add/edit/'.$record->user_id).'">
    //                             <i class="fas fa-pencil-alt m-r-5"></i> Edit
    //                         </a>
    //                         <a class="dropdown-item" href="'.url('users/delete/'.$record->id).'">
    //                         <i class="fas fa-trash-alt m-r-5"></i> Delete
    //                     </a>
    //                     </div>
    //                 </div>
    //             </td>
    //         ';
    //         $avatar = '
    //             <td>
    //                 <h2 class="table-avatar">
    //                     <a class="avatar-sm me-2">
    //                         <img class="avatar-img rounded-circle avatar" data-avatar='.$record->avatar.' src="/images/'.$record->avatar.'"alt="'.$record->name.'">
    //                     </a>
    //                 </h2>
    //             </td>
    //         ';

    //         if ($record->status === 'Active') {
    //             $status = '<td><span class="badge bg-success-dark">'.$record->status.'</span></td>';
    //             $permissionButton = '
    //                 <a class="btn btn-sm bg-danger-light update_permission user_id" data-bs-toggle="modal" data-user_id="'.$record->id.'" data-bs-target="#update_permission_modal">
    //                     <i class="fas fa-clipboard-check"></i>
    //                 </a>';
    //         } elseif ($record->status === 'Disable') {
    //             $status = '<td><span class="badge bg-danger-dark">'.$record->status.'</span></td>';
    //             $permissionButton = ''; 
    //         } elseif ($record->status === 'Inactive') {
    //             $status = '<td><span class="badge badge-warning">'.$record->status.'</span></td>';
    //             $permissionButton = ''; 
    //         } else {
    //             $status = '<td><span class="badge badge-secondary">'.$record->status.'</span></td>';
    //             $permissionButton = ''; 
    //         }
            
    //         $modify = '
    //             <td class="text-end"> 
    //                 <div class="actions">
    //                     '.$permissionButton.'
    //                     <a href="'.url('view/user/edit/'.$record->user_id).'" class="btn btn-sm bg-danger-light">
    //                         <i class="feather-edit"></i>
    //                     </a>
    //                 </div>
    //             </td>
    //         ';
           
    //         $data_arr [] = [
    //             "user_id"      => $record->user_id,
    //             "avatar"       => $avatar,
    //             "name"         => $record->name,

    //             "branch_name"  =>$record->branch_name,
    //             "branch_type"  =>$record->branch_type,


    //             "role_name"  =>$record->role_name,

    //             "email"        => $record->email,
    //             "position"     => $record->position,
    //             "phone_number" => $record->phone_number,
    //             "join_date"    => $record->join_date,
    //             "status"       => $status, 
    //             "modify"       => $modify, 
    //         ];
    //     }

    //     $response = [
    //         "draw"                 => intval($draw),
    //         "iTotalRecords"        => $totalRecords,
    //         "iTotalDisplayRecords" => $totalRecordsWithFilter,
    //         "aaData"               => $data_arr
    //     ];
    //     return response()->json($response);
    // }





    //new add here passowrd reset 
    public function getUsersData(Request $request)
    {
        $draw            = $request->get('draw');
        $start           = $request->get("start");
        $rowPerPage      = $request->get("length"); 
        $columnIndex_arr = $request->get('order');
        $columnName_arr  = $request->get('columns');
        $order_arr       = $request->get('order');
        $search_arr      = $request->get('search');

        $columnIndex     = $columnIndex_arr[0]['column']; 
        $columnName      = $columnName_arr[$columnIndex]['data']; 
        $columnSortOrder = $order_arr[0]['dir']; 
        $searchValue     = $search_arr['value'];

        $is_verified = 1;

        $users = DB::table('users')->where('is_verified', $is_verified); 

        $totalRecords = $users->count();

        $totalRecordsWithFilter = $users->where(function ($query) use ($searchValue) {
            $query->where('name', 'like', '%' . $searchValue . '%');
            $query->orWhere('email', 'like', '%' . $searchValue . '%');
            $query->orWhere('position', 'like', '%' . $searchValue . '%');
            $query->orWhere('phone_number', 'like', '%' . $searchValue . '%');
            $query->orWhere('status', 'like', '%' . $searchValue . '%');
        })->count();

        if ($columnName == 'name') {
            $columnName = 'name';
        }
        $records = $users->orderBy($columnName, $columnSortOrder)
            ->where(function ($query) use ($searchValue) {
                $query->where('name', 'like', '%' . $searchValue . '%');
                $query->orWhere('email', 'like', '%' . $searchValue . '%');
                $query->orWhere('position', 'like', '%' . $searchValue . '%');
                $query->orWhere('phone_number', 'like', '%' . $searchValue . '%');
                $query->orWhere('status', 'like', '%' . $searchValue . '%');
            })
            ->skip($start)
            ->take($rowPerPage)
            ->get();
        $data_arr = [];
        
        foreach ($records as $key => $record) {
            $modify = '
                <td class="text-right">
                    <div class="dropdown dropdown-action">
                        <a href="" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-ellipsis-v ellipse_color"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="'.url('users/add/edit/'.$record->user_id).'">
                                <i class="fas fa-pencil-alt m-r-5"></i> Edit
                            </a>
                            <a class="dropdown-item" href="'.url('users/delete/'.$record->id).'">
                            <i class="fas fa-trash-alt m-r-5"></i> Delete
                        </a>
                        <a href="#" class="btn btn-sm bg-danger-light reset_password"
                            data-bs-toggle="modal"
                            data-bs-target="#password_reset_modal"
                            data-user_id="'.$record->user_id.'">
                            <i class="fas fa-key"></i>
                        </a>
                        </div>
                    </div>
                </td>
            ';
            $avatar = '
                <td>
                    <h2 class="table-avatar">
                        <a class="avatar-sm me-2">
                            <img class="avatar-img rounded-circle avatar" data-avatar='.$record->avatar.' src="/images/'.$record->avatar.'"alt="'.$record->name.'">
                        </a>
                    </h2>
                </td>
            ';

            if ($record->status === 'Active') {
                $status = '<td><span class="badge bg-success-dark">'.$record->status.'</span></td>';
                $permissionButton = '
                    <a class="btn btn-sm bg-danger-light update_permission user_id" data-bs-toggle="modal" data-user_id="'.$record->id.'" data-bs-target="#update_permission_modal">
                        <i class="fas fa-clipboard-check"></i>
                    </a>';
            } elseif ($record->status === 'Disable') {
                $status = '<td><span class="badge bg-danger-dark">'.$record->status.'</span></td>';
                $permissionButton = ''; 
            } elseif ($record->status === 'Inactive') {
                $status = '<td><span class="badge badge-warning">'.$record->status.'</span></td>';
                $permissionButton = ''; 
            } else {
                $status = '<td><span class="badge badge-secondary">'.$record->status.'</span></td>';
                $permissionButton = ''; 
            }
            
            $modify = '
                <td class="text-end"> 
                    <div class="actions">
                        '.$permissionButton.'
                        <a href="'.url('view/user/edit/'.$record->user_id).'" class="btn btn-sm bg-danger-light">
                            <i class="feather-edit"></i>
                        </a>
                        <a href="#" class="btn btn-sm bg-danger-light reset_password"
                            data-bs-toggle="modal"
                            data-bs-target="#password_reset_modal"
                            data-user_id="'.$record->user_id.'">
                            <i class="fas fa-key"></i>
                        </a>
                    </div>
                </td>
            ';
           
            $data_arr [] = [
                "user_id"      => $record->user_id,
                "avatar"       => $avatar,
                "name"         => $record->name,

                "branch_name"  =>$record->branch_name,
                "branch_type"  =>$record->branch_type,


                "role_name"  =>$record->role_name,

                "email"        => $record->email,
                "position"     => $record->position,
                "phone_number" => $record->phone_number,
                "join_date"    => $record->join_date,
                "status"       => $status, 
                "modify"       => $modify, 
            ];
        }

        $response = [
            "draw"                 => intval($draw),
            "iTotalRecords"        => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordsWithFilter,
            "aaData"               => $data_arr
        ];
        return response()->json($response);
    }





    // public function resetPassword(Request $request)
    // {
    //     $request->validate([
    //         'user_id' => 'required|exists:users,user_id',
    //         'new_password' => 'required|string|min:8|confirmed',
    //     ]);

    //     $user = User::where('user_id', $request->user_id)->first();

    //     if (!$user) {
    //         return back()->with('error', 'User not found.');
    //     }

    //     // Update password with hashing
    //     $user->password = Hash::make($request->new_password);
    //     $user->save();

    //     Toastr::success('Password has been reset successfully', 'Success');

    //     return back();
    // }



    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,user_id',
            'new_password' => 'required|string|min:8|confirmed',
        ], [
            'new_password.min' => 'Please enter at least 8 characters for the password.',
            'new_password.confirmed' => 'Password does not match the confirmation.',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                Toastr::error($error, 'Error');
            }
            return redirect()->back()->withInput();
        }

        $user = User::where('user_id', $request->user_id)->first();

        if (!$user) {
            Toastr::error('User not found.', 'Error');
            return back();
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        Toastr::success('Password has been reset successfully', 'Success');

        return back();
    }



    public function getUnverifiedUsersData(Request $request)
    {
        $draw            = $request->get('draw');
        $start           = $request->get("start");
        $rowPerPage      = $request->get("length"); 
        $columnIndex_arr = $request->get('order');
        $columnName_arr  = $request->get('columns');
        $order_arr       = $request->get('order');
        $search_arr      = $request->get('search');

        $columnIndex     = $columnIndex_arr[0]['column']; 
        $columnName      = $columnName_arr[$columnIndex]['data']; 
        $columnSortOrder = $order_arr[0]['dir']; 
        $searchValue     = $search_arr['value']; 

        $is_verified = 0;

        $users = DB::table('users')->where('is_verified', '$is_verified'); 

        $totalRecords = $users->count();

        $totalRecordsWithFilter = $users->where(function ($query) use ($searchValue) {
            $query->where('name', 'like', '%' . $searchValue . '%');
            $query->orWhere('email', 'like', '%' . $searchValue . '%');
            $query->orWhere('position', 'like', '%' . $searchValue . '%');
            $query->orWhere('phone_number', 'like', '%' . $searchValue . '%');
            $query->orWhere('status', 'like', '%' . $searchValue . '%');
        })->count();

        if ($columnName == 'name') {
            $columnName = 'name';
        }
        $records = $users->orderBy($columnName, $columnSortOrder)
            ->where(function ($query) use ($searchValue) {
                $query->where('name', 'like', '%' . $searchValue . '%');
                $query->orWhere('email', 'like', '%' . $searchValue . '%');
                $query->orWhere('position', 'like', '%' . $searchValue . '%');
                $query->orWhere('phone_number', 'like', '%' . $searchValue . '%');
                $query->orWhere('status', 'like', '%' . $searchValue . '%');
            })
            ->skip($start)
            ->take($rowPerPage)
            ->get();
        $data_arr = [];
        
        foreach ($records as $key => $record) {
            $modify = '
                <td class="text-right">
                    <div class="dropdown dropdown-action">
                        <a href="" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-ellipsis-v ellipse_color"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="'.url('users/verify/'.$record->id).'">
                                <i class="fas fa-user-shield m-r-5"></i> Verify
                            </a>
                            <a class="dropdown-item" href="'.url('users/add/edit/'.$record->user_id).'">
                                <i class="fas fa-pencil-alt m-r-5"></i> Edit
                            </a>
                            <a class="dropdown-item" href="'.url('users/delete/'.$record->id).'">
                                <i class="fas fa-trash-alt m-r-5"></i> Delete
                            </a>
                        </div>
                    </div>
                </td>
            ';
            $avatar = '
                <td>
                    <h2 class="table-avatar">
                        <a class="avatar-sm me-2">
                            <img class="avatar-img rounded-circle avatar" data-avatar='.$record->avatar.' src="/images/'.$record->avatar.'"alt="'.$record->name.'">
                        </a>
                    </h2>
                </td>
            ';
            if ($record->status === 'Active') {
                $status = '<td><span class="badge bg-success-dark">'.$record->status.'</span></td>';
            } elseif ($record->status === 'Disable') {
                $status = '<td><span class="badge bg-danger-dark">'.$record->status.'</span></td>';
            }  elseif ($record->status === 'Inactive') {
                $status = '<td><span class="badge badge-warning">'.$record->status.'</span></td>';
            } else {
                $status = '<td><span class="badge badge-secondary">'.$record->status.'</span></td>';
            }

            $modify = '
                <td class="text-end"> 
                    <div class="actions">
                        <a class="btn btn-sm bg-danger-light verify user_id" data-bs-toggle="modal" data-user_id="'.$record->user_id.'" data-bs-target="#verify">
                            <i class="fas fa-user-shield"></i>
                        </a>
                        <a href="'.url('view/user/edit/'.$record->user_id).'" class="btn btn-sm bg-danger-light">
                            <i class="feather-edit"></i>
                        </a>
                        <a class="btn btn-sm bg-danger-light delete user_id" data-bs-toggle="modal" data-user_id="'.$record->user_id.'" data-bs-target="#delete">
                            <i class="fe fe-trash-2"></i>
                        </a>
                    </div>
                </td>
            ';
           
            $data_arr [] = [
                "user_id"      => $record->user_id,
                "avatar"       => $avatar,
                "name"         => $record->name,
                "branch_name"  => $record->branch_name,
                "branch_type"  => $record->branch_type,
                "email"        => $record->email,
                "position"     => $record->position,
                "phone_number" => $record->phone_number,
                "join_date"    => $record->join_date,
                "status"       => $status,
                "modify"       => $modify,
            ];
        }

        $response = [
            "draw"                 => intval($draw),
            "iTotalRecords"        => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordsWithFilter,
            "aaData"               => $data_arr
        ];
        return response()->json($response);
    }




    public function unverifyUserlist()
    {
        $unverifyuser = User::where('is_verified', 0)->get();
        return view('usermanagement.unverifyuserlist', compact('unverifyuser'));
    }




    public function unverifyUseredit($id)
    {
        $users = User::where('is_verified', 0)->findOrFail($id);

        $roles  = Role::all();
        $types = DB::table('user_types')->get();
        $branches = Branch::select('id', 'type', 'name')->get();

        return view('usermanagement.unverifyuseredit', compact('users', 'roles', 'types', 'branches'));
    }



    // public function unverifyUserupdate(Request $request, $id)
    // {

    //     $user = User::find($id);

    //     if (!$user) {
    //         Toastr::error('User not found', 'Error');
    //         return redirect()->route('unverify.user.list');
    //     }

    //     $branch = Branch::find($request->branch_id);
    //     if (!$branch) {
    //         Toastr::error('Branch not found', 'Error');
    //         return redirect()->back();
    //     }

    //     if ($request->hasFile('avatar')) { 
    //         $file = $request->file('avatar');
    //         $filename = time() . '_' . $file->getClientOriginalName();
    //         $file->storeAs(public_path('/images/'), $filename);
            
    //         if ($user->avatar) {
    //             Storage::delete(public_path('/images/'), $filename);
    //         }
    //         $user->avatar = $filename;
    //     }


    //     $update = [
    //         'user_id'       => $request->user_id,
    //         'branch_id'     => $branch->id,
    //         'branch_name'   => $branch->name,
    //         'role_name' => $request->roles ? implode(',', $request->roles) : null,
    //         'branch_type'   => $branch->type,
    //         'name'          => $request->name,
    //         'email'         => $request->email,
    //         'position'      => $request->position,
    //         'phone_number'  => $request->phone_number,
    //         'date_of_birth' => $request->date_of_birth,
    //         'department'    => $request->department,
    //         'status'        => $request->status,
    //         'navigate_to'   => $request->navigate_to,
    //         'blood_group'   => $request->blood_group,
    //         'address'       => $request->address,
    //         'avatar'        => $user->avatar, 
    //     ];

    //     $user->update($update);

    //     Toastr::success('User updated successfully', 'Success');
    //     return redirect()->route('unverify.user.list');
    // }


    


    public function unverifyUserupdate(Request $request, $id)
    {
        $user = User::find($id);
    
        if (!$user) {
            Toastr::error('User not found', 'Error');
            return redirect()->route('unverify.user.list');
        }
    
        $branch = Branch::find($request->branch_id);
        if (!$branch) {
            Toastr::error('Branch not found', 'Error');
            return redirect()->back();
        }
    
        if ($request->hasFile('avatar')) { 
            $file = $request->file('avatar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs(public_path('/images/'), $filename);
            
            if ($user->avatar) {
                Storage::delete(public_path('/images/') . $user->avatar); 
            }
            $user->avatar = $filename;
        }
    
        $update = [
            'user_id'       => $request->user_id,
            'branch_id'     => $branch->id,
            'branch_name'   => $branch->name,
            'role_name'     => $request->roles ? implode(',', $request->roles) : null,
            'branch_type'   => $branch->type,
            'name'          => $request->name,
            'email'         => $request->email,
            'position'      => $request->position,
            'phone_number'  => $request->phone_number,
            'date_of_birth' => $request->date_of_birth,
            'department'    => $request->department,
            'status'        => $request->status,
            'navigate_to'   => $request->navigate_to,
            'blood_group'   => $request->blood_group,
            'address'       => $request->address,
            'avatar'        => $user->avatar, 
        ];
    
        $user->update($update);
    
        $user->roles()->detach(); 
        if ($request->roles) {
            $user->assignRole($request->roles); 
        }
    
        Toastr::success('User updated successfully', 'Success');
        return redirect()->route('unverify.user.list');
    }
    




    public function unverifyUserdelete($id)
    {
        $users = User::where('is_verified', 0)->findOrFail($id);
        $users->delete();

        Toastr::success('Unverify User deleted successfully ','Success');
        return redirect()->route('unverify.user.list');
    }


    public function verifyUser(Request $request)
    {
        $user = User::find($request->user_id);

        if ($user) {
            if (empty($user->role_name)) {
                return response()->json(['success' => false, 'message' => 'Assign the role to this user, then verify it.']);
            }

            $user->is_verified = 1;
            $user->save();
            
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }





}
