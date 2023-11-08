<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use App\Models\Comment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewComment extends Notification implements ShouldQueue
{
    use Queueable;
    public $comment;
    public $url;
    /**
     * Create a new notification instance.
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
        $this->url = route('post.public.view', ["slug" => $this->comment->post->slug, "post" => $this->comment->post->id]);
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
        if($this->comment->type == 'comment'){
            return (new MailMessage)
                    ->line('A new comment has been published on one of your post !')
                    ->action('View Post', $this->url)
                    ->line('Thank you for using our application!');
        }elseif($this->comment->type == 'error'){
            return (new MailMessage)
                    ->line('An error has been reported on one of your post !')
                    ->action('View Post', $this->url)
                    ->line('Thank you for using our application!');
        }
        
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'post_id' => $this->comment->post->id,
            'post_slug' => $this->comment->post->slug,
            'type' => $this->comment->type,
        ];
    }
}
