<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Component;

class PostsCount extends Component
{
    public $count;
    public function render()
    {
        return view('livewire.posts-count');
    }

    public function mount(){
        $this->count = Post::all()->count();
    }
}
