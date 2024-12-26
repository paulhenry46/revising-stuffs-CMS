<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;
use App\Models\File;
use App\Models\User;

class PostsDownloadsUserCount extends Component
{
    public $posts;
    public $count;
    public User $user;

    public function render()
    {
        return view('livewire.count.user.posts-downloads-user-count');
    }
    public function mount(User $user)
    {
        $this->user = $user;
        $this->posts = $this->user->posts()->pluck('id')->toArray();
        $this->count = array_sum(File::whereIn('post_id', $this->posts)->pluck('download_count')->toArray());
    }

    public function placeholder()
    {
        return <<<'HTML'
        <div>
            <span class="text-secondary loading loading-spinner loading-sm"></span>
        </div>
        HTML;
    }
}
