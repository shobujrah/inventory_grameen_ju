<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;

class GoalController extends Controller
{

    public function index()
    {
        $golas = Goal::get();

        return view('goal.index', compact('golas'));
    }

    public function create()
    {
        $types = Goal::$goalType;

        return view('goal.create', compact('types'));
    }


    public function store(Request $request)
    {
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required',
                                   'type' => 'required',
                                   'from' => 'required',
                                   'to' => 'required',
                                   'amount' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $goal             = new Goal();
            $goal->name       = $request->name;
            $goal->type       = $request->type;
            $goal->from       = $request->from;
            $goal->to         = $request->to;
            $goal->amount     = $request->amount;
            $goal->is_display = isset($request->is_display) ? 1 : 0;
            $goal->created_by = \Auth::user()->id;
            $goal->save();

            Toastr::success('Goal successfully created.','Success');
            return redirect()->route('goal.index');

    }


    public function show(Goal $goal)
    {
        //
    }


    public function edit(Goal $goal)
    {
        $types = Goal::$goalType;

        return view('goal.edit', compact('types', 'goal'));

    }


    public function update(Request $request, Goal $goal)
    {
        $validator = \Validator::make(
            $request->all(), [
                                'name' => 'required',
                                'type' => 'required',
                                'from' => 'required',
                                'to' => 'required',
                                'amount' => 'required',
                            ]
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $goal->name       = $request->name;
        $goal->type       = $request->type;
        $goal->from       = $request->from;
        $goal->to         = $request->to;
        $goal->amount     = $request->amount;
        $goal->is_display = isset($request->is_display) ? 1 : 0;
        $goal->save();

        Toastr::success('Goal successfully updated.','Success');
        return redirect()->route('goal.index');

    }


    public function destroy(Goal $goal)
    {
        $goal->delete();

        Toastr::success('Goal successfully deleted.','Success');
        return redirect()->route('goal.index');
    }
}
