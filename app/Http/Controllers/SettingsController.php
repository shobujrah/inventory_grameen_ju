<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::first();
        return view('setting.settings', compact('settings'));
    }

   
    // public function store(Request $request)
    // {

    //     $validatedData = $request->validate([
    //         'website_name' => 'required|string|max:255',
    //         'logo' => 'required|mimes:jpeg,png,jpg|max:2048', 
    //     ]);

    //     if ($request->hasFile('logo')) {
    //         $logo = $request->file('logo');
    //         $logoName = time() . '.' . $logo->getClientOriginalExtension();
    //         $logo->move(public_path('/logo'), $logoName);
    //     }

    //     Setting::create([
    //         'website_name' => $validatedData['website_name'],
    //         'logo' => $logoName,
    //     ]);

    //     return redirect()->route('setting.page')->with('success', 'Settings updated successfully!');
    // }


    // public function store(Request $request)
    // {
    //     $validatedData = $request->validate([
    //         'website_name' => 'required|string|max:255',
    //         'logo' => 'required|mimes:jpeg,png,jpg|max:2048', 
    //     ]);

    //     $settings = Setting::first();

    //     if ($request->hasFile('logo')) {
    //         $logo = $request->file('logo');
    //         $logoName = time() . '.' . $logo->getClientOriginalExtension();
    //         $logo->move(public_path('/logo'), $logoName);

    //         if ($settings && $settings->logo) {
    //             $oldLogoPath = public_path('/logo/' . $settings->logo);
    //             if (file_exists($oldLogoPath)) {
    //                 unlink($oldLogoPath);
    //             }
    //         }
    //     } else {
    //         $logoName = $settings->logo ?? null;
    //     }

    //     if ($settings) {
    //         $settings->update([
    //             'website_name' => $validatedData['website_name'],
    //             'logo' => $logoName,
    //         ]);
    //     } else {
    //         Setting::create([
    //             'website_name' => $validatedData['website_name'],
    //             'logo' => $logoName,
    //         ]);
    //     }

    //     return redirect()->route('setting.page')->with('success', 'Settings updated successfully!');
    // }



    public function store(Request $request)
    {
        $settings = Setting::first();
    
        $validatedData = $request->validate([
            'website_name' => 'required|string|max:255',
            'logo' => ($settings && $settings->logo) ? 'nullable|mimes:jpeg,png,jpg|max:2048' : 'required|mimes:jpeg,png,jpg|max:2048',
        ]);
    
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoName = time() . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('/logo'), $logoName);
    
            if ($settings && $settings->logo) {
                $oldLogoPath = public_path('/logo/' . $settings->logo);
                if (file_exists($oldLogoPath)) {
                    unlink($oldLogoPath);
                }
            }
        } else {
            $logoName = $settings->logo ?? null;
        }
    
        if ($settings) {
            $settings->update([
                'website_name' => $validatedData['website_name'],
                'logo' => $logoName,
            ]);
        } else {
            Setting::create([
                'website_name' => $validatedData['website_name'],
                'logo' => $logoName,
            ]);
        }
    
        return redirect()->route('setting.page')->with('success', 'Settings updated successfully!');
    }
    










}
