<?php

namespace App\Notifications;

use App\Models\Curriculum;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WPageValidated extends Notification implements ShouldQueue
{
    use Queueable;
    public $curi;

    /**
     * Create a new notification instance.
     */
    public function __construct(Curriculum $curi)
    {
        $this->curi = $curi;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('A new welcome page for the curiculum :' . $this->curi->name)
                    ->line('You can now see it when you visit the subdomain associated to the curriculum !');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'curi_id' => $this->curi->id,
            'type' => 'WPageValidated',
        ];
    }
}
