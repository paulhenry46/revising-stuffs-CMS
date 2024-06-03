<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Level;
use App\Models\Course;
use App\Models\Curriculum;
use App\Models\School;
use App\Models\User;
class UserCursus extends Component
{

    
    public $school;
    public $schools;
    public $curriculum;
    public $curricula=[];
    public User $user;
    public $levels=[];
    public $level;
    public $courses=[];
    public $course;

    public function mount(User $user)
    {
        $this->level = $user->level_id;
        $this->school = $user->school_id;
        $this->curriculum = $user->curriculum_id;
        $this->user = $user;
        $this->schools = School::orderBy('name')->get();
    }
    public function render()
    {
        if(!empty($this->school)) {
            $this->curricula = School::where('id', $this->school)->first()->curricula()->get();
        }
        if(!empty($this->curriculum)) {
            $this->levels = Curriculum::where('id', $this->curriculum)->first()->levels()->get();
        }
        if(!empty($this->level)) {
            $this->courses = Level::where('id', $this->level)->first()->courses()->get();
        }
        
        return view('livewire.user-cursus');
    }
}
