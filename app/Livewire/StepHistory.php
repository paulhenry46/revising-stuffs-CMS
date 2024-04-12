<?php

namespace App\Livewire;

use App\Models\Post;
use App\Models\Step;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Mary\Traits\Toast;

class StepHistory extends Component
{
    use Toast;

    public array $HistoryChart;
    public $numberBeforeNextRevision;
    private $post_id;
    public Post $post;
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
        //$steps = Step::where('user_id', Auth::id())->where('post_id', $this->post_id)->delete();
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

        //$dates = $steps->pluck('created_at')->toArray();
        if($steps->first() !== null){
            //dd($steps->first()->next_step);
            //$this->numberBeforeNextRevision = $steps->sortByDesc('created_at')->first()->next_step->diffInDays(Carbon::today(), false);
            $this->numberBeforeNextRevision =  Carbon::today()->diffInDays($steps->sortByDesc('created_at')->first()->next_step, false);
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
