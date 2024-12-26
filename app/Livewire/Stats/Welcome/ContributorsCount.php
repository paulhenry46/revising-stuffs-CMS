<?php

namespace App\Livewire\Stats\Welcome;

use App\Models\User;
use Livewire\Component;

class ContributorsCount extends Component
{
    public $count;
    public function render()
    {
        return view('livewire.stats.welcome.contributors-count');
    }

    public function mount(){
        $this->count = User::has('posts', '>=', 1)->get()->count();
    }

}
