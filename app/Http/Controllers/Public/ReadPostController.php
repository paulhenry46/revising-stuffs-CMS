<?php

namespace App\Http\Controllers\Public;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Course;
use App\Models\Level;
use Auth;

class ReadPostController extends Controller
{
    public function view(string $slug, Post $post)
    {
        if($slug != $post->slug){
            return redirect()->route('post.public.view', [$post->slug, $post->id]);

        }else{
            $comments = $post->comments->where('validated', '=', 1);
            $events = $post->events;
            $files = $post->files;
            return view('posts.view', compact('post', 'comments', 'events', 'files'));
        }
    }

    public function news()
    {
        $newPosts = Post::where('published', '=', 1)->where('pinned', '=', 0)->latest()->limit(5)->get();
        $pinnedPosts = Post::where('pinned', '=', 1)->where('published', '=', 1)->get();
        return view('posts.news', compact('newPosts', 'pinnedPosts'));
    }

    public function favorites()
    {
            if (Auth::check()) {
            $user = Auth::user();
            $posts = Post::where('published', '=', 1)->whereIn('id', $user->favorite_posts)->latest()->get();
            $logged = true;
        }else{
            $posts = null;
            $logged = false;
        }
            return view('posts.favorites', compact('posts', 'logged'));
    }


    public function course(string $level_chosen, string $course_chosen)
    {   
        $course = Course::where('slug', $course_chosen)->first();
        $level = Level::where('slug', $level_chosen)->first();
        return view('posts.course', compact('level', 'course'));
    }

    public function library()
    {   
        $courses = Course::where('id', '!=', '1')->get();
        //$levels = Level::All();
        return view('posts.library', compact('courses'));
    }

}
