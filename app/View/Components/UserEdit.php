<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class UserEdit extends Component
{
    public $user;
    public function __construct($user)
    {
        //
        $this->user=$user;
    }
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('components.user-edit');
    }
}