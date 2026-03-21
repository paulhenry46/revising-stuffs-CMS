<?php

namespace App\Livewire;

use App\Jobs\AddWatermarkToPdf;
use App\Jobs\InformUserOfNewPost;
use Livewire\Component;
use App\Models\Level;
use App\Models\Post;
use Livewire\WithPagination;
use App\Notifications\PostValidated;
use App\Notifications\PostDeleted;
use Illuminate\Support\Facades\Storage;

class PostsTable extends Component
{
    use WithPagination;

    public array $selection = [];
    public string $reasons = '';
    public string $mass_reasons = '';
    public function updating($name, $value){
    }

    private function scopedPostQuery()
    {
        $user = auth()->user();
        $query = Post::where('published', 0)->where('group_id', '!=', 1);
        if ($user->hasRole('co-admin') && !$user->hasRole('admin')) {
            $curriculaIds = $user->getManagedCurriculaIds();
            $levelIds = Level::whereIn('curriculum_id', $curriculaIds)->pluck('id');
            $query->whereIn('level_id', $levelIds);
        }
        return $query;
    }

    private function scopedPostIds(array $ids): array
    {
        return $this->scopedPostQuery()->whereIn('id', $ids)->pluck('id')->toArray();
    }

    public function placeholder()
    {
        return <<<'HTML'
        <div>
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-linear-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">
                            <h1 class=" text-2xl font-medium text-gray-900 dark:text-white">
                                    Moderate the posts
                            </h1>
                        </div>
                        <div role="status" class=" p-4 space-y-4 border border-gray-200 divide-y divide-gray-200 rounded-sm shadow-sm animate-pulse dark:divide-gray-700 md:p-6 dark:border-gray-700">
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
     //Delete the post
         $post->delete();
    }
    public function deletePosts(array $ids){
        $safeIds = $this->scopedPostIds($ids);
        $posts = Post::whereIn('id', $safeIds)->get();
        foreach($posts as $post){
            $title = $post->title;
            $post->user->notify(new PostDeleted($title, $this->mass_reasons));
            $this->destroyPost($post);
        }
        $this->selection = [];
        $this->mass_reasons = '';
        $this->resetPage();
        session()->flash('message', __('The post has been deleted.'));
    }

    public function validatePosts(array $ids){
        $safeIds = $this->scopedPostIds($ids);
        Post::whereIn('id', $safeIds)->update(['published' => 1]);
        $posts = Post::whereIn('id', $safeIds)->get();
        foreach($posts as $post){
            $post->user->notify(new PostValidated($post));
            dispatch(new InformUserOfNewPost($post));
            foreach($post->files as $file){
                if(in_array($file->type, ['primary light', 'primary dark'])){
                    dispatch(new AddWatermarkToPdf($file->id));
                }
            }
        }
        $this->selection = [];
        $this->resetPage();
        session()->flash('message', __('The posts has been validated.'));
    }

    public function deletePost($id){
        $post = $this->scopedPostQuery()->where('id', $id)->firstOrFail();
        $title = $post->title;
        $post->user->notify(new PostDeleted($title, $this->reasons));
        $this->destroyPost($post);
        $this->selection = [];
        $this->reasons = '';
        $this->resetPage();
        session()->flash('message', __('The post has been deleted.'));
    }

    public function validatePost($id){
        $post = $this->scopedPostQuery()->where('id', $id)->first();
        $this->scopedPostQuery()->where('id', $id)->update(['published' => 1]);
        if ($post) {
            $post->user->notify(new PostValidated($post));
            dispatch(new InformUserOfNewPost($post));
            foreach($post->files as $file){
                if(in_array($file->type, ['primary light', 'primary dark'])){
                    dispatch(new AddWatermarkToPdf($file->id));
                }
            }
        }
        $this->selection = [];
        $this->resetPage();
        session()->flash('message', __('The post has been published.'));
    }

    public function render()
    {
        return view('livewire.posts-table', ['posts' => $this->scopedPostQuery()->paginate(15)]);
    }
}
