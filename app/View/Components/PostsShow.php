<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class PostsShow extends Component
{
    public $posts;
    public function __construct($posts)
    {
        //
        $this->posts=$posts;
    }
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('components.posts-show');
    }
}