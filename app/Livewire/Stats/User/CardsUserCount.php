<?php

namespace App\Livewire\Stats\User;

use Livewire\Component;
use App\Models\Card;
use App\Models\Deck;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class CardsUserCount extends Component
{
    
    public User $user;
    public $count;
    public $posts;
    public function render()
    {
        return view('livewire.stats.user.cards-user-count');
    }

    public function mount(User $user)
    {
        $this->user = $user;
        $this->posts = $this->user->posts()->pluck('id')->toArray();


        $decks = Deck::whereHasMorph('deckable', Post::class,
            function (Builder $query) {
                $query->whereIn('id', $this->posts);
            })->pluck('id');

        $this->count = Card::whereIn('deck_id', $decks)->count();

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
