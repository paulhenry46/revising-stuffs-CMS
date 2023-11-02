<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class LevelsShow extends Component
{
    public $levels;
    public function __construct($levels)
    {
        //
        $this->levels=$levels;
    }
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('components.levels-show');
    }
}