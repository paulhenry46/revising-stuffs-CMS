<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\FileRequest;
use App\Http\Requests\FilePrimaryRequest;
use App\Http\Requests\FilePrimaryUpdateRequest;
use App\Models\Post;
use App\Models\File;
use App\Models\Event;
use Illuminate\Support\Str;
use Auth;
use App\Jobs\CreateThumbnail;

class FileController extends Controller
{
    /**
     * Display a listing of the files attached to a specific ressource.
     */
    public function index(Post $post)
    {   $user = Auth::user();
        if($user->id == $post->user_id){
            $files = $post->files;
            return view('files.show', compact('files', 'post'));
        }else{ 
            return view('posts.show')->with('warning', __('You didn\'t created this post. As a result, you can\'t view its files.')); 
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Post $post)
    {
        $file = new File;
        $file->id = 0;
        $file->type = 'none';

        $user = Auth::user();
        if($user->id == $post->user_id){
            return view('files.edit', compact('file', 'post'));
        }else{ 
            return view('posts.show')->with('warning', __('You didn\'t created this post. As a result, you can\'t add files.')); 
        }
    }
    /**
     * Show the form for creating the primary files
     */
    public function createPrimary(Post $post)
    {
        $state ="create";
        $user = Auth::user();
        if($user->id == $post->user_id and $post->files->where('type', '=', 'primary light')->count() == 0){
            return view('files.primary-edit', compact('state', 'post'));
        }else{ 
            return redirect()->route('files.index', $post->id)->with('warning', __('There is already primary file for this post. ')); 
        }
    }
    /**
     * Save the primary files in filesystem
     */
    public function storePrimary(FilePrimaryRequest $request, Post $post)
    {
        if(Auth::user()->id == $post->user_id){
            if(($post->dark_version) == 1 and (!$request->has('file_dark'))){
                $files = $post->files;
                return redirect()->route('files.index', compact('files', 'post'))->with('warning', __('The post has a dark version but you didn\'t provided dark file. Please retry providing a dark version.'));
            }else{
            /*Prepare the values*/
                $folder = ''.$post->level->slug.'/'.$post->course->slug.'';

                /*For the light file*/
                $filename_path_light = ''.$post->id.'-'.$post->slug.'.light.pdf';
                $filename_path_thumbnail = ''.$post->id.'-'.$post->slug.'.thumbnail.png';
                /*save the file*/
                $path_light = $request->file_light->storeAs($folder, $filename_path_light, 'public');

                /*create the file in bdd*/
                $file = new File;
                $file->type = 'primary light';
                $file->name = $filename_path_light;
                $file->file_path = $path_light;
                $file->post_id = $post->id;
                $file->save();
                //$pdf = new Pdf(storage_path('app/public/'.$path_light.''));
                //$pdf->saveImage(storage_path('app/public/'.$folder.'/'.$filename_path_thumbnail.''));
                dispatch(new CreateThumbnail($path_light, $filename_path_thumbnail, $folder ));
                /*for the dark file*/
                if($post->dark_version){
                $filename_path_dark = ''.$post->id.'-'.$post->slug.'.dark.pdf';
                /*save the file*/
                $path_dark = $request->file_dark->storeAs($folder, $filename_path_dark, 'public');

                /*create the file in bdd*/
                $file = new File;
                $file->type = 'primary dark';
                $file->name = $filename_path_dark;
                $file->file_path = $path_dark;
                $file->post_id = $post->id;
                $file->save();
                }

                $files = $post->files;
                return redirect()->route('files.index', compact('files', 'post'))->with('message', __('The primary file(s) has been created.'));
            }
            
        }else{ 
            return view('posts.show')->with('warning', __('You didn\'t created this post. As a result, you can\'t add files.')); 
        }
}


    /**
     * Store a newly created resource in storage.
     */
    public function store(FileRequest $request, Post $post)
    {
        if(Auth::user()->id == $post->user_id){
                $folder = ''.$post->level->slug.'/'.$post->course->slug.'';
                $filename_path = ''.$post->id.'.'.Str::slug($request->name, '-').'.'.$request->file->extension().'';
                $filename = $request->name;
                $path = $request->file->storeAs($folder, $filename_path, 'public');
                $file = new File;
                $file->type = $request->type;
                $file->name = $filename;
                $file->file_path = $path;
                $file->post_id = $post->id;
                $file->save();
            
        }else{ 
            return view('posts.show')->with('warning', __('You didn\'t created this post. As a result, you can\'t add files.')); 
        }

    $files = $post->files;
    return redirect()->route('files.index', compact('files', 'post'))->with('message', __('The file has been created.'));
    }


    /**
     * Show the form for editing the primary files ONLY.
     */
    public function editPrimary(Post $post)
    {

        $state ="update";
        $user = Auth::user();
        if($user->id == $post->user_id){
            return view('files.primary-edit', compact('state', 'post'));
        }
    }

    /**
     * Update the primary files ONLY. 
     */
    public function updatePrimary(FilePrimaryUpdateRequest $request, Post $post)
    {
        if(Auth::user()->id == $post->user_id){
            if(($post->dark_version) == 1 and (!$request->has('file_dark'))){
                $files = $post->files;
                return redirect()->route('files.index', compact('files', 'post'))->with('warning', __('The post has a dark version but you didn\'t provided dark file. Please retry providing a dark version.'));
            }else{
            /*Prepare the values*/
                $folder = ''.$post->level->slug.'/'.$post->course->slug.'';

                /*For the light file*/
                $filename_path_light = ''.$post->id.'-'.$post->slug.'.light.pdf';
                /*save the file*/
                $path_light = $request->file_light->storeAs($folder, $filename_path_light, 'public');

                //Update the thumbnail
                $filename_path_thumbnail = ''.$post->id.'-'.$post->slug.'.thumbnail.png';
                $delete = Storage::disk('public')->delete(''.$post->level->slug.'/'.$post->course->slug.'/'.$post->id.'-'.$post->slug.'.thumbnail.png');
                dispatch(new CreateThumbnail($path_light, $filename_path_thumbnail, $folder ));

                /*for the dark file*/
                if($post->dark_version){
                $filename_path_dark = ''.$post->id.'-'.$post->slug.'.dark.pdf';
                /*save the file*/
                $path_dark = $request->file_dark->storeAs($folder, $filename_path_dark, 'public');

                }

                /*Create the Event*/
                $event = new Event;
                $event->type = $request->update_type;
                $event->content = $request->update_content;
                $event->post_id = $post->id;
                $event->save();

                $files = $post->files;
                return redirect()->route('files.index', compact('files', 'post'))->with('message', __('The primary file(s) has been updated.'));
            }
            
        }else{ 
            return view('posts.show')->with('warning', __('You didn\'t created this post. As a result, you can\'t add files.')); 
        }
    }

    /**
     * Remove the from filesystem and database, but not the primary files
     */
    public function destroy(Post $post, File $file)
    { 
            if(Auth::user()->id == $file->post->user_id){
                if(!str_contains($file->type, 'primary')){

                    $delete = Storage::disk('public')->delete($file->file_path);
                    $file->delete();
                    $files = $post->files;
                    return redirect()->route('files.index', compact('files', 'post'))->with('message', __('The file has been deleted.'));
                }else{
                    return redirect()->route('files.index')->with('warning', __('You can\'t delete primary file.'));
                }
            }
        }

    }
