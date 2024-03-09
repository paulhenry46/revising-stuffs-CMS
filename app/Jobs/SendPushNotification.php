<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendPushNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $title;
    protected string $body;
    protected string $user_fcm_token;

    /**
     * Create a new job instance.
     */
    public function __construct(string $title, string $body, string $user_fcm_token)
    {
        $this->title = $title;
        $this->body = $body;
        $this->user_fcm_token = $user_fcm_token;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $credentialsFilePath = base_path("fcm.json");
        $client = new \Google_Client();
        $client->setAuthConfig($credentialsFilePath);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        $apiurl = 'https://fcm.googleapis.com/v1/projects/'.env('FCM_projectId').'/messages:send';
        $client->refreshTokenWithAssertion();
        $token = $client->getAccessToken();
        $access_token = $token['access_token'];
        
        $headers = [
             "Authorization: Bearer $access_token",
             'Content-Type: application/json'
        ];
        $content_data = [
            "title" => $this->title,
            "body" => $this->body
        ]; 
        
        $data['notification'] =  $content_data;
        $data['webpush']['fcm_options']['link'] = route('post.public.news');
        $data['token'] = $this->user_fcm_token;
    
        $payload['message'] = $data;
        $payload = json_encode($payload);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiurl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_exec($ch);
        curl_close($ch);
    }
}
