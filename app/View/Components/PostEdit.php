<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class PostEdit extends Component
{
    public $post;
    public $levels;
    public $courses;
    public function __construct($post)
    {
        //
        $this->post=$post;
        $this->levels=$levels;
        $this->courses=$courses;
    }
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('components.post-edit');
    }
}