<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TypeRequest;
use App\Models\Type;
use App\Models\Course;
use Illuminate\Support\Str;

class TypeController extends Controller
{
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $type = new Type;
        $type->id = 0;
        $courses = Course::where('id', '!=', '1')->get();
        return view('types.edit', compact(['type', 'courses']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TypeRequest $request)
    {
    $type = new Type;
    $type->name = $request->name;
    $type->color = $request->color;
    $type->slug = Str::slug($request->name, '-');
    if($request->has('forall'))
    {
        $type->course_id = 1;
    }else
    {
        $type->course_id = $request->course_id;
    }
    $type->save();
    return redirect()->route('levels.index')->with('message', __('The type has been created.'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Type $type)
    {
        $courses = Course::where('id', '!=', '1')->get();
        return view('types.edit', compact(['type', 'courses']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TypeRequest $request, Type $type)
    {
    $type->name = $request->name;
    $type->color = $request->color;
    $type->slug = $slug = Str::slug($request->name, '-');
    if($request->has('forall'))
    {
        $type->course_id = 1;
    }else
    {
        $type->course_id = $request->course_id;
    }
    
    $type->save();
    return redirect()->route('levels.index')->with('message', __('The type has been modified.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Type $type)
    {   if($type->posts()->count() == 0){
            $type->delete();
            return redirect()->route('levels.index')->with('message', __('The type has been deleted.'));
        }else{
            return redirect()->route('levels.index')->with('warning', __('The type has posts attached. Please delete all of this posts or change their type before deleting this category.'));
        }

    }
}
