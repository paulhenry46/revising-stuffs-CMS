<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\FileRequest;
use App\Http\Requests\FilePrimaryRequest;
use App\Http\Requests\FilePrimaryUpdateRequest;
use App\Http\Requests\ImagesRequest;
use App\Models\Curriculum;
use App\Models\Post;
use App\Models\File;
use App\Models\Event;
use App\Models\School;
use Illuminate\Support\Str;
use ZipArchive;

class AdminSettingsController extends Controller
{
/*Show the page*/
public function show(){
    $schools = School::all();
    $curricula = Curriculum::all();
    return view('admin', compact(['schools', 'curricula']));
}

public function createZipOfStorage(){
    $zip = new ZipArchive;
        $zipFileName = 'RSCMS-files'.date("m.d.y").'.zip';
        if ($zip->open(public_path($zipFileName), ZipArchive::CREATE) === TRUE) {
            foreach (Storage::allFiles('public') as $file) {
                if (basename($file) !== '.gitignore') {
                    //dd(''.storage_path().'/app/'.$file.'');
                    $zip->addFile(''.storage_path().'/app/'.$file.'', $file);
                }
            }
            $zip->close();
            return response()->download(public_path($zipFileName))->deleteFileAfterSend(true);
        }
}

public function createBackupOfDB(){
    $path =''.storage_path().'/app/export.sql';
    if(env('DB_CONNECTION') == 'mysql'){
        exec('MYSQL_PWD="'.env('DB_PASSWORD').'" mysqldump -u '.env('DB_USERNAME').' '.env('DB_DATABASE').' > '.$path.' 2>&1');
    }
    return response()->download($path)->deleteFileAfterSend(true);
}

}