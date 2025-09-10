<?php

namespace App\Http\Controllers;

use App\Exports\CustomerExport;
use App\Imports\CustomerImport;
use App\Models\Customer;
use App\Models\CustomField;
use App\Models\Transaction;
use Auth;
use App\Models\User;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Brian2694\Toastr\Facades\Toastr;

class CustomerController extends Controller
{

    public function dashboard()
    {
        $data['invoiceChartData'] = \Auth::user()->invoiceChartData();

        return view('customer.dashboard', $data);
    }

    public function index()
    {
        $customers = Customer::get();
        return view('customer.index', compact('customers'));
    }

    public function create()
    {
        $customFields = CustomField::where('module', '=', 'customer')->get();

        return view('customer.create', compact('customFields'));
    }


    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'contact' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/',
            'email' => 'required|email|unique:customers',
        ];

        $validator = \Validator::make($request->all(), $rules);

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();
            return redirect()->route('customer.index')->with('error', $messages->first());
        }

        $objCustomer    = \Auth::user();

        $default_language          = 'en';

        $customer                  = new Customer();
        $customer->customer_id     = $this->customerNumber();
        $customer->name            = $request->name;
        $customer->contact         = $request->contact;
        $customer->email           = $request->email;
        $customer->tax_number      =$request->tax_number;
        $customer->created_by      = \Auth::user()->id;
        $customer->billing_name    = $request->billing_name;
        $customer->billing_country = $request->billing_country;
        $customer->billing_state   = $request->billing_state;
        $customer->billing_city    = $request->billing_city;
        $customer->billing_phone   = $request->billing_phone;
        $customer->billing_zip     = $request->billing_zip;
        $customer->billing_address = $request->billing_address;

        $customer->shipping_name    = $request->shipping_name;
        $customer->shipping_country = $request->shipping_country;
        $customer->shipping_state   = $request->shipping_state;
        $customer->shipping_city    = $request->shipping_city;
        $customer->shipping_phone   = $request->shipping_phone;
        $customer->shipping_zip     = $request->shipping_zip;
        $customer->shipping_address = $request->shipping_address;

        $customer->lang = !empty($default_language) ? $default_language : '';

        $customer->save();
        CustomField::saveData($customer, $request->customField);

        Toastr::success('Customer successfully created.','Success');
        return redirect()->route('customer.index');

    }


    public function show($ids)
    {
        $id       = \Crypt::decrypt($ids);
        $customer = Customer::find($id);

        return view('customer.show', compact('customer'));
    }


    public function edit($id)
    {
        $customer              = Customer::find($id);
        $customer->customField = CustomField::getData($customer, 'customer');

        $customFields = CustomField::where('module', '=', 'customer')->get();

        return view('customer.edit', compact('customer', 'customFields'));
    }


    public function update(Request $request, Customer $customer)
    {

        $rules = [
            'name' => 'required',
            'contact' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/',
        ];

        $validator = \Validator::make($request->all(), $rules);
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->route('customer.index')->with('error', $messages->first());
        }

        $customer->name             = $request->name;
        $customer->contact          = $request->contact;
        $customer->email           = $request->email;
        $customer->tax_number      =$request->tax_number;
        $customer->created_by       = \Auth::user()->id;
        $customer->billing_name     = $request->billing_name;
        $customer->billing_country  = $request->billing_country;
        $customer->billing_state    = $request->billing_state;
        $customer->billing_city     = $request->billing_city;
        $customer->billing_phone    = $request->billing_phone;
        $customer->billing_zip      = $request->billing_zip;
        $customer->billing_address  = $request->billing_address;
        $customer->shipping_name    = $request->shipping_name;
        $customer->shipping_country = $request->shipping_country;
        $customer->shipping_state   = $request->shipping_state;
        $customer->shipping_city    = $request->shipping_city;
        $customer->shipping_phone   = $request->shipping_phone;
        $customer->shipping_zip     = $request->shipping_zip;
        $customer->shipping_address = $request->shipping_address;
        $customer->save();

        CustomField::saveData($customer, $request->customField);

        Toastr::success('Customer successfully updated.','Success');
        return redirect()->route('customer.index');
    }


    public function destroy(Customer $customer)
    {
        $customer->delete();
        Toastr::success('Customer successfully deleted.','Success');
        return redirect()->route('customer.index');
    }

    function customerNumber()
    {
        $latest = Customer::latest()->first();

        if(!$latest)
        {
            return 1;
        }

        return $latest->customer_id + 1;
    }

    public function customerLogout(Request $request)
    {
        \Auth::guard('customer')->logout();

        $request->session()->invalidate();

        return redirect()->route('customer.login');
    }

    public function payment(Request $request)
    {

        $category = [
            'Invoice' => 'Invoice',
            'Deposit' => 'Deposit',
            'Sales' => 'Sales',
        ];

        $query = Transaction::where('user_id', \Auth::user()->id)->where('user_type', 'Customer')->where('type', 'Payment');
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

        return view('customer.payment', compact('payments', 'category'));
    }

    public function transaction(Request $request)
    {
        $category = [
            'Invoice' => 'Invoice',
            'Deposit' => 'Deposit',
            'Sales' => 'Sales',
        ];

        $query = Transaction::where('user_id', \Auth::user()->id)->where('user_type', 'Customer');

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

        return view('customer.transaction', compact('transactions', 'category'));
    }

    public function profile()
    {
        $userDetail              = \Auth::user();
        $userDetail->customField = CustomField::getData($userDetail, 'customer');
        $customFields            = CustomField::where('module', '=', 'customer')->get();

        return view('customer.profile', compact('userDetail', 'customFields'));
    }

    public function editprofile(Request $request)
    {
        $userDetail = \Auth::user();
        $user       = Customer::findOrFail($userDetail['id']);

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

            $dir        = storage_path('uploads/avatar/');
            $image_path = $dir . $userDetail['avatar'];

            if(File::exists($image_path))
            {
                File::delete($image_path);
            }

            if(!file_exists($dir))
            {
                mkdir($dir, 0777, true);
            }

            $path = $request->file('profile')->storeAs('uploads/avatar/', $fileNameToStore);

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
        $user       = Customer::findOrFail($userDetail['id']);
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
        $user       = Customer::findOrFail($userDetail['id']);
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

        Toastr::success('Language Change Successfully!','Success');
        return redirect()->back();

    }


    public function export()
    {
        $name = 'customer_' . date('Y-m-d i:h:s');
        $data = Excel::download(new CustomerExport(), $name . '.xlsx'); ob_end_clean();

        return $data;
    }

    public function importFile()
    {
        return view('customer.import');
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

        $customers = (new CustomerImport())->toArray(request()->file('file'))[0];

        $totalCustomer = count($customers) - 1;
        $errorArray    = [];
        for($i = 1; $i <= count($customers) - 1; $i++)
        {
            $customer = $customers[$i];

            $customerByEmail = Customer::where('email', $customer[2])->first();
            if(!empty($customerByEmail))
            {
                $customerData = $customerByEmail;
            }
            else
            {
                $customerData = new Customer();
                $customerData->customer_id      = $this->customerNumber();
            }


            $customerData->customer_id             = $customer[0];
            $customerData->name             = $customer[1];
            $customerData->email            = $customer[2];
            $customerData->contact          = $customer[3];
            $customerData->is_active        = 1;
            $customerData->billing_name     = $customer[4];
            $customerData->billing_country  = $customer[5];
            $customerData->billing_state    = $customer[6];
            $customerData->billing_city     = $customer[7];
            $customerData->billing_phone    = $customer[8];
            $customerData->billing_zip      = $customer[9];
            $customerData->billing_address  = $customer[10];
            $customerData->shipping_name    = $customer[11];
            $customerData->shipping_country = $customer[12];
            $customerData->shipping_state   = $customer[13];
            $customerData->shipping_city    = $customer[14];
            $customerData->shipping_phone   = $customer[15];
            $customerData->shipping_zip     = $customer[16];
            $customerData->shipping_address = $customer[17];
            $customerData->balance          = 0;
            $customerData->created_by       = \Auth::user()->id;

            if(empty($customerData))
            {
                $errorArray[] = $customerData;
            }
            else
            {
                $customerData->save();
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

        Toastr::success($data['msg'],$data['status']);
        return redirect()->back();
    }


}
