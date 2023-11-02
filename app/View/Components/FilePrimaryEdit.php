<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class FilePrimaryEdit extends Component
{
    public $post;
    public $state;
    public function __construct($post)
    {
        //
        $this->post=$post;
        $this->state=$state;

    }
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('components.files-edit');
    }
}