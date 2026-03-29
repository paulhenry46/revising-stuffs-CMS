<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Download;
use App\Models\Course;
use App\Models\Level;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AllPostsChart extends Component
{
    public $HistoryChart;
    public string $period = 'month';
    public ?int $course_id = null;
    public ?int $level_id = null;

    public function mount(): void
    {
        $this->updateChart();
    }

    public function updatedPeriod(): void
    {
        $this->updateChart();
    }

    public function updatedCourseId(): void
    {
        $this->updateChart();
    }

    public function updatedLevelId(): void
    {
        $this->updateChart();
    }

    private function updateChart(): void
    {
        $user = Auth::user();

        // Build the base post query respecting co-admin restrictions
        $postQuery = Post::query();
        if ($user->hasRole('co-admin') && !$user->hasRole('admin')) {
            $curriculaIds = $user->getManagedCurriculaIds();
            $levelIds = Level::whereIn('curriculum_id', $curriculaIds)->pluck('id');
            $postQuery->whereIn('level_id', $levelIds);
        }

        if ($this->course_id) {
            $postQuery->where('course_id', $this->course_id);
        }

        if ($this->level_id) {
            $postQuery->where('level_id', $this->level_id);
        }

        $postIds = $postQuery->pluck('id');

        if ($this->period === 'week') {
            $this->buildWeeklyChart($postIds);
        } else {
            $this->buildMonthlyChart($postIds);
        }
    }

    private function buildMonthlyChart($postIds): void
    {
        $now = now();
        $currentMonthStart = $now->copy()->startOfMonth();

        $months = collect();
        $cursor = $currentMonthStart->copy()->subMonthsNoOverflow(11);
        while ($cursor->lte($currentMonthStart)) {
            $months->push($cursor->copy());
            $cursor->addMonthNoOverflow();
        }

        $downloads = Download::whereIn('post_id', $postIds)
            ->where('downloaded_at', '>=', $now->copy()->subMonths(12)->startOfMonth())
            ->get()
            ->groupBy(function ($item) {
                return Carbon::parse($item->downloaded_at)->startOfMonth()->format('Y-m');
            });

        $downloadsByPeriod = $months->map(function ($month) use ($downloads) {
            $monthKey = $month->format('Y-m');
            return $downloads->has($monthKey) ? $downloads[$monthKey]->count() : 0;
        });

        $labels = $months->map(fn($month) => $month->translatedFormat('M Y'));

        $this->buildChartData($labels, $downloadsByPeriod);
    }

    private function buildWeeklyChart($postIds): void
    {
        $now = now();
        $weeks = collect();
        for ($i = 11; $i >= 0; $i--) {
            $weeks->push($now->copy()->subWeeks($i)->startOfWeek());
        }

        $startDate = $weeks->first();

        $downloads = Download::whereIn('post_id', $postIds)
            ->where('downloaded_at', '>=', $startDate)
            ->get()
            ->groupBy(function ($item) {
                return Carbon::parse($item->downloaded_at)->startOfWeek()->format('Y-W');
            });

        $downloadsByPeriod = $weeks->map(function ($weekStart) use ($downloads) {
            $key = $weekStart->format('Y-W');
            return $downloads->has($key) ? $downloads[$key]->count() : 0;
        });

        $labels = $weeks->map(fn($w) => __('Week') . ' ' . $w->format('W') . ' (' . $w->translatedFormat('d M') . ')');

        $this->buildChartData($labels, $downloadsByPeriod);
    }

    private function buildChartData($labels, $downloadsByPeriod): void
    {
        $nonZero = $downloadsByPeriod->filter(fn($v) => $v > 0);
        $average = $nonZero->count() > 0 ? round($nonZero->sum() / $nonZero->count(), 2) : 0;
        $averageLine = array_fill(0, $labels->count(), $average);

        $this->HistoryChart = [
            'type' => 'bar',
            'data' => [
                'labels' => $labels->values(),
                'datasets' => [
                    [
                        'type' => 'line',
                        'label' => __('Average'),
                        'data' => $averageLine,
                        'borderColor' => 'rgb(255, 82, 217)',
                        'backgroundColor' => 'rgba(255, 82, 217, 0.2)',
                        'borderWidth' => 2,
                        'pointRadius' => 0,
                        'fill' => false,
                        'tension' => 0.1,
                        'borderDash' => [10, 5],
                    ],
                    [
                        'label' => __('Downloads'),
                        'data' => $downloadsByPeriod->values(),
                        'fill' => true,
                        'borderColor' => 'rgb(255, 88, 97)',
                        'backgroundColor' => 'rgba(255, 88, 97, 1)',
                        'stack' => 'Stack 0',
                    ],
                ],
            ],
            'options' => [
                'maintainAspectRatio' => false,
                'scales' => [
                    'x' => ['stacked' => true],
                    'y' => ['stacked' => true],
                ],
            ],
        ];
    }

    public function render()
    {
        $user = Auth::user();

        // Build course options respecting co-admin restrictions
        if ($user->hasRole('co-admin') && !$user->hasRole('admin')) {
            $curriculaIds = $user->getManagedCurriculaIds();
            $levelIds = Level::whereIn('curriculum_id', $curriculaIds)->pluck('id');
            $courses = Course::whereHas('levels', fn($q) => $q->whereIn('levels.id', $levelIds))->get();
            $levels = Level::whereIn('curriculum_id', $curriculaIds)->get();
        } else {
            $courses = Course::all();
            $levels = Level::all();
        }

        return view('livewire.all-posts-chart', compact('courses', 'levels'));
    }
}
