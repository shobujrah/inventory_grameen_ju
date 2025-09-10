<?php

namespace App\Http\Controllers\Auth;

use DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Branch;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function register()
    {
        // $role = DB::table('role_type_users')->get(); // getting all the role
        // return view('auth.register',compact('role')); // returning with the role
        $branches = Branch::pluck('name', 'id'); 
        return view('auth.register', compact('branches'));
    }


    // public function storeUser(Request $request)
    // {

    //     dd($request->all());

    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|string|email|max:255|unique:users',
    //         // 'role_name' => 'required|string|max:255',
    //         'password' => 'required|string|min:8|confirmed',
    //         'password_confirmation' => 'required',
    //         'phone_number' => 'required|string|max:255',
    //         'date_of_birth' => 'required|string|max:255',
    //         'branch_id' => 'required',
    //     ]);

    //     $branch = Branch::find($request->branch_id);

    //     $branch_name = $branch ? $branch->name : null;
    //     $branch_type = $branch ? $branch->type : null;


    //     # currently using this exiting part
    //     $dt       = Carbon::now();
    //     $todayDate = $dt->toDayDateTimeString();
    //     # custom part
    //     // $currentDateTime = Carbon::now();
    //     // $todayDate = $currentDateTime->format('h:i:s A - jS F, Y');

    //     // $mytime = Carbon\Carbon::now();
    //     // echo $mytime->toDateTimeString();
    //     $status = 'Active';
    //     $is_verified = 0; // User is not verified by default
    //     User::create([
    //         'name'      => $request->name,
    //         'avatar'    => $request->image,
    //         'email'     => $request->email,
    //         'join_date' => $todayDate,

    //         'branch_id' => $request->branch_id,
    //         'branch_name' => $branch_name,
    //         'branch_type' => $branch_type,

    //         // 'role_name' => $request->role_name,
    //         'phone_number' => $request->phone_number,
    //         'date_of_birth' => $request->date_of_birth,
    //         'position' => $request->designation,
    //         // 'navigate_to' => $request->navigate_to,
    //         // 'blood_group' => $request->blood_group,
    //         'address' => $request->address,
    //         'is_verified' => $is_verified,
    //         'status' => $status,
    //         'password'  => Hash::make($request->password),
    //     ]);
    //     Toastr::success('Account created successfully ','Success');
    //     return redirect()->route('login');
    // }




    public function storeUser(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'designation' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required',
            'phone_number' => 'required|regex:/^01[3-9][0-9]{8}$/',
            'date_of_birth' => 'required|string|max:255',
            'branch_id' => 'required',
        ]);

        $branch = Branch::find($request->branch_id);

        $branch_name = $branch ? $branch->name : null;
        $branch_type = $branch ? $branch->type : null;

        $todayDate = Carbon::today()->toDateString(); 

        $status = 'Active';
        $is_verified = 0; 
        User::create([
            'name'      => $request->name,
            'avatar'    => $request->image,
            'email'     => $request->email,
            'join_date' => $todayDate,
            'branch_id' => $request->branch_id,
            'branch_name' => $branch_name,
            'branch_type' => $branch_type,
            'phone_number' => $request->phone_number,
            'date_of_birth' => $request->date_of_birth,
            'position' => $request->designation,
            'address' => $request->address,
            'is_verified' => $is_verified,
            'status' => $status,
            'password'  => Hash::make($request->password),
        ]);
        Toastr::success('Account created successfully ','Success');
        return redirect()->route('login');
    }










}
