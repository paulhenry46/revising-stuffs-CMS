<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CommentCreateRequest;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use App\Notifications\NewComment;

class CommentController extends Controller
{
    /**
     * Display all comments who belong to the posts created by a user
     */
    public function index()
    {   
        $arr = Post::where('user_id', Auth::id())->pluck('id')->toArray();
        $comments = Comment::whereIn('post_id', $arr)->where('validated', '=', 1)->get();

        return view('comments.index')->with('comments', $comments);
    }

//Display all comments wich need to be validated
    public function moderate()
    {   $comments = Comment::where('validated', '=', 0)->get();
        return view('comments.moderate')->with('comments', $comments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CommentCreateRequest $request, string $slug, Post $post)
    {
        $comment = new Comment;
        if (Auth::check()) {
            $comment->user_id = Auth::id();
            
            if(Auth::user()->hasRole('contributor')){
                    $comment->validated = true;
                    $message = 'Your comment has been created.';
            }else{
                    $comment->validated = false;
                    $message = 'Your comment has been created. It will be visible once approved by a moderator.';
                }

        }else{
            $comment->user_id = 1;
            $comment->pseudo = $request->pseudo;
            $comment->validated = false;
            $message = 'Your comment has been created. It will be visible once approved by a moderator.';
        }
        $comment->content = $request->content;
        $comment->type = $request->type;
        $comment->post_id = $post->id;
        $comment->save();
        if($comment->validated){
            $post->user->notify(new NewComment($comment));
        }
        return redirect()->route('post.public.view', [$post->slug, $post->id])->with('message', $message);
    }
}
