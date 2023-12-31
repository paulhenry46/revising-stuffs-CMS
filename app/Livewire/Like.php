<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;

class Like extends Component
{
    public Post $post;
    public $count; //int $count

    public function mount(Post $post)
    {
        $this->post = $post;
        $this->count = $post->likes_count;
    }

    public function like(): void
    {
        if($this->post->isLiked()) {
            $this->post->removeLike();
            $this->count--;
        }elseif(auth()->user()){
            $this->post->likes()->create([
                'user_id' => auth()->id(),
            ]);
            $this->count++;
        }elseif(($ip = request()->ip()) && ($userAgent = request()->userAgent())){
            $this->post->likes()->create([
                'ip' => $ip,
                'user_agent' => $userAgent,
            ]);
            $this->count++;
        }
    }

    public function placeholder()
    {
        return <<<'HTML'
        <div>
            <div class="w-16 h-10 absolute left-0 top-0 flex items-center justify-center bg-base-200 shadow-lg  rounded-br-lg rounded-tl-lg">
                <span class="text-primary loading loading-spinner loading-sm"></span>
            </div>
        </div>
        HTML;
    }

    public function render()
    {
        return view('livewire.like');
    }
}
