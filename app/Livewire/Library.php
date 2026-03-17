<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Url;
use App\Models\Post;
use App\Models\Course;
use App\Models\Type;
use App\Models\Level;
use App\Models\School;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Auth;

class Library extends Component
{
    #[Url(as: 'school_year')]
    public $school_year = null;

    public $school_years = [];

    #[Url(as: 'query')]
    public $search;
    //#[Url(as: 'course')]
    public $course;
    //#[Url(as: 'level')]
    public $level;
    #[Url(as: 'type')]
    public $types = []; // Default is empty, so "All" is checked;
    #[Url(as: 'school')]
    public $schools = []; // Default is empty, so "All" is checked;
    #[Url(as: 'dark')]
    public $dark = false;
    #[Url(as: 'cards')]
    public $cards = false;
    #[Url(as: 'quizlet')]
    public $quizlet = false;

    public function mount(Level $level, Course $course)
    {
        $this->course = $course;
        $this->level = $level;
        // Populate available school years from posts
            $years = Post::where('course_id', '=', $this->course->id)
            ->where('level_id', '=', $this->level->id)
                ->orderByDesc('created_at')
                ->get()
                ->map(fn($post) => $post->created_at->year)
                ->unique()
                ->sort()
                ->reverse()
                ->values()
                ->toArray();

            $this->school_years = [];
            foreach ($years as $year) {
                if ($year === reset($years)) {
                    $prev = $year - 1;
                    $this->school_years[] = [
                        'label' => "$prev/$year",
                        'start' => "$prev-09-01",
                        'end' => "$year-09-01",
                        'value' => "$prev/$year"
                    ];
                }
                $next = $year + 1;
                $this->school_years[] = [
                    'label' => "$year/$next",
                    'start' => "$year-09-01",
                    'end' => "$next-09-01",
                    'value' => "$year/$next"
                ];
            }
    }

    public function render()
    {
        $user = auth()->user();
        return view('livewire.library', [
            'posts' => Post::where('published', '=', 1)

            ->where('group_id', '!=', 1)
            ->when(Auth::check(), function ( $query) {
                $query->where(function ($query) {
                    $query->whereRelation('group', 'public', true)
                          ->orwhereIn('group_id', auth()->user()->groups->pluck('id')->toArray());
                });
            })
            ->when(Auth::check() !== true, function ( $query) {
                $query->where(function ($query) {
                    $query->whereRelation('group', 'public', true);
                });
            })
            ->where('course_id', '=', $this->course->id)
            ->where('level_id', '=', $this->level->id)
            ->when($this->search, function($query, $search){
                return $query->where('title', 'LIKE', "%{$this->search}%")->orWhere('description', 'LIKE', "%{$this->search}%");
            })
            ->when(count(array_filter($this->types)), function ($query) {
                    return $query->whereIn('type_id', $this->types);
            })
            ->when(count(array_filter($this->schools)), function ($query) {
                return $query->whereIn('school_id', $this->schools);
        })
            //->whereIn('type_id', $this->types)
            ->when($this->dark, function($query){
                return $query->where('dark_version', 1);
            })
            ->when($this->cards, function($query){
                return $query->has('decks');
            })
            ->when($this->quizlet, function($query){
                return $query->where('quizlet_url', '!=', NULL);
            })->orderBy('id', 'desc')
            ->when($this->school_year, function ($query) {
                    $selected = collect($this->school_years)->firstWhere('value', $this->school_year);
                    if ($selected) {
                        $query->whereBetween('created_at', [$selected['start'], $selected['end']]);
                    }
                })
                
            ->get(),
            //'courses' => $courses = Course::all(),
            'levels' => $levels = Level::all(),
            'types_view' => Type::where(function ($query) {
                $query->where('course_id', $this->course->id) //Get types for this courses
                      ->orWhere('course_id', 1); //Get types for all courses
            })->get(),
            'schools_view' => $this->level->curriculum->schools,
            'school_years' => $this->school_years,
            'school_year' => $this->school_year,
        ]);
    }
}
//->where('course_id', '=', $this->course)