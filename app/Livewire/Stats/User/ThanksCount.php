<?php

namespace App\Livewire\Stats\User;

use Livewire\Component;
use App\Models\Post;
use App\Models\User;

class ThanksCount extends Component
{
    public $posts;
    public $count;
    public User $user;

    public function render()
    {
        return view('livewire.stats.user.thanks-count');
    }

    public function mount(User $user)
    {
        $this->user = $user;
        $this->posts = $this->user->posts;
        $this->count = 0;
        foreach($this->posts as $post){
            $this->count =  $this->count + $post->likes_count;
        }
    }

    public function placeholder()
    {
        return <<<'HTML'
        <div>
            <span class="text-primary loading loading-spinner loading-sm"></span>
        </div>
        HTML;
    }

}
