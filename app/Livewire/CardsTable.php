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

    public Post $post;
    public array $selection = [];

    public function updating($name, $value){
        if($name == 'search'){
            $this->resetPage();
        }
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
                return $query->where(function ($query) {
                        $query->where('front', 'LIKE', "%{$this->search}%")
                          ->orWhere('back', 'LIKE', "%{$this->search}%");
                });})->paginate(15), 'post' => $this->post]);
    }
}
