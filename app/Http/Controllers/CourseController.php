<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CourseRequest;
use App\Models\Course;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {   
        return redirect()->route('levels.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $course = new Course;
        $course->id = 0;
        return view('courses.edit', compact('course'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CourseRequest $request)
    {
    $course = new Course;
    $course->name = $request->name;
    $course->color = $request->color;
    $course->lang = $request->lang;
    $course->slug = Str::slug($request->name, '-');
    $course->save();
    return redirect()->route('levels.index')->with('message', __('The course has been created.'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        return view('courses.edit', compact('course'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CourseRequest $request, Course $course)
    {
    $course->name = $request->name;
    $course->color = $request->color;
    $course->lang = $request->lang;
    $course->slug = $slug = Str::slug($request->name, '-');
    $course->save();
    return redirect()->route('levels.index')->with('message', __('The course has been modified.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {   if($course->posts()->count() == 0){
            $course->delete();
            return redirect()->route('levels.index')->with('message', __('The course has been deleted.'));
        }else{
            return redirect()->route('levels.index')->with('warning', __('The course has posts attached. Please delete all of this posts or change their course before deleting this category.'));
        }

    }
}
