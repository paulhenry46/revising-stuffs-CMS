<?php

namespace App\Livewire;

use App\Models\Course;
use Livewire\Component;

class CoursesCount extends Component
{
    public $count;
    public function render()
    {
        return view('livewire.courses-count');
    }

    public function mount(){
        $this->count = Course::all()->count();
    }
}
