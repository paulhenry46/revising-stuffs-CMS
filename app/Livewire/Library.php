<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Url;
use App\Models\Post;
use App\Models\Course;
use App\Models\Level;

class Library extends Component
{
    #[Url(as: 'query')]
    public $search = '';
     #[Url(as: 'course')]
    public $course = '';
    #[Url(as: 'level')]
    public $level = '';
    #[Url(as: 'type')]
    public $types = ['mindmap', 'revision', 'metodo']; // Default is empty, so "All" is checked;
    #[Url(as: 'dark')]
    public $dark = false;
    //#[Url(as: 'cards')]
    //public $cards = false;
    #[Url(as: 'quizlet')]
    public $quizlet = false;


    public function render()
    {
        return view('livewire.library', [
            'posts' => Post::where('published', '=', 1)
            ->when($this->search, function($query, $search){
                return $query->where('title', 'LIKE', "%{$this->search}%")->orWhere('description', 'LIKE', "%{$this->search}%");
            })
            ->when($this->course, function($query, $course){
                return $query->where('course_id', '=', $this->course);
            })
            ->when($this->level, function($query, $level){
                return $query->where('level_id', '=', $this->level);
            })
            ->when(count(array_filter($this->types)), function ($query) {
                    return $query->whereIn('type', $this->types);
            })
            ->when($this->dark, function($query){
                return $query->where('dark_version', 1);
            })
            ->when($this->quizlet, function($query){
                return $query->where('quizlet_url', '!=', NULL);
            })->orderBy('id', 'desc')
            ->get(),
            'courses' => $courses = Course::all(),
            'levels' => $levels = Level::all()
        ]);
    }
}
//->where('course_id', '=', $this->course)