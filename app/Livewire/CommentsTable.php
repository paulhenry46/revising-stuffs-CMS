<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Comment;
use App\Models\Post;
use Livewire\WithPagination;
use App\Notifications\NewComment;

class CommentsTable extends Component
{
    use WithPagination;

    public array $selection = [];

    public function deleteComments(array $ids){
        Comment::whereIn('id', $ids)->delete();
        $this->selection = [];
        $this->resetPage();
        session()->flash('message', __('The comment has been deleted.'));
    }

    public function validateComments(array $ids){
        Comment::whereIn('id', $ids)->update(['validated' => 1]);
        $comments = Comment::whereIn('id', $ids)->get();
        foreach($comments as $comment){
            $comment->post->user->notify(new NewComment($comment));
        }
        $this->selection = [];
        $this->resetPage();
        session()->flash('message', __('The comment has been validated.'));
    }

    public function deleteComment($id){
        Comment::where('id', '=', $id)->delete();
        $this->selection = [];
        $this->resetPage();
        session()->flash('message', __('The comment has been deleted.'));
    }

    public function validateComment($id){
        Comment::where('id', '=', $id)->update(['validated' => 1]);
        $comment = Comment::where('id', '=', $id)->first();
        $comment->post->user->notify(new NewComment($comment));
        $this->selection = [];
        $this->resetPage();
        session()->flash('message', __('The comment has been validated.'));
    }

    public function deleteAllComments(){
        Comment::where('validated', 0)->delete();
        $this->selection = [];
        $this->resetPage();
        session()->flash('message', __('All the comments been deleted.'));
    }

    public function render()
    {
        return view('livewire.comments-table', ['comments' => Comment::where('validated', 0)->paginate(15)]);
    }
}
