<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;
use App\Models\Event;
use App\Models\File;
use Livewire\WithFileUploads;
use Mary\Traits\WithMediaSync;
use Mary\Traits\Toast;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Str;
use App\Jobs\CreateThumbnail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Rule;

class EditPrimaryFiles extends Component
{
    use WithFileUploads, WithMediaSync, Toast;
    private string $storage_subpath = '';
    private string $visibility = 'public';
    private string $disk = 'public';
    public Post $post;//The post in wich we add primary files (PF)
    #[Locked]
    public bool $update = false;//Variable to set if we update or create the PF
    #[Locked]
    public bool $dark_version;//Variable to set if a dark version of PF is needeed

    // Images files for mary-ui library component
    public array $light_image_files = [];
    public array $dark_image_files = [];

    // Metadata of image files for mary-ui library component
    public Collection $light_image_library;
    public Collection $dark_image_library;

    //PDF files for mary-ui file-input component
    public $light_file;
    public $dark_file;

     //Update Content
     public $update_content;
     public $update_type = 'bugfix';

    public function mount(Post $post, string $state){
        //Intialise metadadas of Libray component
        $this->light_image_library = new Collection();
        $this->dark_image_library = new Collection();
        //Get Post
        $this->post = $post;
        $this->storage_subpath = ''.$post->level->slug.'/'.$post->course->slug.'';
        $this->dark_version = $post->dark_version;
        if($state == 'update'){
            $this->update = true; //If the state is update, we set the variable, else, we don't do anything because it is already set to false
        }
    }

    public function render()
    {
        return view('livewire.edit-primary-files');
    }

    public function rules()
    {
        $rules = [
        'light_file' => 'nullable|required_without:light_image_files|mimes:pdf', 
        'light_file.*' => 'required_without:light_image_files|mimes:pdf', 
        'light_image_files.*' => 'required_without:light_file|image', 
        'light_image_files' => 'required_without:light_file',
        'light_image_library' => 'required_without:light_file'
        ];

        if($this->dark_version){
            $dark_file_validation = [
                'dark_file' => 'nullable|mimes:pdf|required_without:dark_image_files',
                'dark_image_files' => 'required_without:dark_file',
                'dark_file.*' => 'mimes:pdf|required_without:dark_image_files',
                'dark_image_files.*' => 'image|required_without:dark_file',
                'dark_image_library' => ''
                ];
                foreach($dark_file_validation as $key => $value ){
                    $rules[$key] = $value;
                }

        }

        if($this->update){
            $update_validation = [
                'update_content' => 'required|min:5',
                'update_type' => 'required|nullable|in:bugfix,news'
                ];
                foreach($update_validation as $key => $value ){
                    $rules[$key] = $value;
                }
        }
        return $rules;
    }


    public function save(){
        Log::debug('An informational message.1');
       // $this->validate();
       $validator = Validator::make(
        // Data to validate...
        ['light_file' => $this->light_file, 
        'light_image_files' => $this->light_image_files,
        'dark_file' => $this->dark_file, 
        'dark_image_files' => $this->dark_image_files,
        'update_content' => $this->update_content,
        'update_type' => $this->update_type,
        'light_image_library' => $this->light_image_library,
        'dark_image_library' => $this->dark_image_library,
        ''
        ],
        // Validation rules to apply...
        $this->rules(),

       );

       if (!$validator->fails())
       {
         //Process Light Files/images
         if(empty($this->light_image_files)){
            $light_data = $this->processNumericFile('light_file', 'light');
        }else{
            $light_data = $this->processHandWrittenFiles($this->light_image_files, $this->light_image_library);
        }
        $this->createThumbnail($light_data);

        //Process Dark Files/images
        if($this->dark_version){
            if(empty($this->light_image_files)){
                $dark_data = $this->processNumericFile('dark_file', 'dark');
            }else{
                $dark_data = $this->processHandWrittenFiles($this->dark_image_files, $this->dark_image_library);
            }
        }

        if($this->update){
           $this->createEvent();//If there is an update, we create the Event associated
        }else{
            $this->createFile($light_data, 'primary light');//Else, we create the files in BDD
            if($this->dark_version){
                $this->createFile($dark_data, 'primary dark');
            }
        }
        $this->success(__('Primary File(s) Updated/Created'), redirectTo: '/posts', timeout: 100000,);
       }else{
        $this->error($validator->errors(), timeout: 10000,);
       }
    }

    private function processHandWrittenFiles($files, Collection $metadata){
        //Process images file, convert it and return the path of the final file. Then delete the temporary files

        foreach ($files as $index => $file) {
            $media = $metadata->get($index);
            $name = $this->getFileName($media);

            $file = Storage::disk($this->disk)->putFileAs('/temp/img', $file, $name, $this->visibility);

            // Update the metadatas
            $media['path'] = str('/temp/img')->finish('/')->append($name)->toString();
            $metadata = $metadata->replace([$index => $media]);
        }

        $images_to_convert_path = [];
        foreach($metadata as $item){
            array_push($images_to_convert_path, Storage::path('/public'.$item['path'].''));
        }
                $folder = ''.$this->post->level->slug.'/'.$this->post->course->slug.'';
                $fileName = ''.$this->post->id.'-'.$this->post->slug.'.light.pdf';
                $path = ''.storage_path().'/app/public/'.$folder.'/'.$fileName.'';
                $fileDatas = [
                    'folder' => $folder, 
                    'filename' => $fileName, 
                    'path' => ''.$folder.'/'.$fileName.''
                ];
                /*Convert file*/
                $converter_command = 'convert '.implode( ' ', $images_to_convert_path ).' -quality 100 '.$path.' 2>&1';
                $result = [];
                exec($converter_command, $result);
                if($result == []){
                    foreach($images_to_convert_path as $i => $path){
                        unlink($path);//Delete the images uploaded
                    }
                    return $fileDatas;
                }else{
                    dd($result);
                }
    }

    private function processNumericFile($file, string $type){
        //Process a file and return its datas (paths, folder, name)

        $PdfDatas['folder'] = ''.$this->post->level->slug.'/'.$this->post->course->slug.'';
        $PdfDatas['filename'] = ''.$this->post->id.'-'.$this->post->slug.'.'.$type.'.pdf';
        /*save the file*/
        //dd($this->$file);
        $PdfDatas['path'] = $this->$file->storeAs($PdfDatas['folder'], $PdfDatas['filename'], 'public');
        return $PdfDatas;
    }

    private function createThumbnail(array $datas){
        $filename_thumbnail = ''.$this->post->id.'-'.$this->post->slug.'.thumbnail.png';
        dispatch(new CreateThumbnail($datas['path'], $filename_thumbnail, $datas['folder'] ));
    }

    private function createEvent(){
        $event = new Event;
        $event->type = $this->update_type;
        $event->content = $this->update_content;
        $event->post_id = $this->post->id;
        $event->save();
    }

    private function createFile(array $datas, string $type){
        $file = new File;
        $file->type = $type;
        $file->name = $datas['filename'];
        $file->file_path = $datas['path'];
        $file->post_id = $this->post->id;
        $file->save();
    }
}
