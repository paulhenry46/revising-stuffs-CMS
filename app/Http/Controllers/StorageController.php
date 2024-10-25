<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StorageController extends Controller
{
    public function getFile(string $path){
        $headers = [];
        $new_path = 'public/'.$path.'';
        $new_path = Storage::disk('local')->path($new_path);
        $name = basename($new_path);
        if(strpos($name, '.dark.pdf')){
            $canonical_link = url('storage/'.str_replace('.dark.pdf', '.light.pdf',$path).'');
            //dd($canonical_link);
            $headers = ['link' =>'<'.$canonical_link.'>; rel="canonical"'];
        }
        if(file_exists($new_path)){
            return response()->file($new_path, $headers);
        }else{
            abort(404);
        }
    }
}
