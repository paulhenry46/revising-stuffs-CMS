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

    private function putFileInDB(string $type, string $name, string $path, int $id){
                $file = new File;
                $file->type = $type;
                $file->name = $name;
                $file->file_path = $path;
                $file->post_id = $id;
                $file->save();
    }

    private function createThumbnail(string $path, string $folder, Post $post){
        $filename_thumbnail = ''.$post->id.'-'.$post->slug.'.thumbnail.png';
        dispatch(new CreateThumbnail($path, $filename_thumbnail, $folder ));
    }

    private function createEvent($type, string $content, Post $post){
                $event = new Event;
                $event->type = $type;
                $event->content = $content;
                $event->post_id = $post->id;
                $event->save();
    }

    public function handleRequest(Request $request, Post $post){
        $this->authorize('create', [File::class, $post]);

        if($request->file_type == 'pdf'){
            return $this->processPdf($request, $post);
        }elseif($request->file_type == 'image'){
            return $this->processImage($request, $post);
            
        }
    }

    private function processPdf(Request $request, Post $post){
        if(($post->dark_version) == 1 and (!$request->has('file_dark'))){
            $files = $post->files;
            return redirect()->route('files.index', compact('files', 'post'))->with('warning', __('The post has a dark version but you didn\'t provided dark file. Please retry providing a dark version.'));
        }else{

            $fileDatas = $this->storePdf($request, 'light', $post);
            if($request->op_type == 'create'){
                $this->putFileInDB('primary light', $fileDatas['fileName'], $fileDatas['path'], $post->id);//Put the file in DB
            }
            $this->createThumbnail($fileDatas['path'], $fileDatas['folder'], $post);//Create Thumbnail
            
            if($post->dark_version){
                $fileDatas = $this->storePdf($request, 'dark', $post); // Get the file from request and store it
                if($request->op_type == 'create'){
                    $this->putFileInDB('primary dark', $fileDatas['fileName'], $fileDatas['path'], $post->id);//Put the file in DB
                }
            }

            if($request->op_type == 'update'){
                $this->createEvent($request->update_type, $request->update_content, $post);
            }
            $files = $post->files;
            $request->session()->flush();
            return redirect()->route('files.index', compact('files', 'post'))->with('message', __('The primary file(s) has been created.'));
        }
    }

    private function storePdf(Request $request, string $type, Post $post){
        $PdfDatas['folder'] = ''.$post->level->slug.'/'.$post->course->slug.'';
        $PdfDatas['fileName'] = ''.$post->id.'-'.$post->slug.'.'.$type.'.pdf';
        /*save the file*/
        if($type == 'light'){
            $PdfDatas['path'] = $request->file_light->storeAs($PdfDatas['folder'], $PdfDatas['fileName'], 'public');
        }elseif($type == 'dark'){
            $PdfDatas['path'] = $request->file_dark->storeAs($PdfDatas['folder'], $PdfDatas['fileName'], 'public');

        }
        return $PdfDatas;

    }

    private function processImage(Request $request, Post $post){
        if($request->step == '1'){ //Step where we upload the files
            if($request->op_type == 'update'){
                session(['update_type' => $request->update_type]); //We put datas of the update sent to use them in the step 2
                session(['update_content' => $request->update_content]);
                session(['op_type' => 'update']);
            }else{
                session(['op_type' => 'create']);
            }
            return $this->storeImage($request, $post);//Store Images in the disk
        }elseif($request->step == '2'){ //Step where we recieive the images sorted by the user
            $imagesOrderedDatas = $this->sortImage($request, $post); //Sort Image according to user's choice
            $fileDatas = $this->convertImage($post, $imagesOrderedDatas);//Convert Image(s) to a PDF and return Datas about the PDF such as its folder, name, path
            if($request->session()->get('op_type') == 'create'){
                $this->putFileInDB('primary light', $fileDatas['filename'], $fileDatas['path'], $post->id);//Put the file in DB
            }
            $this->createThumbnail($fileDatas['path'], $fileDatas['folder'], $post);//Create Thumbnail
            if($request->session()->get('op_type') == 'update'){
                $this->createEvent($request->session()->get('update_type'), $request->session()->get('update_content'), $post);
            }
            $files = $post->files;
            $request->session()->flush();
            return redirect()->route('files.index', compact('files', 'post'))->with('message', __('The primary file(s) has been created.'));
        }
    }

    private function storeImage(Request $request, Post $post){
        $i=1;
        $imagesDatas =[];
        foreach ($request->file('files') as $image) {
            $name = ''.$post->id.'_Image'.$i.'';
            $image->storeAs('temp/img', $name, 'public');
            $imagesDatas[$name] = $i;
            $i++;
        }
        $request->session()->put('imagesDatas', $imagesDatas);

        return redirect()->route('files.primary.sortForm', compact('post'))->with('message', __('The file has been uploaded.'))->with('imagesDatas', $imagesDatas);
    }

    private function sortImage(Request $request, Post $post){
        $imagesOrderedDatas = [];
        $i = 1;
        $NameImage = ''.$post->id.'_Image'.$i.'';
        while($request->has($NameImage)){
            $path = Storage::path('/public/temp/img/'.$NameImage.'');
            $imagesOrderedDatas[$path] = $request->input($NameImage);
            $i++;
            $NameImage = ''.$post->id.'_Image'.$i.'';
        }
        asort($imagesOrderedDatas);
        $imagesOrderedDatas = array_flip($imagesOrderedDatas);//Sort array by order selected by user and flip in order to have the name as key.
        return $imagesOrderedDatas;
    }

    private function convertImage(Post $post, array $imagesOrderedDatas){
                /*Prepare the values*/
                $folder = ''.$post->level->slug.'/'.$post->course->slug.'';
                $fileName = ''.$post->id.'-'.$post->slug.'.light.pdf';
                $path = ''.storage_path().'/app/public/'.$folder.'/'.$fileName.'';
                $fileDatas = [
                    'folder' => $folder, 
                    'filename' => $fileName, 
                    'path' => ''.$folder.'/'.$fileName.''
                ];
                /*Convert file*/
                $converter_command = 'convert '.implode( ' ', $imagesOrderedDatas ).' -quality 100 '.$path.' 2>&1';
                $result = [];
                exec($converter_command, $result);
                if($result == []){
                    foreach($imagesOrderedDatas as $i => $path){
                        unlink($path);//Delete the images uploaded
                    }
                    return $fileDatas;
                }
    }

}