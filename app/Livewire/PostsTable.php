<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;
use Livewire\WithPagination;
use App\Notifications\PostValidated;
use Illuminate\Support\Facades\Storage;

class PostsTable extends Component
{
    use WithPagination;

    public array $selection = [];
    public function updating($name, $value){
    }

    public function placeholder()
    {
        return <<<'HTML'
        <div>
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">
                            <h1 class=" text-2xl font-medium text-gray-900 dark:text-white">
                                    Moderate the posts
                            </h1>
                        </div>
                        <div role="status" class=" p-4 space-y-4 border border-gray-200 divide-y divide-gray-200 rounded shadow animate-pulse dark:divide-gray-700 md:p-6 dark:border-gray-700">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="h-2.5 bg-gray-300 rounded-full dark:bg-gray-600 w-24 mb-2.5"></div>
                                    <div class="w-32 h-2 bg-gray-200 rounded-full dark:bg-gray-700"></div>
                                </div>
                                <div class="h-2.5 bg-gray-300 rounded-full dark:bg-gray-700 w-12"></div>
                            </div>
                            <div class="flex items-center justify-between pt-4">
                                <div>
                                    <div class="h-2.5 bg-gray-300 rounded-full dark:bg-gray-600 w-24 mb-2.5"></div>
                                    <div class="w-32 h-2 bg-gray-200 rounded-full dark:bg-gray-700"></div>
                                </div>
                                <div class="h-2.5 bg-gray-300 rounded-full dark:bg-gray-700 w-12"></div>
                            </div>
                            <div class="flex items-center justify-between pt-4">
                                <div>
                                    <div class="h-2.5 bg-gray-300 rounded-full dark:bg-gray-600 w-24 mb-2.5"></div>
                                    <div class="w-32 h-2 bg-gray-200 rounded-full dark:bg-gray-700"></div>
                                </div>
                                <div class="h-2.5 bg-gray-300 rounded-full dark:bg-gray-700 w-12"></div>
                            </div>
                            <div class="flex items-center justify-between pt-4">
                                <div>
                                    <div class="h-2.5 bg-gray-300 rounded-full dark:bg-gray-600 w-24 mb-2.5"></div>
                                    <div class="w-32 h-2 bg-gray-200 rounded-full dark:bg-gray-700"></div>
                                </div>
                                <div class="h-2.5 bg-gray-300 rounded-full dark:bg-gray-700 w-12"></div>
                            </div>
                            <div class="flex items-center justify-between pt-4">
                                <div>
                                    <div class="h-2.5 bg-gray-300 rounded-full dark:bg-gray-600 w-24 mb-2.5"></div>
                                    <div class="w-32 h-2 bg-gray-200 rounded-full dark:bg-gray-700"></div>
                                </div>
                                <div class="h-2.5 bg-gray-300 rounded-full dark:bg-gray-700 w-12"></div>
                            </div>
                            <span class="sr-only">Loading...</span>
                        </div>
                      </div>
                    </div>
                </div>
            </div>
        HTML;
    }
    public function destroyPost(Post $post){
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
    }
    public function deletePosts(array $ids){
        $posts = Post::whereIn('id', $ids)->get();
        foreach($posts as $post){
            $this->destroyPost($post);
        }
        $this->selection = [];
        $this->resetPage();
        session()->flash('message', __('The post has been deleted.'));
    }

    public function validatePosts(array $ids){
        Post::whereIn('id', $ids)->update(['published' => 1]);
        $posts = Post::whereIn('id', $ids)->get();
        foreach($posts as $post){
            $post->user->notify(new PostValidated($post));
        }
        $this->selection = [];
        $this->resetPage();
        session()->flash('message', __('The posts has been validated.'));
    }

    public function deletePost($id){
        $post = Post::where('id', '=', $id)->get();
        $this->destroyPost($post);
        $this->selection = [];
        $this->resetPage();
        session()->flash('message', __('The comment has been deleted.'));
    }

    public function validatePost($id){
        Post::where('id', '=', $id)->update(['published' => 1]);
        $post = Post::where('id', '=', $id)->first();
        $post->user->notify(new PostValidated($post));
        $this->selection = [];
        $this->resetPage();
        session()->flash('message', __('The comment has been validated.'));
    }

    public function render()
    {
        return view('livewire.posts-table', ['posts' => Post::where('published', 0)->paginate(15)]);
    }
}
