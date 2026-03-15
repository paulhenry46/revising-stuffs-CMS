<?php

namespace App\Http\Controllers;

use App\Jobs\IncrementDownload_count;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StorageController extends Controller
{
    public function getFile(string $path){
        $headers = [];//Create headers array
        $basePath = realpath(Storage::disk('local')->path('public'));
        $resolvedPath = realpath($basePath . DIRECTORY_SEPARATOR . $path);

        // Prevent path traversal: ensure the resolved path stays within the public storage directory
        if ($resolvedPath === false || !str_starts_with($resolvedPath, $basePath . DIRECTORY_SEPARATOR)) {
            abort(404);
        }

        $name = basename($resolvedPath);//Create name
        if(file_exists($resolvedPath)){

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
}
