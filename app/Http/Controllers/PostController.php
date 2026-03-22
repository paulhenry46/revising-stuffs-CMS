<?php

namespace App\Http\Controllers;


use App\Http\Requests\PostRequest;
use App\Jobs\AddWatermarkToPdf;
use App\Jobs\InformUserOfNewPost;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\CoAdminLog;
use App\Models\Post;
use App\Models\Course;
use App\Models\Group;
use App\Models\Level;
use App\Models\Step;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function short(Post $post)
    {
        return redirect()->route('post.public.view', [$post->slug, $post->id]);
    }

    public function moderate()
    {   
        $this->authorize('moderate', Post::class);
        //$posts = Post::where('validated', '=', 0)->get();
        return view('posts.moderate');//->with('posts', $posts);
    }

    public function certify()
    {
        $this->authorize('moderate', Post::class);
        return view('posts.certify');
    }

    public function all()
    {   
        $this->authorize('viewAny', Post::class);
        $user = Auth::user();
        $query = Post::orderBy('pinned', 'DESC')->latest();
        if ($user->hasRole('co-admin') && !$user->hasRole('admin')) {
            $curriculaIds = $user->getManagedCurriculaIds();
            $levelIds = Level::whereIn('curriculum_id', $curriculaIds)->pluck('id');
            $query->whereIn('level_id', $levelIds);
        }
        $posts = $query->paginate(15);
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
        if ($user->hasRole('co-admin') && !$user->hasRole('admin')) {
            CoAdminLog::create([
                'user_id'       => $user->id,
                'action'        => 'created_post',
                'subject_type'  => 'Post',
                'subject_id'    => $post->id,
                'subject_label' => $post->title,
            ]);
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
        $oldCurriculymSlug = $post->level->Curriculum->slug;
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
            $wasPublished = $post->published;
            $post->published = $request->has('published');
        }else{
            $wasPublished = $post->published;
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
                // Move or clear the watermarked version if it exists
                if ($file->watermarked_file_path) {
                    $oldWatermarkedPath = $file->watermarked_file_path;
                    $newWatermarkedPath = $this->buildWatermarkedPath($file->file_path);
                    if (Storage::disk('public')->exists($oldWatermarkedPath)) {
                        Storage::disk('public')->move($oldWatermarkedPath, $newWatermarkedPath);
                        $file->watermarked_file_path = $newWatermarkedPath;
                    } else {
                        $file->watermarked_file_path = null;
                    }
                }
                $file->save();
            }
        Storage::disk('public')->move(''.$oldCurriculymSlug.'/'.$oldLevelSlug.'/'.$oldCourseSlug.'/'.$post->id.'-'.$oldSlug.'.thumbnail.png', ''.$oldCurriculymSlug.'/'.$post->level->slug.'/'.$post->course->slug.'/'.$post->id.'-'.$post->slug.'.thumbnail.png');
        // If the post just became published, dispatch watermark jobs for all primary files
        // (dispatched after file moves so the job reads the updated file_path)
        if (!$wasPublished && $post->published) {
            foreach ($post->files as $file) {
                if (in_array($file->type, ['primary light', 'primary dark'])) {
                    dispatch(new AddWatermarkToPdf($file->id));
                }
            }
        }
        if ($user->hasRole('co-admin') && !$user->hasRole('admin')) {
            CoAdminLog::create([
                'user_id'       => $user->id,
                'action'        => 'updated_post',
                'subject_type'  => 'Post',
                'subject_id'    => $post->id,
                'subject_label' => $post->title,
            ]);
        }
        return redirect()->route('posts.index')->with('message', __('The post has been modified.'));
    }

    /**
     * Derive the watermarked file path from the original path.
     * e.g. "foo/bar/1-slug.light.pdf" => "foo/bar/1-slug.light.watermarked.pdf"
     */
    private function buildWatermarkedPath(string $originalPath): string
    {
        if (str_ends_with($originalPath, '.pdf')) {
            return substr($originalPath, 0, -4) . '.watermarked.pdf';
        }
        return $originalPath . '.watermarked.pdf';
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
                // Also delete the watermarked version if it exists
                if ($file->watermarked_file_path) {
                    Storage::disk('public')->delete($file->watermarked_file_path);
                }
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
         $decks = $post->decks()->get();
            
         
         foreach ($decks as $deck) {
            Step::where('deck_id', $deck->id )->delete();
             $deck->cards()->delete();
             $deck->delete();
         }
        //Delete the post
            $user = Auth::user();
            $postTitle = $post->title;
            $postId = $post->id;
            $post->delete();
            if ($user->hasRole('co-admin') && !$user->hasRole('admin')) {
                CoAdminLog::create([
                    'user_id'       => $user->id,
                    'action'        => 'deleted_post',
                    'subject_type'  => 'Post',
                    'subject_id'    => $postId,
                    'subject_label' => $postTitle,
                ]);
            }
            return redirect()->route('posts.index')->with('message', __('The post has been deleted.'));
        
    }

}
