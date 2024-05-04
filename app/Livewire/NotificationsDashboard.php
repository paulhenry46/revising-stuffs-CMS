<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;

class NotificationsDashboard extends Component
{
    public function deleteNotifications(array $ids){
        DatabaseNotification::whereIn('id', $ids) // and/or ->where('type', $notificationType)
        ->delete();
        $this->selection = [];
        session()->flash('message', __('The notification has been deleted.'));
    }

    public User $user;
    public array $selection = [];
    public function updating($name, $value){
    }
    public function render()
    {
        return view('livewire.notifications-dashboard', ['notifications' => $this->user->notifications]);
    }

    public function mount(User $user){
        $this->user = $user;

    }
}
