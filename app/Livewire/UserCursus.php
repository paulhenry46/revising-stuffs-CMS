<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Level;
use App\Models\Course;
use App\Models\User;
class UserCursus extends Component
{

    public $level;
    public $user;
    public $courses=[];
    public $course;

    public function mount(User $user)
    {
        $this->level = $user->level_id;
        $this->user = $user;
    }
    public function render()
    {
        if(!empty($this->level)) {
            $this->courses = Level::where('id', $this->level)->first()->courses()->get();
        }
        return view('livewire.user-cursus')
            ->withLevels(Level::orderBy('name')->get());
    }
}
