<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Card;
use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CardCreateRequest;
use App\Http\Requests\CardEditRequest;
use App\Http\Requests\CardImportRequest;

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

    public function index(Post $post)
    {   
        $user = Auth::user();
        if($user->id == $post->user_id){
            return view('cards.show', compact('post'));
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
    public function store(CardCreateRequest $request, Post $post)
    {
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
        return redirect()->route('cards.index', $post->id)->with('message', __('The card has been created.'));
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

    public function storeImport(CardImportRequest $request, Post $post)
    {
        $input = $request->content;
        //The separator by defalut is a tabulation
        $separator = "/\t+/";
        //We change the tabulation if the user asked it
        if($request->separator_cards == 'egual'){
            $separator = "/=+/";
        }elseif($request->separator_cards == 'semicolon'){
            $separator = "/;+/";
        }
        //We create the key of our final array
        $cols = ["front","back", "id", "created_at", "updated_at"];
        //We create an empty array to get the final output
        $output = [];
        //We divide the input by line
        foreach (preg_split("/((\r?\n)|(\r\n?))/", $input) as $line){
            //We create an array for this line
            $newLine = [];
            //We separe the value of the line by the separator selected. $Value is an array
            $values = preg_split($separator, $line);
            //For each values of our new array
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
    public function update(CardEditRequest $request, Post $post, Card $card)
    {
        $card->front = str_ireplace($this->forbiden_tags, '', $request->front);
        $card->back = str_ireplace($this->forbiden_tags, '', $request->back);
        $card->save();
        return redirect()->route('cards.index', $post->id)->with('message', __('The card has been updated.'));
       
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
