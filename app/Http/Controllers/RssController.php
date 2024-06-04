<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
class RssController extends Controller
{
    public function posts() {

    	$posts = Post::where('published', '=', 1)->whereRelation('group', 'public', true)->where('group_id', '!=', 1)->latest()->limit(10)->get();

    	if ($posts->first()) {
			return response()
    			->view("rss.posts", compact("posts"))
    			->header('Content-Type', 'application/xml');
		 }else{
			return __('No posts for now');
		 }
    }

	public function user(User $user) {

    	$posts = Post::where('published', '=', 1)
		->whereRelation('group', 'public', true)
		->where('group_id', '!=', 1)
		->where('level_id', $user->level_id)
		->where('school_id', $user->school_id)
        ->whereIn('course_id', $user->courses_id)
		->latest()
		->limit(10)
		->get();
		if ($posts->first()) {
			return response()
    			->view("rss.posts", compact("posts"))
    			->header('Content-Type', 'application/xml');
		 }else{
			return __('No posts for now');
		 }
    	
    }
}
