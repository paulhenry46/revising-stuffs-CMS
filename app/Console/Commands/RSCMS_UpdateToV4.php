<?php

namespace App\Console\Commands;

use App\Models\Card;
use App\Models\Deck;
use Illuminate\Console\Command;
use App\Models\Post;
use App\Models\Step;

class RSCMS_UpdateToV4 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rscms:updateToV4';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to update from a 3.x version of the app to the 4.0 version. Run once';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $posts = Post::where('cards', true)->get();
        $progressbar = $this->output->createProgressBar(count($posts));
        foreach($posts as $post){
            $deck = new Deck;
            $deck->name = "main";
            $post->decks()->save($deck);
            $progressbar->advance();
        }
    $progressbar->finish();

    $cards = Card::all();

    $progressbar = $this->output->createProgressBar(count($cards));
    $progressbar->start();
        foreach($cards as $card){
            $id = $card->post_id;
            $deck = Post::where('id', $id)->first()->decks->first();
            $deck->cards()->attach($card->id);
            $progressbar->advance();
        }
    $progressbar->finish();

    $steps = Step::all();

    $progressbar = $this->output->createProgressBar(count($steps));
    $progressbar->start();
        foreach($steps as $step){
            $id = $step->post_id;
            $step->deck_id = Post::where('id', $id)->first()->decks->first()->id;
            $step->save();
            
            $progressbar->advance();
        }
    $progressbar->finish();

    $this->newLine();
    $this->info('The command was successful!.');
    }
}
