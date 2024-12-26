<?php

namespace App\Livewire\Stats\Welcome;

use App\Models\Card;
use Livewire\Component;

class CardsCount extends Component
{
    public $count;
    public function render()
    {
        return view('livewire.stats.welcome.cards-count');
    }
    public function mount(){
        $this->count = Card::all()->count();
    }
}
