<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use NotificationChannels\Telegram\TelegramMessage;
use Illuminate\Notifications\Notification;

class TelegramWebHookController extends Controller
{

    public function get(Request $request){
        $update = json_decode(file_get_contents("php://input"), TRUE);
        $chatId = $update["message"]["chat"]["id"];
        $username = $update["message"]["chat"]["username"];
        if(User::where('telegram_username', $username)->count() == 1){
            $user = User::where('telegram_username', $username)->first();
            $user->telegram_chat_id = $chatId;
            $user->save();
            return $chatId;
            /*TelegramMessage::create()
            // Optional recipient user id.
            ->to($user->telegram_user_id)
            // Markdown supported.
            ->content("Hello there!")
            ->line("Your account has been *linked*")
            ->line("Thank you!");*/
        }else{
            return $chatId;
            /*TelegramMessage::create()
            // Optional recipient user id.
            ->to($chatId)
            // Markdown supported.
            ->content("Hello there!")
            ->line("Before linking your account, please add your telegram username in your profil.")
            ->line("Thank you!");*/
        }
        //return $cards;
    }


}