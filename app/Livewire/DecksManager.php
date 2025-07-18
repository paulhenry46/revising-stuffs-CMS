<?php

namespace App\Livewire;

use App\Models\Deck;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Str;

class DecksManager extends Component
{
    public User $user;
    //public $decks;
    public Deck $deck;

    public $color;
    public $name;

    public function render()
    {
        return view('livewire.decks-manager', ['decks' => $this->user->decks]);
    }

    public function mount(User $user){
        $this->user = $user;
    }

    public function create(){
        $this->validate([ 
            'color' => 'required',
            'name' => 'required',
        ]);

        $deck = new Deck;
        $deck->name = $this->name;
        $deck->color = $this->color;
        $deck->slug = Str::slug($this->name, '-');
        $this->user->decks()->save($deck);
    }

    public function edit(){
        $this->validate([ 
            'color' => 'required',
            'name' => 'required',
        ]);
            $this->deck->name = $this->name;
            $this->deck->color = $this->color;
            $this->deck->slug = Str::slug($this->name, '-');
            $this->deck->save();
    }

    public function setDeck($id){
        $deck = $this->user->decks->where('id', $id)->first();
        if($deck){
        $this->deck = $deck;
        $this->name = $this->deck->name;
        $this->color = $this->deck->color;
        }
    }
    public function delete(){
        $this->deck->cards()->detach();
        $this->deck->delete();
    }



}
