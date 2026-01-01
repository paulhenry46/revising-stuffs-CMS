<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class PostsUserChart extends Component
{
    public $HistoryChart;

    public function render()
    {
        return view('livewire.posts-user-chart');
    }

    public function mount( User $user)
    {
        $now = now();
        $months = collect();
        for ($i = 11; $i >= 0; $i--) {
            $months->push($now->copy()->subMonths($i)->format('Y-m'));
        }

        // Get all post IDs for this user
        $postIds = $user->posts()->pluck('id');

        // Query downloads for these posts, grouped by month
        $downloads = \App\Models\Download::whereIn('post_id', $postIds)
            ->where('downloaded_at', '>=', $now->copy()->subMonths(12)->startOfMonth())
            ->get()
            ->groupBy(function ($item) {
                return \Carbon\Carbon::parse($item->downloaded_at)->format('Y-m');
            });

        $downloadsByMonth = $months->map(function ($month) use ($downloads) {
            return $downloads->has($month) ? $downloads[$month]->count() : 0;
        });


        // Calculate average downloads per month (ignore months with 0 downloads)
        $nonZeroMonths = $downloadsByMonth->filter(function($v) { return $v > 0; });
        $average = $nonZeroMonths->count() > 0 ? round($nonZeroMonths->sum() / $nonZeroMonths->count(), 2) : 0;
        $averageLine = array_fill(0, $months->count(), $average);

        $this->HistoryChart = [
            'type' => 'bar',
            'data' => [
                'labels' => $months->map(function($m) {
                    return \Carbon\Carbon::createFromFormat('Y-m', $m)->format('M Y');
                }),
                'datasets' => [
                    [
                        'type' => 'line',
                        'label' => 'Average',
                        'data' => $averageLine,
                        'borderColor' => 'rgb(255, 82, 217)',
                        'backgroundColor' => 'rgba(255, 82, 217, 0.2)',
                        'borderWidth' => 2,
                        'pointRadius' => 0,
                        'fill' => false,
                        'tension' => 0.1,
                        'borderDash' => [10,5],
                    ],
                    [
                        'label' => 'Downloads',
                        'data' => $downloadsByMonth,
                        'fill' => true,
                        'borderColor' => 'rgb(255, 88, 97)',
                        'backgroundColor'=> 'rgba(255, 88, 97, 1)',
                        'stack'=> 'Stack 0'
                    ],
                    
                ]
            ],
            'options' => [
                'maintainAspectRatio' => false,
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
