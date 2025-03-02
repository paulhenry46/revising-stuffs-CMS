<?php

namespace App\Livewire;

use App\Models\Post;
use App\Models\Step;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Mary\Traits\Toast;
use Illuminate\Support\Str;

class StepHistory extends Component
{
    use Toast;

    public array $HistoryChart;
    public $numberBeforeNextRevision;
    private $post_id;
    public $last_step;
    public Post $post;
    public $mastery_percent;
    public $learning_percent;
    public function render()
    {
        return view('livewire.step-history');
    }

    public function placeholder()
    {
        return <<<'HTML'
            <div class="mt-6 mb-3 card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title">{{__('Progression')}}</h2>
                    <span class="text-primary loading loading-spinner loading-sm"></span>
                </div>
            </div>
        HTML;
    }

    public function reinitialize(){
        Step::where('user_id', Auth::id())->where('post_id', $this->post->id)->delete();
        $this->success(
            title: __('Progress reinitialized !')
        );
    }

    public function mount(Post $post)
    {
        if(Auth::check()){
        $this->post = $post;
        $this->post_id = $post->id;
        $steps = Step::where('user_id', Auth::id())->where('post_id', $post->id)->orderBy('created_at', 'ASC')->get();

        if($steps->first() !== null){
            $this->last_step = $steps->sortByDesc('created_at')->first();
            if($this->last_step->mastery >=1){
                $this->mastery_percent = Str::limit(($this->last_step->mastery/9)*100, 2,'');
                $this->learning_percent = 100;
            }else{
                $this->mastery_percent = 0;
                $this->learning_percent = $this->last_step->mastery*100;
            }
            
            $number = Carbon::today()->diffInDays($this->last_step->next_step, false);
            if($number > 0){
                $this->numberBeforeNextRevision =  $number;
            }else{
                $this->numberBeforeNextRevision = 0;
            } 
        }

        $dates = [];
        foreach ($steps as $step) {
            array_push($dates, $step->created_at->format('d M'));
        }

        $percent = [];
        foreach ($steps as $step) {
            array_push($percent, $step->percent);
        }

        $mastery = [];
        foreach ($steps as $step) {
            array_push($mastery, $step->mastery);
        }

        $percent = $steps->pluck('percent');
        //dd($dates);
        $this->HistoryChart = [
            'type' => 'line',
            'data' => [
                'labels' => $dates,
                'datasets' => [
                    [
                        'label' => __('Score'),
                        'data' => $percent,
                        'fill' => true,
                        'borderColor' => 'rgb(116, 128, 255)',
                        'backgroundColor'=> 'rgba(116, 128, 255, 0.2)',
                        'tension' => 0.3,
                        'yAxisID'=> 'y'
                    ],

                    [
                        'label' => __('Mastery'),
                        'data' => $mastery,
                        'fill' => true,
                        'borderColor' => 'rgb(0, 169, 110)',
                        'backgroundColor'=> 'rgba(0, 169, 110, 0.2)',
                        'tension' => 0.3,
                        'yAxisID'=> 'y1'
                    ]
                ]
                    ],
                    'options' => [
                        'maintainAspectRatio' => false,
                        'scale' => [
                            'y' => [
                                'type'=> 'linear',
                                'display'=> true,
                                'position'=> 'left',
                                'max' => 100,
                                'min' => 0,
                            ],

                            'y1' => [
                                'type'=> 'linear',
                                'display'=> true,
                                'position'=> 'right',
                                'max' => 9,
                                'grid' => [
                                    'drawOnChartArea'=> false
                                ]
                            ],
                        ]
                    ]
        ];
    }else{
        $this->HistoryChart = [
            'type' => 'line',
            'data' => [
                'labels' => [Carbon::today()->format('d M'), Carbon::yesterday()->format('d M'), Carbon::today()->subDays(2)->format('d M')],
                'datasets' => [
                    [
                        'label' => __('Score'),
                        'data' => [50, 80, 70],
                        'fill' => true,
                        'borderColor' => 'rgb(116, 128, 255)',
                        'backgroundColor'=> 'rgba(116, 128, 255, 0.2)',
                        'tension' => 0.3,
                        'yAxisID'=> 'y'
                    ],

                    [
                        'label' => __('Mastery'),
                        'data' => [7, 6, 7],
                        'fill' => true,
                        'borderColor' => 'rgb(0, 169, 110)',
                        'backgroundColor'=> 'rgba(0, 169, 110, 0.2)',
                        'tension' => 0.3,
                        'yAxisID'=> 'y1'
                    ]
                ]
                    ],
                    'options' => [
                        'maintainAspectRatio' => false,
                        'scale' => [
                            'y' => [
                                'type'=> 'linear',
                                'display'=> true,
                                'position'=> 'left',
                                'max' => 100,
                                'min' => 0,
                            ],

                            'y1' => [
                                'type'=> 'linear',
                                'display'=> true,
                                'position'=> 'right',
                                'max' => 9,
                                'min' => 0,
                                'grid' => [
                                    'drawOnChartArea'=> false
                                ]
                            ],
                        ]
                    ]
        ];
    }
    }
}
