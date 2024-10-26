<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class ContributorsCount extends Component
{
    public $count;
    public function render()
    {
        return view('livewire.count.welcome.contributors-count');
    }

    public function mount(){
        $this->count = User::has('posts', '>=', 1)->get()->count();
    }

}
