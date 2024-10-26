<?php

namespace App\Livewire;

use App\Models\Card;
use Livewire\Component;

class CardsCount extends Component
{
    public $count;
    public function render()
    {
        return view('livewire.count.welcome.cards-count');
    }
    public function mount(){
        $this->count = Card::all()->count();
    }
}
