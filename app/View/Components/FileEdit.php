<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class FileEdit extends Component
{
    public $post;
    public $file;
    public function __construct($post, $files)
    {
        //
        $this->post=$post;
        $this->file=$file;
    }
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('components.files-edit');
    }
}