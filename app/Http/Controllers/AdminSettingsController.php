<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\FileRequest;
use App\Http\Requests\FilePrimaryRequest;
use App\Http\Requests\FilePrimaryUpdateRequest;
use App\Http\Requests\ImagesRequest;
use App\Models\CoAdminLog;
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
    $coAdminLogs = CoAdminLog::with('user')->latest()->paginate(50);
    return view('admin', compact(['schools', 'curricula', 'emailDomainRules', 'coAdminLogs']));
}

public function createZipOfStorage(){
    $zip = new ZipArchive;
        $zipFileName = 'RSCMS-files'.date("m.d.y").'.zip';
        $zipPath = storage_path('app/' . $zipFileName);
        if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
             foreach (Storage::allFiles('./') as $file) {
                if (!str_starts_with($file, 'livewire-tmp') && !str_ends_with($file, '.watermarked.pdf')) {
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
        $database = escapeshellarg($connection['database']);
        // Pass the password via the child-process environment to avoid exposing it in the command string
        $env = array_merge(getenv() ?: [], ['MYSQL_PWD' => $connection['password']]);
        $descriptors = [0 => ['pipe', 'r'], 1 => ['file', $path, 'w'], 2 => ['pipe', 'w']];
        $process = proc_open('mysqldump -h ' . $host . ' -P ' . $port . ' -u ' . $username . ' ' . $database, $descriptors, $pipes, null, $env);
        if (is_resource($process)) {
            fclose($pipes[0]);
            fclose($pipes[2]);
            proc_close($process);
        }
        return response()->download($path)->deleteFileAfterSend(true);
    }else{
        abort(403, 'Only available with MYSQL database');
    }
}

}