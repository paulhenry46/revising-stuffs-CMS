<?php

namespace App\Livewire\Stats\Welcome;

use App\Models\Post;
use Livewire\Component;

class PostsCount extends Component
{
    public $count;
    public function render()
    {
        return view('livewire.stats.welcome.posts-count');
    }

    public function mount(){
        $this->count = Post::all()->count();
    }
}
