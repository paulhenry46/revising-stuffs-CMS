<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class UsersShow extends Component
{
    public $users;
    public function __construct($users)
    {
        //
        $this->users=$users;
    }
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('components.users-show');
    }
}