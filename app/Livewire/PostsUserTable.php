<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Post;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;

class PostsUserTable extends Component
{
    use WithPagination;

    public string $search = '';

    public Post $post;
    public User $user;
    public array $selection = [];

    public function updating($name, $value){
        if($name == 'search'){
            $this->resetPage();
        }
    }

    public function deletePost($id){
        $post = Post::find($id);
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
            session()->flash('message', __('The post has been deleted.'));
    }
    

    public function mount(User $user){
    $this->user = $user;
    }

    public function render()
    {
        return view('livewire.posts-user-table', ['posts' => Post::where('user_id', '=', $this->user->id)
            ->when($this->search, function($query, $search){
                return $query->where(function ($query) {
                        $query->where('title', 'LIKE', "%{$this->search}%")
                          ->orWhere('description', 'LIKE', "%{$this->search}%");
                });})->orderBy('created_at','DESC')->paginate(15)]);
    }
}
