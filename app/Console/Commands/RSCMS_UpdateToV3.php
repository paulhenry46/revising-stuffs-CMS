<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use App\Models\Course;
use App\Models\Group;
use App\Models\Post;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;

class RSCMS_UpdateToV3 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rscms:updateToV3';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to update from a 2.x version of the app to the 3.0 version. Run once';

    /**
     * Execute the console command.
     */
    public function handle()
    {
    $school_id = $this->ask('What is the school id of the posts');
    $groups = Group::all();
    foreach($groups as $group){
        $group->school_id = $school_id;
        $group->save();
    }
    $posts = Post::all();
    $progressbar = $this->output->createProgressBar(count($posts));
    $progressbar->start();

    foreach($posts as $post){
        $post->school_id = $school_id;
        $post->save();
        if($post->level->curriculum !== null ){
        foreach($post->files as $file){
            $file->file_path = ''.$post->level->curriculum->slug.'/'.$post->level->slug.'/'.$post->course->slug.'/'.$file->name.'';
            $file->save();
        }
    }else{
        $this->fail('The level of post doesn\'t have any curriculum. Please add a curriculum for level'.$post->level->name.'');
    }
        $progressbar->advance();

    }
    $progressbar->finish();
    Permission::create(['guard_name' => 'sanctum', 'name' => 'manage curricula']);
    Permission::create(['guard_name' => 'sanctum', 'name' => 'manage schools']);
    $role = Role::findByName('admin', 'sanctum');
    $role->syncPermissions(['manage courses', 'manage levels', 'manage users', 'manage all posts', 'manage groups', 'manage curricula', 'manage schools']);
    $this->newLine();
    $this->info('The command was successful!. Now you must move manually the folder of the level inside a folder entitled with the slug of their curriculum');
    }
}
