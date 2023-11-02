<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class FilesShow extends Component
{
    public $post;
    public $files;
    public function __construct($post, $files)
    {
        //
        $this->post=$post;
        $this->files=$files;
    }
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('components.files-show');
    }
}