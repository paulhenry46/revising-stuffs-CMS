<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Comment;
use App\Models\Post;
use Livewire\WithPagination;

class CommentsTable extends Component
{
    use WithPagination;

    public array $selection = [];
    public function updating($name, $value){
    }

    public function placeholder()
    {
        return <<<'HTML'
        <div>
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">
                            <h1 class=" text-2xl font-medium text-gray-900 dark:text-white">
                                    Moderate the comments
                            </h1>
                        </div>
                        <div role="status" class=" p-4 space-y-4 border border-gray-200 divide-y divide-gray-200 rounded shadow animate-pulse dark:divide-gray-700 md:p-6 dark:border-gray-700">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="h-2.5 bg-gray-300 rounded-full dark:bg-gray-600 w-24 mb-2.5"></div>
                                    <div class="w-32 h-2 bg-gray-200 rounded-full dark:bg-gray-700"></div>
                                </div>
                                <div class="h-2.5 bg-gray-300 rounded-full dark:bg-gray-700 w-12"></div>
                            </div>
                            <div class="flex items-center justify-between pt-4">
                                <div>
                                    <div class="h-2.5 bg-gray-300 rounded-full dark:bg-gray-600 w-24 mb-2.5"></div>
                                    <div class="w-32 h-2 bg-gray-200 rounded-full dark:bg-gray-700"></div>
                                </div>
                                <div class="h-2.5 bg-gray-300 rounded-full dark:bg-gray-700 w-12"></div>
                            </div>
                            <div class="flex items-center justify-between pt-4">
                                <div>
                                    <div class="h-2.5 bg-gray-300 rounded-full dark:bg-gray-600 w-24 mb-2.5"></div>
                                    <div class="w-32 h-2 bg-gray-200 rounded-full dark:bg-gray-700"></div>
                                </div>
                                <div class="h-2.5 bg-gray-300 rounded-full dark:bg-gray-700 w-12"></div>
                            </div>
                            <div class="flex items-center justify-between pt-4">
                                <div>
                                    <div class="h-2.5 bg-gray-300 rounded-full dark:bg-gray-600 w-24 mb-2.5"></div>
                                    <div class="w-32 h-2 bg-gray-200 rounded-full dark:bg-gray-700"></div>
                                </div>
                                <div class="h-2.5 bg-gray-300 rounded-full dark:bg-gray-700 w-12"></div>
                            </div>
                            <div class="flex items-center justify-between pt-4">
                                <div>
                                    <div class="h-2.5 bg-gray-300 rounded-full dark:bg-gray-600 w-24 mb-2.5"></div>
                                    <div class="w-32 h-2 bg-gray-200 rounded-full dark:bg-gray-700"></div>
                                </div>
                                <div class="h-2.5 bg-gray-300 rounded-full dark:bg-gray-700 w-12"></div>
                            </div>
                            <span class="sr-only">Loading...</span>
                        </div>
                      </div>
                    </div>
                </div>
            </div>
        HTML;
    }

    public function deleteComments(array $ids){
        Comment::whereIn('id', $ids)->delete();
        $this->selection = [];
        $this->resetPage();
        session()->flash('message', __('The comment has been deleted.'));
    }

    public function validateComments(array $ids){
        Comment::whereIn('id', $ids)->update(['validated' => 1]);
        $this->selection = [];
        $this->resetPage();
        session()->flash('message', __('The comment has been validated.'));
    }

    public function deleteComment($id){
        Comment::where('id', '=', $id)->delete();
        $this->selection = [];
        $this->resetPage();
        session()->flash('message', __('The comment has been deleted.'));
    }

    public function validateComment($id){
        Comment::where('id', '=', $id)->update(['validated' => 1]);
        $this->selection = [];
        $this->resetPage();
        session()->flash('message', __('The comment has been validated.'));
    }

    public function deleteAllComments(){
        Comment::where('validated', 0)->delete();
        $this->selection = [];
        $this->resetPage();
        session()->flash('message', __('All the comments been deleted.'));
    }

    public function render()
    {
        return view('livewire.comments-table', ['comments' => Comment::where('validated', 0)->paginate(15)]);
    }
}
