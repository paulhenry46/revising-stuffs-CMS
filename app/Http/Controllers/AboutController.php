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
        $contributors = User::where('id', '!=', 1)
            ->whereHas('roles', function ($query) {
            $query->where('name', 'contributor');
            })
            ->whereHas('posts', function ($query) {
            $query->where('published', true);
            })
            ->whereDoesntHave('roles', function ($query) {
            $query->whereIn('name', ['admin', 'co-admin', 'moderator']);
            })
            ->get();

        $admins = User::where('id', '!=', 1)
            ->whereHas('roles', function ($query) {
                $query->whereIn('name', ['admin', 'co-admin', 'moderator']);
            })
            ->get();

        return view('about.index', compact(['contributors', 'admins']));
    }

}
