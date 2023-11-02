<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Card;
use App\Models\Post;
use Livewire\WithPagination;

class CardsTable extends Component
{
    use WithPagination;

    public string $search = '';
    public $post;
    public array $selection = [];
    public function updating($name, $value){
        if($name == 'search'){
            $this->resetPage();
        }
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
                                    Manage the cards attached to this post
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

    public function deleteCards(array $ids){
        Card::where('post_id', '=', $this->post->id)->whereIn('id', $ids)->delete();
        //Card::destroy($ids);
        $this->selection = [];
        $this->resetPage();
        if(Card::where('post_id', '=', $this->post->id)->count() == 0){
                $this->post->cards = false;
                $this->post->save();
            } 
        session()->flash('message', __('The cards has been deleted.'));
    }

    public function deleteCard($id){
        Card::where('post_id', '=', $this->post->id)->where('id', $id)->delete();
        $this->selection = [];
        $this->resetPage();
        if(Card::where('post_id', '=', $this->post->id)->count() == 0){
                $this->post->cards = false;
                $this->post->save();
            }
        session()->flash('message', __('The cards has been deleted.'));
    }

    public function deleteAllCards(){
        Card::where('post_id', '=', $this->post->id)->delete();
        //Card::destroy($ids);
        $this->post->cards = false;
        $this->post->save();
        $this->selection = [];
        $this->resetPage();
        session()->flash('message', __('All the cards been deleted.'));
    }

    public function mount(Post $post){
    $this->post = $post;
    }

    public function render()
    {
        return view('livewire.cards-table', ['cards' => Card::where('post_id', '=', $this->post->id)
            ->when($this->search, function($query, $search){
                return $query->where('front', 'LIKE', "%{$this->search}%")->orWhere('back', 'LIKE', "%{$this->search}%");})->paginate(15), 'post' => $this->post]);
    }
}
