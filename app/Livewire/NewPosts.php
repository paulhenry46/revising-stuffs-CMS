<?php

namespace App\Livewire;
use App\Models\Post;
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
            
            //->whereIn('type_id', $this->types)
            ->when($this->restricted, function($query){
                return $query->where('level_id', auth()->user()->level_id)
                             ->whereIn('course_id', auth()->user()->courses_id);
            })
            ->orderBy('pinned', 'DESC')
            ->latest()->limit(5)->get()
        ]);
    }
}
