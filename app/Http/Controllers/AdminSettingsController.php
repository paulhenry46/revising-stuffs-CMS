<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\FileRequest;
use App\Http\Requests\FilePrimaryRequest;
use App\Http\Requests\FilePrimaryUpdateRequest;
use App\Http\Requests\ImagesRequest;
use App\Models\Curriculum;
use App\Models\EmailDomainRule;
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
    $emailDomainRules = EmailDomainRule::with('curricula')->get();
    return view('admin', compact(['schools', 'curricula', 'emailDomainRules']));
}

public function createZipOfStorage(){
    $zip = new ZipArchive;
        $zipFileName = 'RSCMS-files'.date("m.d.y").'.zip';
        $zipPath = storage_path('app/' . $zipFileName);
        if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
            foreach (Storage::allFiles('public') as $file) {
                if (basename($file) !== '.gitignore') {
                    $zip->addFile(''.storage_path().'/app/'.$file.'', $file);
                }
            }
            $zip->close();
            return response()->download($zipPath)->deleteFileAfterSend(true);
        }
}

public function createBackupOfDB(){
    $path = storage_path('app/export.sql');
    if(config('database.default') == 'mysql'){
        $connection = config('database.connections.mysql');
        $host = escapeshellarg($connection['host']);
        $port = escapeshellarg((string) $connection['port']);
        $username = escapeshellarg($connection['username']);
        $password = escapeshellarg($connection['password']);
        $database = escapeshellarg($connection['database']);
        $outPath = escapeshellarg($path);
        exec('MYSQL_PWD=' . $password . ' mysqldump -h ' . $host . ' -P ' . $port . ' -u ' . $username . ' ' . $database . ' > ' . $outPath . ' 2>&1');
    }
    return response()->download($path)->deleteFileAfterSend(true);
}

}