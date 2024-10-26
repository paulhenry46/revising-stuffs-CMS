<?php

namespace App\Http\Controllers;

use App\Jobs\IncrementDownload_count;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StorageController extends Controller
{
    public function getFile(string $path){
        $headers = [];//Create headers array
        $new_path = 'public/'.$path.''; //Create the path from url
        $new_path = Storage::disk('local')->path($new_path); //Create aboslute path
        $name = basename($new_path);//Create name
        if(file_exists($new_path)){

            if(strpos($name, '.dark.pdf')){
                $canonical_link = url('storage/'.str_replace('.dark.pdf', '.light.pdf',$path).'');
                //dd($canonical_link);
                $headers = ['link' =>'<'.$canonical_link.'>; rel="canonical"'];
                dispatch(new IncrementDownload_count($name, 'primary dark'));
            }elseif(strpos($name, '.light.pdf')){
                dispatch(new IncrementDownload_count($name, 'primary light'));
            }

            return response()->file($new_path, $headers);
        }else{
            abort(404);
        }
    }
}
