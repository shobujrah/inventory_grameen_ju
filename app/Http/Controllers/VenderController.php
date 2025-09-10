<?php

namespace App\Http\Controllers;

use App\Exports\VenderExport;
use App\Imports\VenderImport;
use App\Models\CustomField;
use App\Models\Transaction;
use App\Models\Utility;
use App\Models\Vender;
use Auth;
use App\Models\User;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;
use Brian2694\Toastr\Facades\Toastr;

class VenderController extends Controller
{

    public function dashboard()
    {
        $data['billChartData'] = \Auth::user()->billChartData();

        return view('vender.dashboard', $data);
    }

    public function index()
    {
        $venders = Vender::get();

        return view('vender.index', compact('venders'));
    }


    public function create()
    {
        $customFields = CustomField::where('module', '=', 'vendor')->get();
        return view('vender.create', compact('customFields'));
    }


    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'contact' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/',
            'email' => 'required|email|unique:venders',
        ];

        $validator = \Validator::make($request->all(), $rules);
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();
            return redirect()->route('vender.index')->with('error', $messages->first());
        }
        $objVendor    = \Auth::user();
        $creator      = User::find($objVendor->id);
        $total_vendor = Utility::countVenders();

        $default_language = 'en';

        $vender                   = new Vender();
        $vender->vender_id        = $this->venderNumber();
        $vender->name             = $request->name;
        $vender->contact          = $request->contact;
        $vender->email            = $request->email;
        $vender->tax_number      =$request->tax_number;
        $vender->created_by       = \Auth::user()->id;
        $vender->billing_name     = $request->billing_name;
        $vender->billing_country  = $request->billing_country;
        $vender->billing_state    = $request->billing_state;
        $vender->billing_city     = $request->billing_city;
        $vender->billing_phone    = $request->billing_phone;
        $vender->billing_zip      = $request->billing_zip;
        $vender->billing_address  = $request->billing_address;
        $vender->shipping_name    = $request->shipping_name;
        $vender->shipping_country = $request->shipping_country;
        $vender->shipping_state   = $request->shipping_state;
        $vender->shipping_city    = $request->shipping_city;
        $vender->shipping_phone   = $request->shipping_phone;
        $vender->shipping_zip     = $request->shipping_zip;
        $vender->shipping_address = $request->shipping_address;
        $vender->lang             = 'en';
        $vender->save();
        CustomField::saveData($vender, $request->customField);

        // $role_r = Role::where('name', '=', 'vender')->firstOrFail();
        // $vender->assignRole($role_r); //Assigning role to user
        // $vender->type     = 'Vender';

        Toastr::success('Vendor successfully created.','Success');
        return redirect()->route('vender.index');

    }


    public function show($ids)
    {
        try {
            $id       = Crypt::decrypt($ids);
        } catch (\Throwable $th) {
            Toastr::error('Vendor Not Found.','Error');
            return redirect()->back();
        }
        $id     = \Crypt::decrypt($ids);
        $vendor = Vender::find($id);

        return view('vender.show', compact('vendor'));
    }


    public function edit($id)
    {
        $vender              = Vender::find($id);
        $vender->customField = CustomField::getData($vender, 'vendor');

        $customFields = CustomField::where('module', '=', 'vendor')->get();

        return view('vender.edit', compact('vender', 'customFields'));
    }


    public function update(Request $request, Vender $vender)
    {
            $rules = [
                'name' => 'required',
                'contact' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/',
            ];


            $validator = \Validator::make($request->all(), $rules);

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('vender.index')->with('error', $messages->first());
            }

            $vender->name             = $request->name;
            $vender->contact          = $request->contact;
            $vender->tax_number      = $request->tax_number;
            $vender->created_by       = \Auth::user()->id;
            $vender->billing_name     = $request->billing_name;
            $vender->billing_country  = $request->billing_country;
            $vender->billing_state    = $request->billing_state;
            $vender->billing_city     = $request->billing_city;
            $vender->billing_phone    = $request->billing_phone;
            $vender->billing_zip      = $request->billing_zip;
            $vender->billing_address  = $request->billing_address;
            $vender->shipping_name    = $request->shipping_name;
            $vender->shipping_country = $request->shipping_country;
            $vender->shipping_state   = $request->shipping_state;
            $vender->shipping_city    = $request->shipping_city;
            $vender->shipping_phone   = $request->shipping_phone;
            $vender->shipping_zip     = $request->shipping_zip;
            $vender->shipping_address = $request->shipping_address;
            $vender->save();
            CustomField::saveData($vender, $request->customField);

            Toastr::success('Vendor successfully updated.','Success');
            return redirect()->route('vender.index');
    }


    public function destroy(Vender $vender)
    {
        $vender->delete();

        Toastr::success('Vendor successfully deleted.','Success');
        return redirect()->route('vender.index');

    }

    function venderNumber()
    {
        $latest = Vender::latest()->first();
        if(!$latest)
        {
            return 1;
        }

        return $latest->vender_id + 1;
    }

    public function venderLogout(Request $request)
    {
        \Auth::guard('vender')->logout();

        $request->session()->invalidate();

        return redirect()->route('vender.login');
    }

    public function payment(Request $request)
    {
        $category = [
            'Bill' => 'Bill',
            'Deposit' => 'Deposit',
            'Sales' => 'Sales',
        ];

        $query = Transaction::where('user_id', \Auth::user()->id)->where('user_type', 'Vender')->where('type', 'Payment');
        if(!empty($request->date))
        {
            $date_range = explode(' - ', $request->date);
            $query->whereBetween('date', $date_range);
        }

        if(!empty($request->category))
        {
            $query->where('category', '=', $request->category);
        }
        $payments = $query->get();


        return view('vender.payment', compact('payments', 'category'));

    }

    public function transaction(Request $request)
    {
            $category = [
                'Bill' => 'Bill',
                'Deposit' => 'Deposit',
                'Sales' => 'Sales',
            ];

            $query = Transaction::where('user_id', \Auth::user()->id)->where('user_type', 'Vender');

            if(!empty($request->date))
            {
                $date_range = explode(' - ', $request->date);
                $query->whereBetween('date', $date_range);
            }

            if(!empty($request->category))
            {
                $query->where('category', '=', $request->category);
            }
            $transactions = $query->get();

            return view('vender.transaction', compact('transactions', 'category'));

    }

    public function profile()
    {
        $userDetail              = \Auth::user();
        $userDetail->customField = CustomField::getData($userDetail, 'vendor');
        $customFields            = CustomField::where('created_by', '=', \Auth::user()->creatorId())->where('module', '=', 'vendor')->get();

        return view('vender.profile', compact('userDetail', 'customFields'));
    }

    public function editprofile(Request $request)
    {

        $userDetail = \Auth::user();
        $user       = Vender::findOrFail($userDetail['id']);
        $this->validate(
            $request, [
                        'name' => 'required|max:120',
                        'contact' => 'required',
                        'email' => 'required|email|unique:users,email,' . $userDetail['id'],
                    ]
        );
        if($request->hasFile('profile'))
        {
            $filenameWithExt = $request->file('profile')->getClientOriginalName();
            $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension       = $request->file('profile')->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;

            $dir        = storage_path('app/public/uploads/avatar/');
            $image_path = $dir . $userDetail['avatar'];

            if(File::exists($image_path))
            {
                File::delete($image_path);
            }

            if(!file_exists($dir))
            {
                mkdir($dir, 0777, true);
            }

            $path = $request->file('profile')->storeAs('app/public/uploads/avatar/', $fileNameToStore);

        }

        if(!empty($request->profile))
        {
            $user['avatar'] = $fileNameToStore;
        }
        $user['name']    = $request['name'];
        $user['email']   = $request['email'];
        $user['contact'] = $request['contact'];
        $user->save();
        CustomField::saveData($user, $request->customField);

        Toastr::success('Profile successfully updated.','Success');
        return redirect()->back();
    }

    public function editBilling(Request $request)
    {

        $userDetail = \Auth::user();
        $user       = Vender::findOrFail($userDetail['id']);
        $this->validate(
            $request, [
                        'billing_name' => 'required',
                        'billing_country' => 'required',
                        'billing_state' => 'required',
                        'billing_city' => 'required',
                        'billing_phone' => 'required',
                        'billing_zip' => 'required',
                        'billing_address' => 'required',
                    ]
        );
        $input = $request->all();
        $user->fill($input)->save();

        Toastr::success('Profile successfully updated.','Success');
        return redirect()->back();
    }

    public function editShipping(Request $request)
    {
        $userDetail = \Auth::user();
        $user       = Vender::findOrFail($userDetail['id']);
        $this->validate(
            $request, [
                        'shipping_name' => 'required',
                        'shipping_country' => 'required',
                        'shipping_state' => 'required',
                        'shipping_city' => 'required',
                        'shipping_phone' => 'required',
                        'shipping_zip' => 'required',
                        'shipping_address' => 'required',
                    ]
        );
        $input = $request->all();
        $user->fill($input)->save();

        Toastr::success('Profile successfully updated.','Success');
        return redirect()->back();
    }


    public function changeLanquage($lang)
    {


        $user       = Auth::user();
        $user->lang = $lang;
        $user->save();

        Toastr::success('Language successfully change.','Success');
        return redirect()->back();

    }

    public function export()
    {
        $name = 'vendor_' . date('Y-m-d i:h:s');
        $data = Excel::download(new VenderExport(), $name . '.xlsx');

        return $data;
    }

    public function importFile()
    {
        return view('vender.import');
    }

    public function import(Request $request)
    {

        $rules = [
            'file' => 'required|mimes:csv,txt',
        ];

        $validator = \Validator::make($request->all(), $rules);

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $vendors = (new VenderImport())->toArray(request()->file('file'))[0];

        $totalCustomer = count($vendors) - 1;
        $errorArray    = [];
        for($i = 1; $i <= count($vendors) - 1; $i++)
        {
            $vendor = $vendors[$i];

            $vendorByEmail = Vender::where('email', $vendor[2])->first();

            if(!empty($vendorByEmail))
            {
                $vendorData = $vendorByEmail;
            }
            else
            {
                $vendorData            = new Vender();
                $vendorData->vender_id = $this->venderNumber();
            }

            $vendorData->vender_id          =$vendor[0];
            $vendorData->name               = $vendor[1];
            $vendorData->email              = $vendor[2];
            $vendorData->contact            = $vendor[3];
            $vendorData->avatar             = $vendor[4];
            $vendorData->billing_name       = $vendor[5];
            $vendorData->billing_country    = $vendor[6];
            $vendorData->billing_state      = $vendor[7];
            $vendorData->billing_city       = $vendor[8];
            $vendorData->billing_phone      = $vendor[9];
            $vendorData->billing_zip        = $vendor[10];
            $vendorData->billing_address    = $vendor[11];
            $vendorData->shipping_name      = $vendor[12];
            $vendorData->shipping_country   = $vendor[13];
            $vendorData->shipping_state     = $vendor[14];
            $vendorData->shipping_city      = $vendor[15];
            $vendorData->shipping_phone     = $vendor[16];
            $vendorData->shipping_zip       = $vendor[17];
            $vendorData->shipping_address   = $vendor[18];
            $vendorData->created_by         = \Auth::user()->creatorId();

            if(empty($vendorData))
            {
                $errorArray[] = $vendorData;
            }
            else
            {
                $vendorData->save();
            }
        }

        $errorRecord = [];
        if(empty($errorArray))
        {
            $data['status'] = 'success';
            $data['msg']    = __('Record successfully imported');
        }
        else
        {
            $data['status'] = 'error';
            $data['msg']    = count($errorArray) . ' ' . __('Record imported fail out of' . ' ' . $totalCustomer . ' ' . 'record');


            foreach($errorArray as $errorData)
            {

                $errorRecord[] = implode(',', $errorData);

            }

            \Session::put('errorArray', $errorRecord);
        }

        Toastr::success($data['msg'],'Success');
        return redirect()->back();
    }
}
