<?php

namespace App\Http\Controllers\Public;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use Auth;
use Carbon\Carbon;

class ReadUserController extends Controller
{
    public function view(User $user)
    {
        $user = User::findOrFail($user->id);
        $posts = Post::where('user_id', $user->id)->latest()->limit(4)->get();
        return view('users.view', compact('user', 'posts'));
    }

}
