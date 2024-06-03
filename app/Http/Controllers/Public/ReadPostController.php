<?php

namespace App\Http\Controllers\Public;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Course;
use App\Models\Curriculum;
use App\Models\Level;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class ReadPostController extends Controller
{
    public function view(string $slug, Post $post)
    {
        if($slug != $post->slug){
            abort(404);
        }else{
            $comments = $post->comments->where('validated', '=', 1);
            $events = $post->events;
            $files = $post->files;
            return view('posts.view', compact('post', 'comments', 'events', 'files'));
        }
    }

    public function news()
    {
        //Logic moved to Livewire Component NewPosts
        return view('posts.news');
    }

    public function favorites()
    {
            if (Auth::check()) {
            $user = Auth::user();
            $posts = Post::where('published', '=', 1)->whereIn('id', $user->favorite_posts)->latest()->get();
            $RevisePosts = Post::whereHas('steps', function ($query) {
                $query->where('user_id', Auth::id());
                $query->where('next_step', '<=', Carbon::today());
                $query->where('next_step', '!=', null);
            })->get();
            $logged = true;
        }else{
            $posts = null;
            $RevisePosts = null;
            $logged = false;
        }
            return view('posts.favorites', compact('posts', 'RevisePosts', 'logged'));
    }


    public function course(string $curriculum_chosen, string $level_chosen, string $course_chosen)
    {   $curriculum = Curriculum::where('slug', $curriculum_chosen)->first();
        $level = Level::where('slug', $level_chosen)->where('curriculum_id', $curriculum->id)->first();
        $course = Course::where('slug', $course_chosen)->first();
        
        return view('posts.course', compact('level', 'course'));
    }

    public function library()
    {   
        $curricula = Curriculum::all();
        //$levels = Level::All();
        return view('posts.library', compact('curricula'));
    }

}
