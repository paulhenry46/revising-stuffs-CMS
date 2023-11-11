<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PostDeleted extends Notification implements ShouldQueue
{
    use Queueable;
    public $title;
    public $reasons;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $title, string $reasons)
    {
        $this->title = $title;
        $this->reasons = $reasons;
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
                    ->line('Unfortunately, your post "'.$this->title.'" has been rejected for this reasons : ')
                    ->line($this->reasons)
                    ->line('Thank you for contributing !');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'reasons' => $this->reasons,
            'type' => 'post_deleted',
        ];
    }
}
