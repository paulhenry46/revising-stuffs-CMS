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
        /*$this->types = Type::where(function ($query) {
            $query->where('course_id', $this->course->id) //Get types for this courses
                  ->orWhere('course_id', 1); //Get types for all courses
        })->pluck('id')->toArray();*/
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
            /*->when($this->course, function($query, $course){
                return $query->where('course_id', '=', $this->course);
            })
            ->when($this->level, function($query, $level){
                return $query->where('level_id', '=', $this->level);
            })*/
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
                return $query->where('cards', 1);
            })
            ->when($this->quizlet, function($query){
                return $query->where('quizlet_url', '!=', NULL);
            })->orderBy('id', 'desc')
            ->get(),
            //'courses' => $courses = Course::all(),
            'levels' => $levels = Level::all(),
            'types_view' => Type::where(function ($query) {
                $query->where('course_id', $this->course->id) //Get types for this courses
                      ->orWhere('course_id', 1); //Get types for all courses
            })->get(),
            'schools_view' => $this->level->curriculum->schools
        ]);
    }
}
//->where('course_id', '=', $this->course)