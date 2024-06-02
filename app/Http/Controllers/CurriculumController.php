<?php

namespace App\Http\Controllers;

use App\Models\Curriculum;
use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class CurriculumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $curricula = Curriculum::all();
        return view('curricula.index', compact(['curricula']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $curriculum = New Curriculum;
        $levels = Level::all();
        $curriculum->id = 0;
        $create = 1;
        return view('curricula.edit', compact(['create', 'curriculum', "levels"]));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $curriculum = new Curriculum();
        $curriculum->name = $request->name;
        $curriculum->description = $request->description;
        $curriculum->slug = Str::slug($request->name, '-');
        $curriculum->save();
        foreach($request->levels as $id){
            $level = Level::findOrFail($id);
            $level->curriculum_id = $curriculum->id;
            $level->save();
        }

        return redirect()->route('curricula.index')->with('message', __('The curriculum has been created'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Curriculum $curriculum)
    { 
        $levels = Level::all();
        $create = 0;
        return view('curricula.edit', compact(['create', 'curriculum', "levels"]));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Curriculum $curriculum)
    {
        $curriculum->name = $request->name;
        $curriculum->description = $request->description;
        $curriculum->slug = Str::slug($request->name, '-');
        $curriculum->save();
        foreach($request->levels as $id){
            $level = Level::findOrFail($id);
            $level->curriculum_id = $curriculum->id;
            $level->save();
        }

        return redirect()->route('curricula.index')->with('message', __('The curriculum has been updated'));
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
            return redirect()->route('curricula.index')->with('message', __('The curriculum has been deleted.'));
    }
}
