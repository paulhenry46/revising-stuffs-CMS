<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Post;

class PostFeedbackController extends Controller
{
    /**
     * Show the feedback/error-report page for a post.
     * This URL is embedded in the PDF watermark.
     */
    public function show(int $id)
    {
        $post = Post::where('id', $id)->where('published', true)->firstOrFail();

        return view('posts.feedback', compact('post'));
    }
}
