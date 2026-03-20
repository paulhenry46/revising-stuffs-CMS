<?php

namespace App\Http\Controllers;

use App\Jobs\IncrementDownload_count;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StorageController extends Controller
{
    public function getFile(string $path){
        $headers = [];//Create headers array
        $basePath = realpath(Storage::disk('local')->path('public'));
        $resolvedPath = realpath($basePath . DIRECTORY_SEPARATOR . $path);

        // Prevent path traversal: ensure the resolved path stays within the public storage directory
        if ($resolvedPath === false || $resolvedPath === $basePath || !str_starts_with($resolvedPath, $basePath . DIRECTORY_SEPARATOR)) {
            abort(404);
        }

        $name = basename($resolvedPath);//Create name
        if(file_exists($resolvedPath)){

            if(strpos($name, '.dark.pdf') || strpos($name, '.light.pdf')){
                // Determine if this is a primary PDF (not a watermarked copy)
                if (!Str::endsWith($name, '.watermarked.pdf')) {
                    // Check whether the requesting user is the original author or an admin;
                    // if not, redirect them to the watermarked version.
                    $relativePath = ltrim($path, '/');
                    $file = File::where('file_path', $relativePath)->first();

                    if ($file && !$this->canDownloadOriginal($file)) {
                        if ($file->watermarked_file_path) {
                            $watermarkedResolved = realpath($basePath . DIRECTORY_SEPARATOR . $file->watermarked_file_path);
                            if ($watermarkedResolved && str_starts_with($watermarkedResolved, $basePath . DIRECTORY_SEPARATOR) && file_exists($watermarkedResolved)) {
                                dispatch(new IncrementDownload_count($name, strpos($name, '.dark.pdf') ? 'primary dark' : 'primary light'));
                                return response()->file($watermarkedResolved);
                            }
                        }
                        // No watermarked version available yet — fall through to serve original
                    }
                }
            }

            if(strpos($name, '.dark.pdf')){
                $canonical_link = url('storage/'.str_replace('.dark.pdf', '.light.pdf',$path).'');
                $headers = ['link' =>'<'.$canonical_link.'>; rel="canonical"'];
                dispatch(new IncrementDownload_count($name, 'primary dark'));
            }elseif(strpos($name, '.light.pdf')){
                dispatch(new IncrementDownload_count($name, 'primary light'));
            }

            return response()->file($resolvedPath, $headers);
        }else{
            abort(404);
        }
    }

    /**
     * Determine whether the currently authenticated user may download the original (un-watermarked) file.
     * Returns true for the post's original author and for admins.
     */
    private function canDownloadOriginal(File $file): bool
    {
        $user = Auth::user();
        if (!$user) {
            return false;
        }
        if ($user->hasRole('admin')) {
            return true;
        }
        $post = $file->post;
        return $post && $post->user_id === $user->id;
    }
}

