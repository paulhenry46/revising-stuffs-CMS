<?php

namespace App\Http\Controllers;

use App\Http\Controllers\CurriculumController;
use Illuminate\Support\Facades\File;

class WelcomeController extends Controller
{
    public function index()
    {
        $subdomainCurriculum = app()->bound('subdomainCurriculum') ? app('subdomainCurriculum') : null;

        if ($subdomainCurriculum) {
            $path = CurriculumController::welcomePagePath($subdomainCurriculum);
            if (File::exists($path)) {
                return view()->file($path, ['subdomainCurriculum' => $subdomainCurriculum]);
            }
        }

        return view('welcome');
    }
}
