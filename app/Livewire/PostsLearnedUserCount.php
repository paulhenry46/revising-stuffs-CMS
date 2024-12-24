<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Card;
use App\Models\User;

class PostsLearnedUserCount extends Component
{
    
    public User $user;
    public $count;
    public function render()
    {
        return view('livewire.count.user.posts-learned-user-count');
    }

    public function mount(User $user)
    {
        $this->user = $user;
        $this->count = $this->user->steps()->where('next_step', '!=', null)->where('mastery', '>=', '1')->count();
    }

    public function placeholder()
    {
        return <<<'HTML'
        <div>
            <span class="text-accent loading loading-spinner loading-sm"></span>
        </div>
        HTML;
    }
}