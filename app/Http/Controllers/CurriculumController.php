<?php

namespace App\Http\Controllers;

use App\Models\Curriculum;
use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
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

        File::delete(self::welcomePagePath($curriculum));
        $curriculum->delete();
            return redirect()->route('settings')->with('message', __('The curriculum has been deleted.'));
    }

    /**
     * Show the welcome page upload form (accessible to admin and co-admins of the curriculum).
     */
    public function editWelcomePage(Curriculum $curriculum)
    {
        $user = auth()->user();
        if (!$user->can('manage curricula') && !$user->managedCurricula->contains($curriculum)) {
            abort(403);
        }
        $hasWelcomePage = File::exists(self::welcomePagePath($curriculum));
        return view('curricula.welcome-page', compact('curriculum', 'hasWelcomePage'));
    }

    /**
     * Upload a Blade welcome page file for the curriculum (accessible to admin and co-admins).
     */
    public function updateWelcomePage(Request $request, Curriculum $curriculum)
    {
        $user = auth()->user();
        if (!$user->can('manage curricula') && !$user->managedCurricula->contains($curriculum)) {
            abort(403);
        }

        $request->validate([
            'welcome_file' => ['required', 'file', 'max:512', 'mimetypes:text/plain,text/x-php,application/x-php,application/octet-stream'],
        ]);

        $file = $request->file('welcome_file');

        if (!str_ends_with($file->getClientOriginalName(), '.blade.php')) {
            return redirect()->back()->withErrors(['welcome_file' => __('The file must have a .blade.php extension.')]);
        }

        // Note: Blade files are executed server-side. Access is intentionally restricted
        // to admin and co-admin users who are considered trusted operators of the platform.
        $dir = storage_path('app/welcome-pages');
        File::ensureDirectoryExists($dir);
        $file->move($dir, $curriculum->id . '.blade.php');

        return redirect()->back()->with('message', __('The welcome page has been updated'));
    }

    /**
     * Delete the Blade welcome page file for the curriculum.
     */
    public function deleteWelcomePage(Curriculum $curriculum)
    {
        $user = auth()->user();
        if (!$user->can('manage curricula') && !$user->managedCurricula->contains($curriculum)) {
            abort(403);
        }

        File::delete(self::welcomePagePath($curriculum));

        return redirect()->back()->with('message', __('The welcome page has been removed'));
    }

    /**
     * Get the filesystem path of the welcome page Blade file for a curriculum.
     */
    public static function welcomePagePath(Curriculum $curriculum): string
    {
        return storage_path('app/welcome-pages/' . $curriculum->id . '.blade.php');
    }
}
