<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Card;
use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class CardController extends Controller
{
    /**
     * Used for chose the html tags which need to be escaped when importing/creating cards
     */
    private array $forbiden_tags =[
        '<div>', '</div>', 
        '<script>', '</script>', 
        '<link>', '</link>', 
        '<a>', '</a>', 
        '<td>', '</td>', 
        '<th>', '</th>', 
        '<iframe>', '</iframe>'
    ];
    /**
     * Display a listing of the resource.
     */
    public function quizPublic(string $slug, Post $post){
        $cards = Card::where('post_id', '=', $post->id)
        ->get()
        //->pluck('front', 'back')
        ->toJSON();
        return view('cards.quiz-public', compact('post', 'cards'));
        //return $cards;
    }

    public function learnPublic(string $slug, Post $post){
        $cards = Card::where('post_id', '=', $post->id)
        ->get()
        //->pluck('front', 'back')
        ->toJSON();
        return view('cards.learn-public', compact('post', 'cards'));
        //return $cards;
    }

    public function showPublic(string $slug, Post $post){
        $cards = Card::where('post_id', '=', $post->id)
        ->get();
        return view('cards.show-public', compact('post', 'cards'));
    }

    public function index(Post $post)
    {   
        $user = Auth::user();
        if($user->id == $post->user_id){
            //$cards = $post->cards->paginate(15);
            //$cards = Card::where('post_id', '=', $post->id)->paginate(15);
            return view('cards.show', compact(/*'cards', */'post'));
        }else{ 
            return redirect()->route('posts.index')->with('warning', __('You didn\'t created this post. As a result, you can\'t view its cards.')); 
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Post $post)
    {
        $user = Auth::user();
        if($user->id == $post->user_id){
            $card = new Card;
            $card->id = 0;
            return view('cards.edit', compact('card', 'post'));
        }else{ 
            return redirect()->route('posts.index')->with('warning', __('You didn\'t created this post. As a result, you can\'t view its cards.')); 
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Post $post)
    {
        $user = Auth::user();
        if($user->id == $post->user_id){
             $validated = $request->validate([
                'front' => 'required|max:255',
                'back' => 'required|max:255',
            ]);
                
                $front = str_ireplace($this->forbiden_tags, '', $request->front);
                $back = str_ireplace($this->forbiden_tags, '', $request->back);

            $card = New Card;
            $card->front = $front;
            $card->back = $back;
            $card->post_id = $post->id;
            $card->save();
            if(!$post->cards){
                $post->cards = true;
                $post->save();
            }
            return redirect()->route('cards.index', $post->id)->with('message', __('The card has been imported.'));
        }else{ 
            return redirect()->route('posts.index')->with('warning', __('You didn\'t created this post. As a result, you can\'t view its cards.')); 
        }
    }

    public function import(Post $post)
    {
        $user = Auth::user();
        if($user->id == $post->user_id){
            return view('cards.import', compact('post'));
        }else{ 
            return redirect()->route('posts.index')->with('warning', __('You didn\'t created this post. As a result, you can\'t view its cards.')); 
        }

    }

    public function storeImport(Request $request, Post $post)
    {
        $input = $request->content;
        $separator = "/\t+/";
        if($request->separator_cards == 'egual'){
            $separator = "/=+/";
        }elseif($request->separator_cards == 'semicolon'){
            $separator = "/;+/";
        }
        $cols = ["front","back", "id", "created_at", "updated_at"];
        $output = [];

        foreach (preg_split("/((\r?\n)|(\r\n?))/", $input) as $line){
            $newLine = [];
            $values = preg_split($separator, $line);
            foreach ($values as $col_index => $value) {
                $value = str_ireplace($this->forbiden_tags, '', $value);
                $newLine[$cols[$col_index]] = $value;
            }
            $newLine['post_id'] = $post->id;
            $newLine['updated_at'] = now();
            $newLine['created_at'] = now();
            $output[] = $newLine;
        }
        Card::insert($output);
        if(!$post->cards){
                $post->cards = true;
                $post->save();
            }
        return redirect()->route('cards.index', $post->id)->with('message', __('The cards has been imported.'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post, Card $card)
    {
        $user = Auth::user();
        if($user->id == $card->post->user_id){
            return view('cards.edit', compact('card', 'post'));
        }else{ 
            return redirect()->route('posts.index')->with('warning', __('You didn\'t created this post. As a result, you can\'t view its cards.')); 
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post, Card $card)
    {
        $user = Auth::user();
        if($user->id == $card->post->user_id){
             $validated = $request->validate([
                'front' => 'required|max:255',
                'back' => 'required|max:255',
            ]);
            $card->front = str_ireplace($this->forbiden_tags, '', $request->front);
            $card->back = str_ireplace($this->forbiden_tags, '', $request->back);
            $card->save();
            return redirect()->route('cards.index', $post->id)->with('message', __('The card has been updated.'));
        }else{ 
            return redirect()->route('posts.index')->with('warning', __('You didn\'t created this post. As a result, you can\'t edit its cards.')); 
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post, Card $card)
    {   $user = Auth::user();
        if($user->id == $card->post->user_id){
            $card->delete();
            if(Card::where('post_id', '=', $card->post->id)->count() == 0){
                $post->cards = false;
                $post->save();
            }
            return redirect()->route('cards.index', $post->id)->with('message', __('The card has been deleted.'));
        }else{ 
            return redirect()->route('posts.index')->with('warning', __('You didn\'t created this post. As a result, you can\'t edit its cards.')); 
        }

    }
}
