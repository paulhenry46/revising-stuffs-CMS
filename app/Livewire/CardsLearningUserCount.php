<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Card;
use App\Models\User;

class CardsLearningUserCount extends Component
{
    
    public User $user;
    public $count;
    public $posts;
    public function render()
    {
        return view('livewire.count.user.cards-learning-user-count');
    }

    public function mount(User $user)
    {
        $this->user = $user;
        $this->posts = $this->user->steps()->where('next_step', '!=', null)->where('mastery', '<', '1')->pluck('post_id')->toArray();
        $this->count = Card::whereIn('post_id', $this->posts)->count();
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