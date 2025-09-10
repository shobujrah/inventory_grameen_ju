<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Requisition;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;

class ProjectController extends Controller
{

    public function index()
    {
        $projects = Project::orderBy('id', 'desc')->get();

        return view('project.index', compact('projects'));
    }

    public function create()
    {
        return view('project.create');
    }

    public function store(Request $request)
    {
        $validator = \Validator::make(
            $request->all(), [
                'name' => 'required',
            ]
        );

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->route('project.index')->with('error', $messages->first());
        }

        $project                = new Project();
        $project->name          = $request->name;
        $project->created_by    = \Auth::user()->id;
        $project->save();

        Toastr::success('Project Created!','Success');

        return redirect()->route('project.index');

    }

    public function show()
    {
        return redirect()->route('project.index');
    }


    public function edit(Project $project)
    {
        return view('project.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $validator = \Validator::make(
            $request->all(), [
                'name' => 'required',
            ]
        );

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->route('project.index')->with('error', $messages->first());
        }

        $project->name          = $request->name;
        $project->created_by    = \Auth::user()->id;
        $project->save();

        Toastr::success('Project Updated!','Success');
        return redirect()->route('project.index');

    }


    // public function destroy(Project $project)
    // {
    //     $project->delete();
    //     Toastr::success('Project successfully deleted.','Success');
    //     return redirect()->route('project.index');
    // }



    public function destroy(Project $project)
    {
        $isUsedInRequisition = Requisition::where('project_id', $project->id)->exists();

        if ($isUsedInRequisition) {
            Toastr::error("This project can't be deleted because it is used in requisition.", 'Error');
            return redirect()->route('project.index');
        }

        $project->delete();
        Toastr::success('Project successfully deleted.', 'Success');
        return redirect()->route('project.index');
    }





}
