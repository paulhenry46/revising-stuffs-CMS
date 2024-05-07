<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;
use App\Models\User;
use Auth;

class FavoriteButton extends Component
{
    public Post $post;
    public User $user;
    public bool $mobile;

    public function favorite(): void
    {
        if ($this->user->hasFavorited($this->post->id)) {
            $this->user->removeFavorite($this->post->id, $this->user);
        }else{
            $this->user->addFavorite($this->post->id, $this->user);
        }
    }

    public function mount(Post $post, User $user, $mobile=false)
    {
        $this->post = $post;
        $this->user = $user;
        $this->mobile = $mobile;
    }

        public function placeholder()
    {
        return <<<'HTML'
        <div>
            <div class="w-10 h-10 absolute right-0 top-0 flex items-center justify-center bg-base-200 shadow-lg  rounded-bl-lg rounded-tr-lg">
                <span class="text-primary loading loading-spinner loading-sm"></span>
            </div>
        </div>
        HTML;
    }

    public function render()
    {
        return view('livewire.favorite-button');
    }
}
