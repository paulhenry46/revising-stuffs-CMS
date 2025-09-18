<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Post;
use Illuminate\Support\Str;
use App\Http\Requests\CardRequest;
use App\Http\Requests\CardImportRequest;
use App\Models\Deck;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use \Illuminate\Validation\ValidationException;

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

    private array $users_tags =[
         
        '[RED]', 
        '[YEL]', 
        '[GRE]',
        '[PUR]', 
        '[/]',
        '[/IMG]'
    ];

    private array $users_tags_replaced =[
         
        '<span class="underline decoration-2 decoration-error">', 
        '<span class="underline decoration-2 decoration-warning">', 
        '<span class="underline decoration-2 decoration-success">',
        '<span class="underline decoration-2 decoration-primary">', 
        '</span>',
        '">',
    ];

    private array $latex_split_tag = [
        '='
    ];

    private array $latex_split_replaced = [
        '\) \(='
    ];
    
    /**
     * Display a listing of the resource.
     */

    public function index(Post $post)
    {   
        $this->authorize('list', [Card::class, $post]);
        return view('cards.index', compact('post'));
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
     * Sanitize values, replace tags by true values and split latex
     */
    private function replace_values(string $value, string $file_path, bool $create=true){
        $value_sanitized = str_ireplace($this->forbiden_tags, '', $value);
        $value_temp = str_ireplace('[IMG]', '<img class="h-48" src="'.$file_path.'/', $value_sanitized);
        $value = str_ireplace('[/IMG]', '">', $value_temp);
        $new_value = str_ireplace($this->users_tags, $this->users_tags_replaced, $value);
        if(str_contains($value, '\(') and (!str_contains($value, '\) \(=')) and ($create)){//Split latex only if latex is detected with \( balise
            return str_ireplace($this->latex_split_tag, $this->latex_split_replaced, $new_value);
        }else{
            return $new_value;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CardRequest $request, Post $post)
    {
        $this->authorize('create',[Card::class, $post]);

        if ($post->decks->isEmpty()) {
            $deck = new Deck();
            $deck->name = 'main';
            $post->decks()->save($deck);
            $post->load('decks');
        }

        $deck = $post->decks->first();

        $file_path=url('storage/'.$post->level->curriculum->slug.'/'.$post->level->slug.'/'.$post->course->slug.'/');

        $card = New Card;
        $card->front = $this->replace_values($request->front, $file_path);
        $card->back = $this->replace_values($request->back, $file_path);
        $card->deck_id = $deck->id;
        $card->save();
        if(!$post->cards){
            $post->cards = true;
            $post->save();
        }
        $deck->cards()->attach($card->id);
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

        if ($post->decks->isEmpty()) {
            $deck = new Deck();
            $deck->name = 'main';
            $post->decks()->save($deck);
            $post->load('decks');
        }
        $deck = $post->decks->first();

        $file_path=url('storage/'.$post->level->curriculum->slug.'/'.$post->level->slug.'/'.$post->course->slug.'/');
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
                $newLine[$cols[$col_index]] = $this->replace_values($value, $file_path);
            }
            $newLine['post_id'] = $post->id;
            $newLine['updated_at'] = now();
            $newLine['created_at'] = now();
            if ((!array_key_exists('front', $newLine))or(!array_key_exists('back', $newLine))) {
                $error = ValidationException::withMessages([
                    'content' => ['Error when handling your input near "'.$newLine['front'].'" on line '.(count($output)+1).'. Please, check if the separator selected exists.']
                 ]);
                 throw $error;
                 break;
            }
            $output[] = $newLine;
        }

        if(!$post->cards){
                $post->cards = true;
                $post->save();
            }
        $insertedCards = [];
        DB::transaction(function () use ($output, &$insertedCards) {
                foreach ($output as $card) {
                    $insertedCards[] = DB::table('cards')->insertGetId($card);
                }
            });

        // Attach to deck (avoiding duplicates)
        $deck->cards()->syncWithoutDetaching($insertedCards);

        return redirect()->route('cards.index', $post->id)->with('message', __('The cards has been imported.'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post, Card $card)
    {   
        $this->authorize('update', $card);
        $file_path=url('storage/'.$post->level->curriculum->slug.'/'.$post->level->slug.'/'.$post->course->slug.'/');
        $back = str_ireplace('<img class="h-48" src="'.$file_path.'/','[IMG]', $card->back);
        $card->back = str_ireplace($this->users_tags_replaced, $this->users_tags, $back);
        $front = str_ireplace('<img class="h-48" src="'.$file_path.'/','[IMG]', $card->front);
        $card->front = str_ireplace($this->users_tags_replaced, $this->users_tags, $front);
        return view('cards.edit', compact('card', 'post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CardRequest $request, Post $post, Card $card)
    {
        $file_path=url('storage/'.$post->level->curriculum->slug.'/'.$post->level->slug.'/'.$post->course->slug.'/');
        $this->authorize('update', $card);

        $card->back = $this->replace_values($request->back, $file_path, false);
        $card->front = $this->replace_values($request->front, $file_path, false);

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
        $this->authorize('view', $post);
        
        $this->authorize('export', [Card::class, $post]);
        $cards = Card::where('deck_id', '=', $post->decks->first()->id)->get();
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