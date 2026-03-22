<?php

namespace App\Livewire;

use App\Models\Level;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class CertifyPostsTable extends Component
{
    use WithPagination;

    public string $search = '';

    public function updating($name, $value): void
    {
        if ($name === 'search') {
            $this->resetPage();
        }
    }

    private function scopedPostQuery()
    {
        $user  = Auth::user();
        $query = Post::where('published', 1)
            ->where('group_id', '!=', 1)
            ->when($this->search, function ($q) {
                $q->where(function ($q2) {
                    $q2->where('title', 'LIKE', "%{$this->search}%")
                        ->orWhere('description', 'LIKE', "%{$this->search}%");
                });
            });

        if ($user->hasRole('co-admin') && !$user->hasRole('admin')) {
            $curriculaIds = $user->getManagedCurriculaIds();
            $levelIds     = Level::whereIn('curriculum_id', $curriculaIds)->pluck('id');
            $query->whereIn('level_id', $levelIds);
        }

        return $query;
    }

    public function certifyPost(int $id): void
    {
        $this->authorize('moderate', Post::class);

        Post::where('id', $id)
            ->whereIn('id', $this->scopedPostQuery()->pluck('id'))
            ->update(['certified_at' => now()]);

        session()->flash('message', __('The post has been certified.'));
    }

    public function uncertifyPost(int $id): void
    {
        $this->authorize('moderate', Post::class);

        Post::where('id', $id)
            ->whereIn('id', $this->scopedPostQuery()->pluck('id'))
            ->update(['certified_at' => null]);

        session()->flash('message', __('The certification has been removed.'));
    }

    public function placeholder(): string
    {
        return <<<'HTML'
        <div>
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white dark:bg-base-100 overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6 lg:p-8">
                            <div role="status" class="p-4 space-y-4 border border-gray-200 divide-y divide-gray-200 rounded-sm shadow-sm animate-pulse dark:divide-gray-700">
                                <div class="flex items-center justify-between">
                                    <div class="h-2.5 bg-gray-300 rounded-full dark:bg-gray-600 w-24 mb-2.5"></div>
                                </div>
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        HTML;
    }

    public function render()
    {
        return view('livewire.certify-posts-table', [
            'posts' => $this->scopedPostQuery()->latest()->paginate(15),
        ]);
    }
}
