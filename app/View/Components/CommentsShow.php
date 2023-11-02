<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class CommentsShow extends Component
{
    public $comments;
    public function __construct($comments)
    {
        //
        $this->comments=$comments;
    }
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('components.comments-show');
    }
}