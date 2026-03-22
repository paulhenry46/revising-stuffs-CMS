<div>
<div class="py-4">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="text-sm breadcrumbs mb-2">
            <ul>
                <li><a wire:navigate href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
                <li><a wire:navigate href="{{ route('posts.certify') }}">{{ __('Certify posts') }}</a></li>
            </ul>
        </div>
        <div class="bg-white dark:bg-base-100 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 lg:p-8 bg-white dark:bg-base-100 border-b border-gray-200 dark:border-gray-700">
                <h1 class="text-2xl font-medium text-gray-900 dark:text-white">
                    {{ __('Certify posts') }}
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {{ __('Mark published posts as certified to validate their quality.') }}
                </p>
            </div>
            <div class="bg-white/25 dark:bg-base-100/25 gap-6 lg:gap-8 p-6 lg:p-8">
                <x-info-message />
                <div class="mb-4">
                    <label for="search" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{ __('Search') }}</label>
                    <div class="mt-2">
                        <input wire:model.live.debounce.400ms="search" type="text" id="search"
                            class="input input-bordered input-primary w-full" placeholder="{{ __('Search by title or description…') }}">
                    </div>
                </div>
                <div class="px-4 sm:px-6 lg:px-8">
                    <div class="mt-4 flow-root">
                        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                                <table class="table table-zebra">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Title') }}</th>
                                            <th>{{ __('Author') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th class="text-right">{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($posts as $post)
                                        <tr>
                                            <td>
                                                <div class="font-bold">{{ $post->title }}</div>
                                                <div class="text-sm opacity-50">{{ $post->course->name ?? '' }}</div>
                                            </td>
                                            <td>
                                                <div class="flex items-center gap-x-4">
                                                    <img src="{{ $post->user->profile_photo_url }}" alt=""
                                                        class="h-8 w-8 rounded-full bg-gray-800">
                                                    <div class="truncate text-sm font-medium">{{ $post->user->name }}</div>
                                                </div>
                                            </td>
                                            <td>
                                                @if($post->certified_at)
                                                    <div class="badge badge-success gap-1">
                                                        <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="14" viewBox="0 -960 960 960" width="14">
                                                            <path d="m438-452-58-57q-11-11-27.5-11T324-508q-11 11-11 28t11 28l86 86q12 12 28 12t28-12l170-170q12-12 11.5-28T636-592q-12-12-28.5-12.5T579-593L438-452ZM326-90l-58-98-110-24q-15-3-24-15.5t-7-27.5l11-113-75-86q-10-11-10-26t10-26l75-86-11-113q-2-15 7-27.5t24-15.5l110-24 58-98q8-13 22-17.5t28 1.5l104 44 104-44q14-6 28-1.5t22 17.5l58 98 110 24q15 3 24 15.5t7 27.5l-11 113 75 86q10 11 10 26t-10 26l-75 86 11 113q2 15-7 27.5T802-212l-110 24-58 98q-8 13-22 17.5T584-74l-104-44-104 44q-14 6-28 1.5T326-90Z"/>
                                                        </svg>
                                                        {{ __('Certified') }}
                                                    </div>
                                                    <div class="text-xs opacity-50 mt-1">{{ $post->certified_at->format('d/m/Y') }}</div>
                                                @else
                                                    <div class="badge badge-ghost">{{ __('Not certified') }}</div>
                                                @endif
                                            </td>
                                            <td class="flex items-center justify-end gap-2 whitespace-nowrap py-4">
                                                <a href="{{ route('post.public.view', [$post->slug, $post->id]) }}"
                                                    class="btn btn-xs btn-ghost" target="_blank" rel="noopener">
                                                    <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="18" viewBox="0 -960 960 960" width="18">
                                                        <path d="M480-320q75 0 127.5-52.5T660-500q0-75-52.5-127.5T480-680q-75 0-127.5 52.5T300-500q0 75 52.5 127.5T480-320Zm0-72q-45 0-76.5-31.5T372-500q0-45 31.5-76.5T480-608q45 0 76.5 31.5T588-500q0 45-31.5 76.5T480-392Zm0 192q-146 0-266-81.5T40-500q54-137 174-218.5T480-800q146 0 266 81.5T920-500q-54 137-174 218.5T480-200Z"/>
                                                    </svg>
                                                </a>
                                                @if($post->certified_at)
                                                    <button wire:click="uncertifyPost({{ $post->id }})"
                                                        class="btn btn-xs btn-warning"
                                                        wire:confirm="{{ __('Remove certification from this post?') }}">
                                                        <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="18" viewBox="0 -960 960 960" width="18">
                                                            <path d="m710-362-56-56 86-86-86-86 56-56 86 86 86-86 56 56-86 86 86 86-56 56-86-86-86 86ZM326-90l-58-98-110-24q-15-3-24-15.5t-7-27.5l11-113-75-86q-10-11-10-26t10-26l75-86-11-113q-2-15 7-27.5t24-15.5l110-24 58-98q8-13 22-17.5t28 1.5l104 44 104-44q14-6 28-1.5t22 17.5l58 98 110 24q15 3 24 15.5t7 27.5l-11 113 19 22-58 58-17-19-86 86q10 11 10 26t-10 26l-75 86 11 113q2 15-7 27.5T802-212l-110 24-58 98q-8 13-22 17.5T584-74l-104-44-104 44q-14 6-28 1.5T326-90Z"/>
                                                        </svg>
                                                        {{ __('Remove') }}
                                                    </button>
                                                @else
                                                    <button wire:click="certifyPost({{ $post->id }})"
                                                        class="btn btn-xs btn-success">
                                                        <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="18" viewBox="0 -960 960 960" width="18">
                                                            <path d="m438-452-58-57q-11-11-27.5-11T324-508q-11 11-11 28t11 28l86 86q12 12 28 12t28-12l170-170q12-12 11.5-28T636-592q-12-12-28.5-12.5T579-593L438-452ZM326-90l-58-98-110-24q-15-3-24-15.5t-7-27.5l11-113-75-86q-10-11-10-26t10-26l75-86-11-113q-2-15 7-27.5t24-15.5l110-24 58-98q8-13 22-17.5t28 1.5l104 44 104-44q14-6 28-1.5t22 17.5l58 98 110 24q15 3 24 15.5t7 27.5l-11 113 75 86q10 11 10 26t-10 26l-75 86 11 113q2 15-7 27.5T802-212l-110 24-58 98q-8 13-22 17.5T584-74l-104-44-104 44q-14 6-28 1.5T326-90Z"/>
                                                        </svg>
                                                        {{ __('Certify') }}
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-8 opacity-50">
                                                {{ __('No published posts found.') }}
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div class="mt-4">
                                    {{ $posts->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
