<?php

namespace App\Livewire;

use App\Models\Card;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Locked;

class DeckCardSelector extends Component
{
    public Card $card;
    public User $user;

    #[Locked]
    public $decks;
    public $decks_contains_card = [];

    public function render()
    {
        return view('livewire.deck-card-selector');
    }
    public function mount(Card $card, User $user){
        $this->card = $card;
        $this->user = $user;
        
        $id_decks_containing_card = $this->card->decks->pluck('id')->toArray();

        $this->decks = $user->decks;
        foreach ($this->decks as $deck) {
            $this->decks_contains_card[$deck->id] = in_array($deck->id, $id_decks_containing_card);
        }
    }

    public function change_state($id){
        $deck = $this->decks->firstWhere('id', $id);
        if ($deck) {
            if ($this->decks_contains_card[$id]) {
                $deck->cards()->detach($this->card->id);
                $this->decks_contains_card[$id] = false;
            } else {
                $deck->cards()->attach($this->card->id);
                $this->decks_contains_card[$id] = true;
            }
        }
    }
}
