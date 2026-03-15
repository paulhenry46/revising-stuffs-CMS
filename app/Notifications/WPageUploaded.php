<?php

namespace App\Notifications;

use App\Models\Curriculum;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WPageUploaded extends Notification implements ShouldQueue
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
                    ->line('it is not shown until you validate it ! ');
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
            'type' => 'WPageUploaded',
        ];
    }
}
