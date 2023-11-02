<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class PostView extends Component
{
    public $post;
    public $comments;
    public $events;
    public $files;
    public function __construct($post, $comments, $events, $files)
    {
        //
        $this->post=$post;
        $this->comments=$comments;
        $this->events=$events;
        $this->files=$files;
    }
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('components.post-view');
    }
}