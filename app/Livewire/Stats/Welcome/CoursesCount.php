<?php

namespace App\Livewire\Stats\Welcome;

use App\Models\Course;
use Livewire\Component;

class CoursesCount extends Component
{
    public $count;
    public function render()
    {
        return view('livewire.stats.welcome.courses-count');
    }

    public function mount(){
        $this->count = Course::all()->count();
    }
}
