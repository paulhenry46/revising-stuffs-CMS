<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class CourseEdit extends Component
{
    public $course;
    public function __construct($course)
    {
        //
        $this->course=$course;
    }
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('components.courses-edit');
    }
}