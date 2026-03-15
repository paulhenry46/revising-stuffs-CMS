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
     * Download the custom welcome page file for the curriculum.
     */
    public function downloadWelcomePage(Curriculum $curriculum)
    {
        $user = auth()->user();
        if (!$user->can('manage curricula') && !$user->managedCurricula->contains($curriculum)) {
            abort(403);
        }
        $path = self::welcomePagePath($curriculum);
        if (!\Illuminate\Support\Facades\File::exists($path)) {
            return redirect()->back()->withErrors(['welcome_file' => __('No custom welcome page found.')]);
        }
        return response()->download($path, $curriculum->slug . '-welcome-page.blade.php');
    }

    /**
     * Download the default welcome page file.
     */
    public function downloadDefaultWelcomePage(Curriculum $curriculum)
    {
        $user = auth()->user();
        if (!$user->can('manage curricula') && !$user->managedCurricula->contains($curriculum)) {
            abort(403);
        }
        // Path to the default welcome page template
        $defaultPath = resource_path('views/welcome.blade.php');
        if (!\Illuminate\Support\Facades\File::exists($defaultPath)) {
            return redirect()->back()->withErrors(['welcome_file' => __('Default welcome page not found.')]);
        }
        return response()->download($defaultPath, 'default-welcome-page.blade.php');
    }

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
        $logoPath = self::logoPath($curriculum);
        if ($logoPath) {
            File::delete($logoPath);
        }
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
            'welcome_file' => ['required', 'file', 'max:512', 'mimes:php,txt,blade.php'],
        ]);

        $file = $request->file('welcome_file');

        if (!str_ends_with($file->getClientOriginalName(), '.blade.php')) {
            return redirect()->back()->withErrors(['welcome_file' => __('The file must have a .blade.php extension.')]);
        }

        // Store in a pending folder for admin review
        $pendingDir = storage_path('app/pending-welcome-pages');
        File::ensureDirectoryExists($pendingDir);
        $file->move($pendingDir, $curriculum->id . '.blade.php');

        // Optionally, notify admins here (implementation depends on notification system)
        $admins = \App\Models\User::role('admin')->where('id', '!=', 1)->get();
        foreach ($admins as $admin){
            $admin->notify(new \App\Notifications\WPageUploaded($curriculum));
        }

        return redirect()->back()->with('message', __('The welcome page has been uploaded and is pending admin approval.'));
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

    /**
     * Show the logo upload form (accessible to admin and co-admins of the curriculum).
     */
    public function editLogo(Curriculum $curriculum)
    {
        $user = auth()->user();
        if (!$user->can('manage curricula') && !$user->managedCurricula->contains($curriculum)) {
            abort(403);
        }
        $logoUrl = self::logoUrl($curriculum);
        return view('curricula.logo', compact('curriculum', 'logoUrl'));
    }

    /**
     * Upload a logo image for the curriculum (accessible to admin and co-admins).
     */
    public function updateLogo(Request $request, Curriculum $curriculum)
    {
        $user = auth()->user();
        if (!$user->can('manage curricula') && !$user->managedCurricula->contains($curriculum)) {
            abort(403);
        }

        $request->validate([
            'logo_file' => ['required', 'file', 'max:2048', 'mimes:jpg,jpeg,png,gif,webp'],
        ]);

        $file = $request->file('logo_file');

        // Remove any previously uploaded logo before storing the new one
        $existing = self::logoPath($curriculum);
        if ($existing) {
            File::delete($existing);
        }

        $dir = storage_path('app/public/curriculum-logos');
        File::ensureDirectoryExists($dir);
        // Derive extension from the validated MIME type rather than the client-supplied filename
        $ext = $file->extension();
        $file->move($dir, $curriculum->id . '.' . $ext);

        return redirect()->back()->with('message', __('The logo has been updated'));
    }

    /**
     * Delete the logo file for the curriculum.
     */
    public function deleteLogo(Curriculum $curriculum)
    {
        $user = auth()->user();
        if (!$user->can('manage curricula') && !$user->managedCurricula->contains($curriculum)) {
            abort(403);
        }

        $path = self::logoPath($curriculum);
        if ($path) {
            File::delete($path);
        }

        return redirect()->back()->with('message', __('The logo has been removed'));
    }

    /**
     * Get the filesystem path of the logo for a curriculum (null if none uploaded).
     */
    public static function logoPath(Curriculum $curriculum): ?string
    {
        $dir = storage_path('app/public/curriculum-logos');
        $files = glob($dir . '/' . $curriculum->id . '.*');
        // Filter to exact basename match so id=5 doesn't accidentally match id=50
        $files = array_values(array_filter($files ?? [], function ($f) use ($curriculum) {
            return pathinfo($f, PATHINFO_FILENAME) === (string) $curriculum->id;
        }));
        return count($files) > 0 ? $files[0] : null;
    }

    /**
     * Get the public URL of the logo for a curriculum (null if none uploaded).
     */
    public static function logoUrl(Curriculum $curriculum): ?string
    {
        $path = self::logoPath($curriculum);
        if (!$path) {
            return null;
        }
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        return url('storage/curriculum-logos/' . $curriculum->id . '.' . $ext);
    }
}
