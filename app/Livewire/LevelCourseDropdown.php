<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Level;
use App\Models\Course;
use App\Models\Curriculum;
use App\Models\School;
use App\Models\Type;
use Illuminate\Support\Facades\Auth;

class LevelCourseDropdown extends Component
{

    public $curriculum;
    public $level;
    

    public $courses=[];
    public $course;

    public $types=[];
    public $type;

    public function mount($level, $course, $type)
    {
        $this->level = $level;//
        $this->course = $course;
        $this->type = $type;
        if($level == null){
            $this->curriculum = Auth::user()->curriculum_id;
        }else{
            $this->curriculum = Level::findOrFail($level)->curriculum_id;
        }
    }
    public function render()
    {
        if(!empty($this->level)) {
            $this->courses = Level::where('id', $this->level)->first()->courses()->get();
        }
        if(!empty($this->course)) {
            $this->types = Type::where(function ($query) {
                $query->where('course_id', $this->course)
                      ->orWhere('course_id', 1);
            })->get();
        }
        //dd(Curriculum::findOrFail($this->curriculum)->levels()->get());
        return view('livewire.level-course-dropdown')
            ->withLevels(Curriculum::findOrFail($this->curriculum)->levels()->get());
    }
}
