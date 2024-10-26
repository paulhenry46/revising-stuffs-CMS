<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;
use App\Models\User;

class PostsUserCount extends Component
{
    public $posts;
    public $count;
    public User $user;

    public function render()
    {
        return view('livewire.count.user.posts-user-count');
    }
    public function mount(User $user)
    {
        $this->user = $user;
        $this->count = $this->user->posts()->count();
    }

    public function placeholder()
    {
        return <<<'HTML'
        <div>
            <span class="text-secondary loading loading-spinner loading-sm"></span>
        </div>
        HTML;
    }
}
