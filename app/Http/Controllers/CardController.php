<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Post;
use Illuminate\Support\Str;
use App\Http\Requests\CardRequest;
use App\Http\Requests\CardImportRequest;
use Illuminate\Support\Facades\Response;

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
        $this->authorize('list', [Card::class, $post]);
        return view('cards.show', compact('post'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Post $post)
    {
        $this->authorize('create', [Card::class, $post]);
        $card = new Card;
        $card->id = 0;
        return view('cards.edit', compact('card', 'post'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CardRequest $request, Post $post)
    {
        $this->authorize('create',[Card::class, $post]);
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
        $this->authorize('create', [Card::class, $post]);
        return view('cards.import', compact('post'));
    }

    public function storeImport(CardImportRequest $request, Post $post)
    {
        $this->authorize('create', [Card::class, $post]);
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
        $this->authorize('update', $card);
        return view('cards.edit', compact('card', 'post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CardRequest $request, Post $post, Card $card)
    {
        $this->authorize('update', $card);
        $card->front = str_ireplace($this->forbiden_tags, '', $request->front);
        $card->back = str_ireplace($this->forbiden_tags, '', $request->back);
        $card->save();
        return redirect()->route('cards.index', $post->id)->with('message', __('The card has been updated.'));
       
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post, Card $card)
    {   
        //See App\Livewire\CardsTable for this function
    }

    public function export(string $slug, Post $post)
    {
        $this->authorize('export', [Card::class, $post]);
        $cards = Card::where('post_id', '=', $post->id)->get();
        $csvFileName = 'cards-'.$post->title.'-'.$post->id.'.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $csvFileName . '"',
        ];

        $handle = fopen('php://output', 'w');
        fputcsv($handle, ['front', 'back']); // Add more headers as needed

        foreach ($cards as $card) {
            fputcsv($handle, [$card->front, $card->back]); // Add more fields as needed
        }

        fclose($handle);

        return Response::make('', 200, $headers);
    }
}