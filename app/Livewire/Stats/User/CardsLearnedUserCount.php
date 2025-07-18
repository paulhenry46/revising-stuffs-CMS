<?php

namespace App\Livewire\Stats\User;

use Livewire\Component;
use App\Models\Card;
use App\Models\User;

class CardsLearnedUserCount extends Component
{
    
    public User $user;
    public $count;
    public $decks;
    public function render()
    {
        return view('livewire.stats.user.cards-learned-user-count');
    }

    public function mount(User $user)
    {
        $this->user = $user;
        $this->decks = $this->user->steps()->where('next_step', '!=', null)->where('mastery', '>=', '1')->pluck('deck_id')->toArray();
        $this->count = Card::whereHas('decks', function ($query) {
            $query->whereIn('decks.id', $this->decks);
        })->count();
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