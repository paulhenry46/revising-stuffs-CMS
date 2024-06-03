<?php

namespace App\Http\Controllers;

use App\Models\Curriculum;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SchoolController extends Controller
{
       /**
     * Display a listing of the resource.
     */


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $school = New School();
        $curricula = Curriculum::all();
        $school->id = 0;
        $create = 1;
        return view('schools.edit', compact(['create', 'curricula', "school"]));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $school = new School();
        $school->name = $request->name;
        $school->description = $request->description;
        $school->slug = Str::slug($request->name, '-');
        $school->save();
        $school->curricula()->attach($request->curricula);

        return redirect()->route('settings')->with('message', __('The curriculum has been created'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(School $school)
    { 
        $curricula = Curriculum::all();
        $create = 0;
        return view('schools.edit', compact(['create', 'curricula', "school"]));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, School $school)
    {
        $school->name = $request->name;
        $school->description = $request->description;
        $school->slug = Str::slug($request->name, '-');
        $school->save();
        $school->curricula()->attach($request->curricula);

        return redirect()->route('settings')->with('message', __('The school has been updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Curriculum $curriculum)
    {
        foreach($curriculum->levels as $level){
           
            $level->curriculum_id = null;
            $level->save();
        }

        $curriculum->delete();
            return redirect()->route('settings')->with('message', __('The school has been deleted.'));
    }
}
