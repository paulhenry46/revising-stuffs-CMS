<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class CoursesShow extends Component
{
    public $courses;
    public function __construct($courses)
    {
        //
        $this->courses=$courses;
    }
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('components.courses-show');
    }
}