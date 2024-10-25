<?php

namespace App\Livewire;

use App\Models\Post;
use App\Models\Step;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;

class LearnHistory extends Component
{

    public array $HistoryChart;
    public $numberBeforeNextRevision;
    public $steps;
    public User $user;
    public function render()
    {
        return view('livewire.learn-history');
    }

    public function placeholder()
    {
        return <<<'HTML'
            <div class="mt-6 mb-3 card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title">{{__('Progress')}}</h2>
                    <span class="text-primary loading loading-spinner loading-sm"></span>
                </div>
            </div>
        HTML;
    }



private function masteryOfWeek($steps){

    $newsteps = $steps->groupBy('post_id');
        $masteryOfPost = [];
        foreach($newsteps as $postSteps){
            $masteryOfPost[$postSteps->last()->post_id] = $postSteps->last()->mastery;
        }

        $mastery = [];
        $mastery[1] = 0;
        $mastery[2] = 0;
        $mastery[3] = 0;
        $mastery[4] = 0;

        foreach($masteryOfPost as $key => $value){
            if($value <= 1){
                $mastery[1]++;
            }elseif($value <= 3){
                $mastery[2]++;
            }elseif($value <= 6){
                $mastery[3]++;
            }elseif($value <= 9){
                $mastery[4]++;
            }
        }

        return $mastery;

}
    public function mount(User $user)
    {

        $this->steps = Step::where('user_id', $this->user->id)->where('next_step', '!=', null)->orderBy('next_step', 'ASC')->limit(3)->get();
        foreach($this->steps as $step){
            $step['numberBeforeNextRevision'] =  Carbon::today()->diffInDays($step->next_step, false);
        }


        $this->user = $user;
        $toWeekStartDate = Carbon::today()->endOfWeek()->adddays(7);
    
        for ($i = 1; $i <= 4; $i++) {
            $datas[$i] = $this->masteryOfWeek(Step::where('user_id', $this->user->id)->where('created_at','<=', $toWeekStartDate->subDays(7))->get());
        }

        $this->HistoryChart = [
            'type' => 'bar',
            'data' => [
                'labels' => [__('3 weeks ago'),__('2 weeks ago'),__('1 week ago'),__('This week')],
                'datasets' => [
                    [
                        'label' => __('Learning'),
                        'data' => [$datas[4][1], $datas[3][1], $datas[2][1], $datas[1][1]],
                        'fill' => true,
                        'borderColor' => 'rgb(255, 88, 97)',
                        'backgroundColor'=> 'rgba(255, 88, 97, 1)',
                        'stack'=> 'Stack 0'
                    ],

                    [
                        'label' => __('Good mastery'),
                        'data' => [$datas[4][2], $datas[3][2], $datas[2][2], $datas[1][2]],
                        'fill' => true,
                        'borderColor' => 'rgb(255, 82, 217)',
                        'backgroundColor'=> 'rgba(155, 82, 217, 1)',
                        'stack'=> 'Stack 0'
                    ],

                    [
                        'label' => __('Very good mastery'),
                        'data' => [$datas[4][3], $datas[3][3], $datas[2][3], $datas[1][3]],
                        'fill' => true,
                        'borderColor' => 'rgb(116, 128, 255)',
                        'backgroundColor'=> 'rgba(116, 128, 255, 1)',
                        'stack'=> 'Stack 0'
                    ],

                    [
                        'label' => __('Excelent mastery'),
                        'data' => [$datas[4][4], $datas[3][4], $datas[2][4], $datas[1][4]],
                        'fill' => true,
                        'borderColor' => 'rgb(0, 169, 110)',
                        'backgroundColor'=> 'rgba(0, 169, 110, 1)',
                        'stack'=> 'Stack 0'
                    ]
                ]
                    ],
                    'options' => [
                        'scales' => [
                            'x'=> [
                              'stacked'=> true,
                            ],
                            'y'=> [
                              'stacked'=> true
                            ]
                    
                        ]
                    ]
        ];
    }
}
