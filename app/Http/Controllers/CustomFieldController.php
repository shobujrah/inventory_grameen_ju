<?php

namespace App\Http\Controllers;

use App\Models\CustomField;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;

class CustomFieldController extends Controller
{
    public function __construct()
    {

    }

    public function index()
    {
        $custom_fields = CustomField::get();

        return view('customFields.index', compact('custom_fields'));
    }


    public function create()
    {
        $types   = CustomField::$fieldTypes;
        $modules = CustomField::$modules;

        return view('customFields.create', compact('types', 'modules'));
    }


    public function store(Request $request)
    {
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required|max:40',
                                   'type' => 'required',
                                   'module' => 'required',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('custom-field.index')->with('error', $messages->first());
            }

            $custom_field             = new CustomField();
            $custom_field->name       = $request->name;
            $custom_field->type       = $request->type;
            $custom_field->module     = $request->module;
            $custom_field->created_by = \Auth::user()->id;
            $custom_field->save();

            Toastr::success('Custom Field successfully created!','Success');
            return redirect()->route('custom-field.index');

    }


    public function show(CustomField $customField)
    {
        return redirect()->route('custom-field.index');
    }

    public function edit(CustomField $customField)
    {
        $types   = CustomField::$fieldTypes;
        $modules = CustomField::$modules;

        return view('customFields.edit', compact('customField', 'types', 'modules'));
    }


    public function update(Request $request, CustomField $customField)
    {

        $validator = \Validator::make(
            $request->all(), [
                                'name' => 'required|max:40',
                            ]
        );

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->route('custom-field.index')->with('error', $messages->first());
        }

        $customField->name = $request->name;
        $customField->save();

        Toastr::success('Custom Field successfully updated!','Success');
        return redirect()->route('custom-field.index');

    }


    public function destroy(CustomField $customField)
    {
        $customField->delete();

        Toastr::success('Custom Field successfully deleted!','Success');
        return redirect()->route('custom-field.index');
    }
}
