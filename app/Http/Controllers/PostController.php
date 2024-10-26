<?php

namespace App\Http\Controllers;


use App\Http\Requests\PostRequest;
use App\Jobs\InformUserOfNewPost;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\Course;
use App\Models\Group;
use App\Models\Level;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function moderate()
    {   
        $this->authorize('moderate', Post::class);
        //$posts = Post::where('validated', '=', 0)->get();
        return view('posts.moderate');//->with('posts', $posts);
    }

    public function all()
    {   
        $this->authorize('viewAny', Post::class);
        $posts = Post::orderBy('pinned', 'DESC')->latest()->paginate(15);
        return view('posts.all')->with('posts', $posts);
    }

    /**
     * Display a listing of the posts created by the authenticated user.
     */
    public function index()
    {   
        $user = Auth::user();
        $posts = $user->posts()->orderBy('pinned', 'DESC')->latest()->paginate(15);
        //$posts = Post::all();
        return view('posts.index')->with('posts', $posts);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Post::class);

        $post = new Post;
        $post->id = 0;

        $user = auth()->user();
      if($user->hasPermissionTo('publish all posts')){
        $groups = Group::where('id', '!=', 1)->where('id', '!=', 2)->get();
      }else{
        $groups = $user->groups;
      }
      $school = $user->school;
      $curriculum = $user->curriculum;
        return view('posts.edit', compact('post', 'groups', 'school', 'curriculum'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostRequest $request)
    {
        $this->authorize('create', Post::class);
            $user = Auth::user();
        $post = new Post;
        $post->title = $request->title;
        $post->description = $request->description;
        $post->type_id = $request->type_id;
        $post->school_id = $user->school_id;
        $post->quizlet_url = $request->quizlet_url;
        $post->dark_version = $request->has('dark_version');
        $post->early_access = $request->has('early_access');
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
        $post->course_id = $request->course_id;
        $post->level_id = $request->level_id;
        $post->user_id = $user->id;
        $post->save();
        if($post->published){
            dispatch(new InformUserOfNewPost($post));
        }
        return redirect()->route('files.primary.create', $post)->with('message', __('The post has been created. Now, you can upload your primary file.'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        $this->authorize('update', $post);
        $courses = Course::all();
        $levels = Level::all();
        $user = auth()->user();
        $school = $post->school;
        $curriculum = $post->level->curriculum;
      if($user->hasPermissionTo('publish all posts')){
        $groups = Group::where('id', '!=', '1')->where('id', '!=', '2')->get();
      }else{
        $groups = $user->groups;
      }
        return view('posts.edit', compact('post', 'courses', 'levels', 'groups', 'school', 'curriculum'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostRequest $request, Post $post)
    {
        $this->authorize('update', $post);
        $user = Auth::user();
        $oldSlug = $post->slug;
        $oldLevelSlug = $post->level->slug;
        $oldCurriculymSlug = $oldLevelSlug = $post->level->Curriculum->slug;
        $oldCourseSlug = $post->course->slug;

        $post->title = $request->title;
        $post->description = $request->description;
        $post->type_id = $request->type_id;
        $post->quizlet_url = $request->quizlet_url;
        $post->dark_version = $request->has('dark_version');
        $post->early_access = $request->has('early_access');
       
        if($request->visibility == 1 or $request->visibility == 2){
            $post->group_id = $request->visibility;
        }else{
            $groups = $user->groups->pluck('id')->toArray();
            if(in_array($request->visibility, $groups) or $user->hasPermissionTo('publish all posts')){

                $post->group_id = $request->visibility;
            }else{
                $post->group_id = 2;
            }
        }
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
        $post->course_id = $request->course_id;
        $post->level_id = $request->level_id;
        $post->save();
        //Move files to the new directory if course or level or post name is changed :
            foreach ($post->files as $file) {
                $file->name = str_replace($oldSlug, $post->slug, $file->name);
                Storage::disk('public')->move($file->file_path, ''.$post->level->Curriculum->slug.'/'.$post->level->slug.'/'.$post->course->slug.'/'.$file->name.'');
                //Do it with thumbnail
                $file->file_path = ''.$post->level->Curriculum->slug.'/'.$post->level->slug.'/'.$post->course->slug.'/'.$file->name.'';
                $file->save();
            }
        Storage::disk('public')->move(''.$oldCurriculymSlug.'/'.$oldLevelSlug.'/'.$oldCourseSlug.'/'.$post->id.'-'.$oldSlug.'.thumbnail.png', ''.$oldCurriculymSlug.'/'.$post->level->slug.'/'.$post->course->slug.'/'.$post->id.'-'.$post->slug.'.thumbnail.png');
        return redirect()->route('posts.index')->with('message', __('The post has been modified.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    { 
        $this->authorize('destroy', $post);

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
        $comments = $post->comments;
        foreach ($comments as $comment) {
            $comment->delete();
        }
         //Delete the cards
         $cards = $post->cards()->get();
         foreach ($cards as $card) {
             $card->delete();
         }
        //Delete the post
            $post->delete();
            return redirect()->route('posts.index')->with('message', __('The post has been deleted.'));
        
    }

}
