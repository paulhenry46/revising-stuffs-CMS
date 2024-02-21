<?php

namespace App\Http\Controllers;

use App\Models\Post;
class RssController extends Controller
{
    public function posts() {

    	$posts = Post::where('published', '=', 1)->latest()->limit(10)->get();

    	return response()
    			->view("rss.posts", compact("posts"))
    			->header('Content-Type', 'application/xml');
    }
}
