<?php

namespace App\Http\Controllers\Public;
use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class ReadCardController extends Controller
{
    /**
     * Display the cards in quiz mode
     */
    public function quiz(string $slug, Post $post)
    {
        $this->authorize('view', $post);
        $deck = $post->decks->first();
        $cards = $deck->cards->toJSON();
        return view('cards.quiz-public', compact('post', 'cards', 'deck'));
        
    }
    /**
     * Display the cards in learn mode
     */
    public function learn(string $slug, Post $post)
    {
        $this->authorize('view', $post);
        $deck = $post->decks->first();
        $cards = $deck->cards->toJSON();
        return view('cards.learn-public', compact('post', 'cards', 'deck'));
    }
    /**
     * Display the cards in normal mode
     */
    public function show(string $slug, Post $post)
    {
        $this->authorize('view', $post);
        $deck = $post->decks->first();
        $cards = $deck->cards;
        $user = Auth::user();
        
        return view('cards.show-public', compact('post', 'cards', 'deck', 'user'));
    }

}
