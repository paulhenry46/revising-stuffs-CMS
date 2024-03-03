<?php

namespace App\Jobs;

use App\Models\Post;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class InformUserOfNewPost implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Post $post;

    /**
     * Create a new job instance.
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $users_token = User::where('level_id', $this->post->level->id)
        ->where('fcm_token', '!=', NULL)
        ->where('courses_id', 'like', '%'.$this->post->course->id.',%')
        ->pluck('fcm_token');
        $title = __('New post of :course was created', ['course' => $this->post->course->name]);
        $body = __('It\s about :title . Tap to open', ['title' => $this->post->title]);
        //dd($users_token);
        foreach($users_token as $token){
            dispatch(new SendPushNotification($title, $body, $token ));
        }
    }
}
