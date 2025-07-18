<?php

namespace App\Http\Controllers\Public;
use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Post;

class ReadCardController extends Controller
{
    /**
     * Display the cards in quiz mode
     */
    public function quiz(string $slug, Post $post)
    {
        $this->authorize('view', $post);
        $cards = $post->decks->first()->cards->toJSON();
        return view('cards.quiz-public', compact('post', 'cards'));
        
    }
    /**
     * Display the cards in learn mode
     */
    public function learn(string $slug, Post $post)
    {
        $this->authorize('view', $post);

        $cards = $post->decks->first()->cards->toJSON();
        return view('cards.learn-public', compact('post', 'cards'));
    }
    /**
     * Display the cards in normal mode
     */
    public function show(string $slug, Post $post)
    {
        $this->authorize('view', $post);
        
        $cards = $post->decks->first()->cards;
        
        return view('cards.show-public', compact('post', 'cards'));
    }

}
