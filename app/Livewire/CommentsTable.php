<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Comment;
use Livewire\WithPagination;
use App\Notifications\NewComment;

class CommentsTable extends Component
{
    use WithPagination;

    public array $selection = [];

    private function scopedCommentQuery()
    {
        $user = auth()->user();
        $query = Comment::where('validated', 0);
        if ($user->hasRole('co-admin') && !$user->hasRole('admin')) {
            $curriculaIds = $user->getManagedCurriculaIds();
            $query->whereHas('post.level', function ($q) use ($curriculaIds) {
                $q->whereIn('curriculum_id', $curriculaIds);
            });
        }
        return $query;
    }

    public function deleteComments(array $ids){
        $this->scopedCommentQuery()->whereIn('id', $ids)->delete();
        $this->selection = [];
        $this->resetPage();
        session()->flash('message', __('The comment has been deleted.'));
    }

    public function validateComments(array $ids){
        $comments = $this->scopedCommentQuery()->whereIn('id', $ids)->get();
        $this->scopedCommentQuery()->whereIn('id', $ids)->update(['validated' => 1]);
        foreach($comments as $comment){
            $comment->post->user->notify(new NewComment($comment));
        }
        $this->selection = [];
        $this->resetPage();
        session()->flash('message', __('The comment has been validated.'));
    }

    public function deleteComment($id){
        $this->scopedCommentQuery()->where('id', $id)->delete();
        $this->selection = [];
        $this->resetPage();
        session()->flash('message', __('The comment has been deleted.'));
    }

    public function validateComment($id){
        $comment = $this->scopedCommentQuery()->where('id', $id)->first();
        $this->scopedCommentQuery()->where('id', $id)->update(['validated' => 1]);
        if ($comment) {
            $comment->post->user->notify(new NewComment($comment));
        }
        $this->selection = [];
        $this->resetPage();
        session()->flash('message', __('The comment has been validated.'));
    }

    public function deleteAllComments(){
        $this->scopedCommentQuery()->delete();
        $this->selection = [];
        $this->resetPage();
        session()->flash('message', __('All the comments been deleted.'));
    }

    public function render()
    {
        return view('livewire.comments-table', ['comments' => $this->scopedCommentQuery()->paginate(15)]);
    }
}
