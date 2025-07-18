<?php

namespace App\Http\Controllers\Public;
use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Deck;
use Illuminate\Support\Facades\Response;

class ReadCardInUserDeckController extends Controller
{
    /**
     * Display the cards in quiz mode
     */
    public function quiz(string $slug, Deck $deck)
    {
        $this->authorize('view', $deck);

        $cards = $deck->cards
        ->toJSON();
        return view('cards.quiz-deck', compact('deck', 'cards'));
        
    }
    /**
     * Display the cards in learn mode
     */
    public function learn(string $slug, Deck $deck)
    {
        $this->authorize('view', $deck);

        $cards = $deck->cards
        ->toJSON();
        return view('cards.learn-deck', compact('deck', 'cards'));
    }
    /**
     * Display the cards in normal mode
     */
    public function show(string $slug, Deck $deck)
    {
        $this->authorize('view', $deck);
        
        $cards = $deck->cards;

        return view('cards.show-deck', compact('deck', 'cards'));
    }

    public function export(string $slug, Deck $deck)
    {
        $this->authorize('view', $deck);
        
        $cards = Card::where('deck_id', '=', $deck->id)->get();
        $csvFileName = 'cards-'.$deck->slug.'-'.$deck->id.'.csv';
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
