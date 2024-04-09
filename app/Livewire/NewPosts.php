<?php

namespace App\Livewire;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NewPosts extends Component
{
    //public $user = auth()->user();
    public bool $restricted = false;
    public function render()
    {
        $user = auth()->user();
        return view('livewire.new-posts', [
            'posts' => Post::where('published', '=', 1)
            ->where('group_id', '!=', 1)
            ->when(Auth::check(), function ( $query) {
                $query->where(function ($query) {
                    $query->whereRelation('group', 'public', true)
                          ->orwhereIn('group_id', auth()->user()->groups->pluck('id')->toArray());
                });
            })
            ->when(Auth::check() !== true, function ( $query) {
                $query->where(function ($query) {
                    $query->whereRelation('group', 'public', true);
                });
            })

            ->when($this->restricted, function($query){
                return $query->where('level_id', auth()->user()->level_id)
                             ->whereIn('course_id', auth()->user()->courses_id);
            })
            ->orderBy('pinned', 'DESC')
            ->latest()->limit(5)->get()
        ]);
    }
}
