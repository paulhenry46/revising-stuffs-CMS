<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Post;

class PostValidated extends Notification implements ShouldQueue
{
    use Queueable;
    public $post;
    public $url;

    /**
     * Create a new notification instance.
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
        $this->url = route('post.public.view', ["slug" => $this->post->slug, "post" => $this->post->id]);
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
                    ->line('Your post has been validated ! Now, it is visible to everybody !')
                    ->action('View Post', $this->url)
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
            'post_id' => $this->post->id,
            'post_slug' => $this->post->slug,
            'type' => 'Postvalidated',
        ];
    }
}
