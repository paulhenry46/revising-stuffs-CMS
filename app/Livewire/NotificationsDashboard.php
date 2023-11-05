<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;

class NotificationsDashboard extends Component
{
    public $user;
    public $selection = [];
    public function render()
    {
        return view('livewire.notifications-dashboard', ['notifications' => $this->user->notifications]);
    }

    public function mount(User $user){
        $this->user = $user;

    }
}
