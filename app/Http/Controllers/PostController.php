<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PostRequest;
use Illuminate\Support\Facades\Storage;
use App\Models\Post;
use App\Models\Course;
use App\Models\Level;
use Illuminate\Support\Str;
use Auth;

class PostController extends Controller
{
    public function indexModerator()
    {   //$posts = Post::where('validated', '=', 0)->get();
        return view('posts.moderate');//->with('posts', $posts);
    }

    public function viewPublic(string $slug, Post $post)
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

    public function viewNews()
    {
        $newPosts = Post::where('published', '=', 1)->where('pinned', '=', 0)->latest()->limit(5)->get();
        $pinnedPosts = Post::where('pinned', '=', 1)->where('published', '=', 1)->get();
        return view('posts.news', compact('newPosts', 'pinnedPosts'));
    }

    public function viewFavorites()
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

    /**
     * Display a listing of the posts created by the authenticated user.
     */
    public function index()
    {   
        $user = Auth::user();
        $posts = $user->posts()->latest()->get();
        //$posts = Post::all();
        return view('posts.show')->with('posts', $posts);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $courses = Course::all();
        $levels = Level::all();
        $post = new Post;
        $post->id = 0;
        return view('posts.edit', compact('post', 'courses', 'levels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostRequest $request)
    {
        $user = Auth::user();
    $post = new Post;
    $post->title = $request->title;
    $post->description = $request->description;
    $post->type = $request->type;
    $post->quizlet_url = $request->quizlet_url;
    $post->dark_version = $request->has('dark_version');
    $post->thanks = 0;
    if($user->hasPermissionTo('publish own posts')){
        $post->published = $request->has('published');
    }else{
        $post->published = false;
    }
    if($user->hasPermissionTo('manage all posts')){
        $post->pinned = $request->has('pinned');
        if($request->date !== NULL){
            $post->created_at = $request->date;
        }
    }else{
        $post->pinned = false;
    }
    $post->slug = Str::slug($request->title, '-');
    if($request->has('public')){
    $post->public = 'public';
    }else{
        $post->public = 'specific';
    }
    $post->course_id = $request->course_id;
    $post->level_id = $request->level_id;
    $post->user_id = $user->id;
    $post->save();
    return redirect()->route('posts.index')->with('message', __('The post has been created.'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        $courses = Course::all();
        $levels = Level::all();
        return view('posts.edit', compact('post', 'courses', 'levels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostRequest $request, Post $post)
    {
    $user = Auth::user();
    $post->title = $request->title;
    $post->description = $request->description;
    $post->type = $request->type;
    $post->quizlet_url = $request->quizlet_url;
    $post->dark_version = $request->has('dark_version');
    $post->thanks = 0;
    if($user->hasPermissionTo('publish own posts')){
        $post->published = $request->has('published');
    }else{
        $post->published = false;
    }
    if($user->hasPermissionTo('manage all posts')){
        $post->pinned = $request->has('pinned');
    }else{
        $post->pinned = false;
    }
    $post->slug = Str::slug($request->title, '-');
    if($request->has('public')){
    $post->public = 'public';
    }else{
        $post->public = 'specific';
    }
    $post->course_id = $request->course_id;
    $post->level_id = $request->level_id;
    $post->user_id = $user->id;
    $post->save();
    return redirect()->route('posts.index')->with('message', __('The post has been modified.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    { 
        $user = Auth::user();
        if(($user->id == $post->user_id) or ($user->hasPermissionTo('manage all posts'))){
        //Delete the primary file(s) and complementary file(s)
            $files = $post->files;
            foreach ($files as $file) {
                $delete = Storage::disk('public')->delete($file->file_path);
                $file->delete();
            }
        //Delete the thumbnail
          $delete = Storage::disk('public')->delete(''.$post->level->slug.'/'.$post->course->slug.'/'.$post->id.'-'.$post->slug.'.thumbnail.png');
        //Delete the event Items
            $events = $post->events;
            foreach ($events as $event) {
                $event->delete();
            }
        //Delete the comments
        //Delete the post
            $post->delete();
            return redirect()->route('posts.index')->with('message', __('The post has been deleted.'));
        }
        }

    }
