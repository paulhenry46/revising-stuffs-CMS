<?php

namespace App\Http\Controllers;

use App\Models\Curriculum;
use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class CurriculumController extends Controller
{
    /**
     * Display a listing of the resource --> Moved to Settings
     */


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
        $request->validate([
            'subdomain' => ['nullable', 'regex:/^[a-z0-9]([a-z0-9\-]*[a-z0-9])?$/', 'unique:curricula,subdomain'],
        ]);

        $curriculum = new Curriculum();
        $curriculum->name = $request->name;
        $curriculum->description = $request->description;
        $curriculum->slug = Str::slug($request->name, '-');
        $curriculum->subdomain = $request->subdomain ?: null;
        $curriculum->subdomain_enabled = $request->boolean('subdomain_enabled');
        $curriculum->welcome_page = $request->welcome_page ?: null;
        $curriculum->save();
        foreach($request->levels ?? [] as $id){
            $level = Level::findOrFail($id);
            $level->curriculum_id = $curriculum->id;
            $level->save();
        }

        return redirect()->route('settings')->with('message', __('The curriculum has been created'));
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
        $request->validate([
            'subdomain' => ['nullable', 'regex:/^[a-z0-9]([a-z0-9\-]*[a-z0-9])?$/', 'unique:curricula,subdomain,' . $curriculum->id],
        ]);

        $curriculum->name = $request->name;
        $curriculum->description = $request->description;
        $curriculum->slug = Str::slug($request->name, '-');
        $curriculum->subdomain = $request->subdomain ?: null;
        $curriculum->subdomain_enabled = $request->boolean('subdomain_enabled');
        $curriculum->welcome_page = $request->welcome_page ?: null;
        $curriculum->save();
        foreach($request->levels ?? [] as $id){
            $level = Level::findOrFail($id);
            $level->curriculum_id = $curriculum->id;
            $level->save();
        }

        return redirect()->route('settings')->with('message', __('The curriculum has been updated'));
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
            return redirect()->route('settings')->with('message', __('The curriculum has been deleted.'));
    }

    /**
     * Show the welcome page edit form (accessible to admin and co-admins of the curriculum).
     */
    public function editWelcomePage(Curriculum $curriculum)
    {
        $user = auth()->user();
        if (!$user->can('manage curricula') && !$user->managedCurricula->contains($curriculum)) {
            abort(403);
        }
        return view('curricula.welcome-page', compact('curriculum'));
    }

    /**
     * Update the welcome page for the curriculum (accessible to admin and co-admins).
     */
    public function updateWelcomePage(Request $request, Curriculum $curriculum)
    {
        $user = auth()->user();
        if (!$user->can('manage curricula') && !$user->managedCurricula->contains($curriculum)) {
            abort(403);
        }

        $curriculum->welcome_page = $request->welcome_page ?: null;
        $curriculum->save();

        return redirect()->back()->with('message', __('The welcome page has been updated'));
    }
}
