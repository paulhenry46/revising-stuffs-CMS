<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LevelRequest;
use App\Models\Level;
use App\Models\Course;
use App\Models\Type;
use Illuminate\Support\Str;

class LevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {   $levels = Level::all();
        $courses = Course::where('id', '!=', '1')->get();
        $types = Type::all();
        return view('levels.index', compact(['levels', 'courses', 'types']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $level = new Level;
        $level->id = 0;
        $courses = Course::where('id', '!=', '1')->get();
        return view('levels.edit', compact(['level', 'courses']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LevelRequest $request)
    {
    $level = new Level;
    $level->name = $request->name;
    $level->slug = $slug = Str::slug($request->name, '-');
    $level->save();
    $level->courses()->attach($request->courses);
    return redirect()->route('levels.index')->with('message', __('The level has been created.'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Level $level)
    {
        $courses = Course::where('id', '!=', '1')->get();
        return view('levels.edit', compact(['level', 'courses']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LevelRequest $request, Level $level)
    {
    $level->name = $request->name;
    $level->slug = $slug = Str::slug($request->name, '-');
    $level->save();
    $level->courses()->sync($request->courses);
    return redirect()->route('levels.index')->with('message', __('The course has been modified.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Level $level)
    {   if($level->posts()->count() == 0){
            $level->delete();
            return redirect()->route('levels.index')->with('message', __('The level has been deleted.'));
        }else{
            return redirect()->route('levels.index')->with('warning', __('The level has posts attached. Please delete all of this posts or change their level before deleting this category.'));
        }

    }
}
