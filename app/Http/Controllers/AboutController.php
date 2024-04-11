<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AboutController extends Controller
{
    public function licensing(){
        return view('about.licensing');
    }

    public function legal(){
        return view('about.legal');
    }

    public function index(){
        $contributors=User::where('id', '!=', 1)->role('contributor')->get();
        return view('about.index', compact(['contributors']));
    }

}