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

        $this->HistoryChart = [
            'type' => 'bar',
            'data' => [
                'labels' => $months->map(function($m) {
                    return \Carbon\Carbon::createFromFormat('Y-m', $m)->format('M Y');
                }),
                'datasets' => [
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
