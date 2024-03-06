<?php

namespace App\Http\Controllers;

use App\Jobs\InformUserOfNewPost;
use App\Models\Post;
use Illuminate\Http\Request;

class PushNotificationsController extends Controller
{
    public function updateToken(Request $request){
        try{
            $request->user()->update(['fcm_token'=>$request->currentToken]);
            return response()->json([
                'success'=>true
            ]);
        }catch(\Exception $e){
            report($e);
            return response()->json([
                'success'=>false
            ],500);
        }
    }

    public function sendPushNotification(){

        $post = Post::first();
        dispatch(new InformUserOfNewPost($post));
    }
}
