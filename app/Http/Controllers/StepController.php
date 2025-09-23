<?php

namespace App\Http\Controllers;

use App\Http\Requests\StepRequest;
use App\Models\Step;
use App\Models\Deck;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class StepController extends Controller
{

    /**
     * Show the form for creating a new steap.
     */
    public function create(StepRequest $request)
    {
        if(Auth::check()){
            $LastStep = Step::where('user_id', Auth::id())->where('deck_id', $request->deckId)->orderBy('created_at', 'DESC')->get();
            if($LastStep->first() === NULL){ //Check if there is already a step
                $step = new Step;
                $step->user_id = Auth::id();
                $step->deck_id = $request->deckId;
                $step->post_id = Deck::find($step->deck_id)->deckable->id;
                $step->mastery = ($request->percent / 100);
                $step->percent = $request->percent;
                $step->next_step = $this->nextStep($step->mastery);
                $step->save();
                return $step;
            }else{
                if($this->checkDate($LastStep) !== true){//If there is no step, we bypass the check
                    return __('It\'s too early, please wait until the next exam date for your mastery level.');
                    exit();
                }else{
                    $LastStep = $LastStep->first();
                    
                    $step = new Step;
                    $step->user_id = Auth::id();
                    $step->deck_id = $request->deckId;
                    $step->post_id = Deck::find($step->deck_id)->deckable->id;
                    $step->mastery = $this->newMastery($LastStep, $request->percent);
                    $step->percent = $request->percent;
                    $step->next_step = $this->nextStep($step->mastery);
                    $step->save();
                    $LastStep->next_step = null;
                    $LastStep->save();
                    return $step;
                }
            }
        }else{
            abort('404');
        }
    }
     /**
     * Check if the date of next_date is passed
     */
    private function checkDate($LastStep){
        //return $LastStep->first()->next_step->gte(Carbon::today());
        return Carbon::today()->gte($LastStep->first()->next_step);
    }

    /**
     * Get the new level of mastery of a post
     */
    private function newMastery($Laststep, int $percent)
    {
        $score = $percent / 100;

        if($Laststep->mastery < 1){//There are no step for this user and post, so it is the first time the post is studied.
            return $score;
        }elseif($score > 0.95){
            $newMastery = $Laststep->mastery + 1;
            if ($newMastery > 9){
               $newMastery = 9; 
            }
            return $newMastery;
        }else{
            $newMastery = $Laststep->mastery - 1;
            if ($newMastery < 1){
               $newMastery = 1; 
            }
            return $newMastery;
        }
    }

    /**
     * Get the date of the next step
     */
    private function nextStep(int $newMastery)
    {
        $today = Carbon::today();
        if($newMastery < 1){
            $date = $today;
        }elseif($newMastery == 1){
            $date = Carbon::tomorrow();
        }elseif($newMastery == 2){
            $date = $today->addDays(2);
        }elseif($newMastery == 3){
            $date = $today->addDays(7);
        }elseif($newMastery == 4){
            $date = $today->addDays(14);
        }elseif($newMastery == 5){
            $date = $today->addDays(30);
        }elseif($newMastery == 6){
            $date = $today->addDays(60);
        }elseif($newMastery == 7){
            $date = $today->addDays(90);
        }elseif($newMastery == 8){
            $date = $today->addDays(120);
        }elseif($newMastery == 9){
            $date = $today->addDays(120);
        }
        return $date;
    }
}
