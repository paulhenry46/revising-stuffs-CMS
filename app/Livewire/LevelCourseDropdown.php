<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Level;
use App\Models\Course;
use App\Models\Type;
class LevelCourseDropdown extends Component
{

    public $level;

    public $courses=[];
    public $course;

    public $types=[];
    public $type;

    public function mount($level, $course, $type)
    {
        $this->level = $level;
        $this->course = $course;
        $this->type = $type;
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
        return view('livewire.level-course-dropdown')
            ->withLevels(Level::orderBy('name')->get());
    }
}
