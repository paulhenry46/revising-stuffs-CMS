<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\FileRequest;
use App\Http\Requests\FilePrimaryRequest;
use App\Http\Requests\FilePrimaryUpdateRequest;
use App\Http\Requests\ImagesRequest;
use App\Models\Post;
use App\Models\File;
use App\Models\Event;
use Illuminate\Support\Str;
use App\Jobs\CreateThumbnail;

use function PHPUnit\Framework\isEmpty;

class FileController extends Controller
{
    /**
     * Display a listing of the files attached to a specific ressource.
     */
    public function index(Post $post)
    {   
        $this->authorize('list', [File::class, $post]);
        $files = $post->files;
        return view('files.show', compact('files', 'post'));
    }

    /**
     * Show the form for creating a new complementary file.
     */
    public function create(Post $post)
    {
        $this->authorize('create', [File::class, $post]);
        $file = new File;
        $file->id = 0;
        $file->type = 'none';
        return view('files.edit', compact('file', 'post'));
    }
    /**
     * Show the form for creating the primary files
     */
    public function createPrimary(Post $post)
    {
        $this->authorize('create', [File::class, $post]);
        $state ="create";
        return view('files.primary-edit', compact('state', 'post'));
    }

    /**
     * Store a newly created complementary file in storage.
     */
    public function store(FileRequest $request, Post $post)
    {
        $this->authorize('create', [File::class, $post]);

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
        $files = $post->files;

        return redirect()->route('files.index', compact('files', 'post'))->with('message', __('The file has been created.'));
    }


    /**
     * Show the form for editing the primary files ONLY.
     */
    public function editPrimary(Post $post)
    {
        $this->authorize('create', [File::class, $post]);
        $state ="update";
        return view('files.primary-edit', compact('state', 'post'));
    }



    /**
     * Remove the from filesystem and database, but not the primary files
     */
    public function destroy(Post $post, File $file)
    {
        $this->authorize('delete', $file);

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