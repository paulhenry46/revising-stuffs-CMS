<div>
<div x-data="{selection: @entangle('selection')}">
  <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class=" text-sm breadcrumbs mb-2">
  <ul>
    <li><a wire:navigate href="{{route('dashboard')}}">
      {{__('Dashboard')}}
    </a></li>
    <li><a wire:navigate href="{{route('posts.moderate')}}">
      {{__('Moderate posts')}}
    </a></li>
  </ul>
</div>
<div class="bg-white dark:bg-base-100 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 lg:p-8 bg-white dark:bg-base-100 border-b border-gray-200 dark:border-gray-700">

    <h1 class=" text-2xl font-medium text-gray-900 dark:text-white">
        {{__('Moderate posts')}}
    </h1>
    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
        {{__('Validate pending posts and certify published posts.')}}
    </p>
</div>
<div class="bg-white/25 dark:bg-base-100/25 gap-6 lg:gap-8 p-6 lg:p-8">
    <x-info-message/>

    <div role="tablist" class="tabs tabs-bordered mb-6">

        {{-- TAB 1: Posts to validate --}}
        <input type="radio" name="moderate_tabs" role="tab" class="tab"
               aria-label="{{ __('To validate') . ($posts->total() > 0 ? ' (' . $posts->total() . ')' : '') }}"
               checked />
        <div role="tabpanel" class="tab-content p-4">

            <div class="px-4 sm:px-6 lg:px-8">
              <div class="sm:flex sm:items-center">
                <div class="sm:flex-auto">
                  <h2 class="text-base font-semibold leading-6 text-gray-900 dark:text-white">{{__('Posts')}}</h2>
                </div>
              </div>
              <div class="mt-4 flow-root">
                <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                  <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                    <table class="table table-zebra">
                      <thead>
                        <tr>
                            <th></th>
                          <th>{{__('Name')}}</th>
                          <th>{{__('Author')}}</th>
                          <th scope="col" class="flex items-stretch justify-end  relative py-3 pl-3 pr-4 sm:pr-0">
                            <span class="sr-only">Edit</span>
                            <button x-show="selection.length > 0" x-on:click="$wire.validatePosts(selection)" class="btn btn-success">{{__('Validate')}}</button>
                            <button x-show="selection.length > 0" onclick="mass_delete.showModal()" class="ml-4 btn btn-error">{{__('Delete')}}</button>
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        @forelse($posts as $post)
                        <tr>
                            <td><input value="{{$post->id}}" x-model="selection" type="checkbox" class="checkbox checkbox-success" /></td>
                            <td>{{ $post->title }}</td>
                            <td>
                                <div class="flex items-center gap-x-4">
                        <img src="{{$post->user->profile_photo_url}}" alt="" class="h-8 w-8 rounded-full bg-gray-800">
                        <div class="pl-2 truncate text-sm font-medium leading-6">{{ $post->user->name }}</div>
                      </div>
                            </td>
                            <td class="flex items-stretch justify-end relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                                <a href="{{route('post.public.view', [$post->slug, $post->id])}}" class="link link-primary">
                              <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M480-320q75 0 127.5-52.5T660-500q0-75-52.5-127.5T480-680q-75 0-127.5 52.5T300-500q0 75 52.5 127.5T480-320Zm0-72q-45 0-76.5-31.5T372-500q0-45 31.5-76.5T480-608q45 0 76.5 31.5T588-500q0 45-31.5 76.5T480-392Zm0 192q-146 0-266-81.5T40-500q54-137 174-218.5T480-800q146 0 266 81.5T920-500q-54 137-174 218.5T480-200Zm0-300Zm0 220q113 0 207.5-59.5T832-500q-50-101-144.5-160.5T480-720q-113 0-207.5 59.5T128-500q50 101 144.5 160.5T480-280Z"/></svg> <span class="sr-only">, {{ $post->title }}</span></a>
                              <button x-on:click="$wire.validatePost({{ $post->id }})" class="ml-4 link link-success">
                              <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="m424-296 282-282-56-56-226 226-114-114-56 56 170 170Zm56 216q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z"/></svg></button>
                              <button onclick="post_{{ $post->id }}.showModal()" class="ml-4 link link-error">
                                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="m376-300 104-104 104 104 56-56-104-104 104-104-56-56-104 104-104-104-56 56 104 104-104 104 56 56Zm-96 180q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520Zm-400 0v520-520Z"/></svg>
                              </button>
                            </td>
                        </tr>
                        <dialog id="post_{{ $post->id }}" class="modal">
                          <div class="modal-box">
                            <h3 class="font-bold text-lg">{{__('Delete post')}}</h3>
                            <p class="py-4">{{__('Please explain why are you deleteing this post !')}}</p>
                              <form method="dialog">
                                <textarea wire:model="reasons" class="textarea textarea-error w-full" placeholder="Reasons"></textarea>
                              </form>
                                <div class="modal-action">
                                  <form method="dialog">
                                    <button class="btn">Close</button>
                                    </form>
                                  <button x-on:click="$wire.deletePost({{ $post->id }})" class="ml-4 link link-error">
                                    <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="m376-300 104-104 104 104 56-56-104-104 104-104-56-56-104 104-104-104-56 56 104 104-104 104 56 56Zm-96 180q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520Zm-400 0v520-520Z"/></svg>
                                  </button>
                                </div>
                          </div>
                        </dialog>
                        @empty
                        <tr><td class="col-span-full">{{__('All posts are validated for now !')}}</td></tr>
                        @endforelse
                        <dialog id="mass_delete" class="modal">
                          <div class="modal-box">
                            <h3 class="font-bold text-lg">{{__('Delete multiple posts')}}</h3>
                            <p class="py-4">{{__('Please explain why are you deleting this posts !')}}</p>
                            
                              <form method="dialog">
                                <textarea wire:model="mass_reasons" class="textarea textarea-error w-full" placeholder="Reasons"></textarea>
                              </form>
                                <div class="modal-action">
                                  <form method="dialog">
                                    <button class="btn">{{__('Close')}}</button>
                                    </form>
                                  <button x-on:click="$wire.deletePosts(selection)" class="ml-4 link link-error">
                                    <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="m376-300 104-104 104 104 56-56-104-104 104-104-56-56-104 104-104-104-56 56 104 104-104 104 56 56Zm-96 180q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520Zm-400 0v520-520Z"/></svg>
                                  </button>
                                </div>
                          </div>
                        </dialog>
                      </tbody>
                    </table>
                    <div class="mt-4">{{ $posts->links() }}</div>
                  </div>
                </div>
              </div>
            </div>

        </div>{{-- end tab 1 panel --}}

        {{-- TAB 2: Posts to certify --}}
        <input type="radio" name="moderate_tabs" role="tab" class="tab"
               aria-label="{{ __('To certify') . ' (' . $publishedPosts->total() . ')' }}" />
        <div role="tabpanel" class="tab-content p-4">

            <div class="mb-4">
                <label for="certifySearch" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{ __('Search') }}</label>
                <div class="mt-2">
                    <input wire:model.live.debounce.400ms="certifySearch" type="text" id="certifySearch"
                        class="input input-bordered input-primary w-full"
                        placeholder="{{ __('Search by title or description...') }}">
                </div>
            </div>

            <div class="px-4 sm:px-6 lg:px-8">
              <div class="mt-2 flow-root">
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
                        @forelse($publishedPosts as $post)
                        <tr>
                          <td>
                            <div class="font-bold">{{ $post->title }}</div>
                            <div class="text-sm opacity-50">{{ $post->course->name ?? '' }}</div>
                          </td>
                          <td>
                            <div class="flex items-center gap-x-4">
                              <img src="{{ $post->user->profile_photo_url }}" alt="" class="h-8 w-8 rounded-full bg-gray-800">
                              <div class="truncate text-sm font-medium">{{ $post->user->name }}</div>
                            </div>
                          </td>
                          <td>
                            @if($post->certified_at)
                              <div class="badge badge-success gap-1">
                                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="14" viewBox="0 -960 960 960" width="14"><path d="m438-452-58-57q-11-11-27.5-11T324-508q-11 11-11 28t11 28l86 86q12 12 28 12t28-12l170-170q12-12 11.5-28T636-592q-12-12-28.5-12.5T579-593L438-452ZM326-90l-58-98-110-24q-15-3-24-15.5t-7-27.5l11-113-75-86q-10-11-10-26t10-26l75-86-11-113q-2-15 7-27.5t24-15.5l110-24 58-98q8-13 22-17.5t28 1.5l104 44 104-44q14-6 28-1.5t22 17.5l58 98 110 24q15 3 24 15.5t7 27.5l-11 113 75 86q10 11 10 26t-10 26l-75 86 11 113q2 15-7 27.5T802-212l-110 24-58 98q-8 13-22 17.5T584-74l-104-44-104 44q-14 6-28 1.5T326-90Z"/></svg>
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
                              <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="18" viewBox="0 -960 960 960" width="18"><path d="M480-320q75 0 127.5-52.5T660-500q0-75-52.5-127.5T480-680q-75 0-127.5 52.5T300-500q0 75 52.5 127.5T480-320Zm0-72q-45 0-76.5-31.5T372-500q0-45 31.5-76.5T480-608q45 0 76.5 31.5T588-500q0 45-31.5 76.5T480-392Zm0 192q-146 0-266-81.5T40-500q54-137 174-218.5T480-800q146 0 266 81.5T920-500q-54 137-174 218.5T480-200Z"/></svg>
                            </a>
                            @if($post->certified_at)
                              <button wire:click="uncertifyPost({{ $post->id }})"
                                  class="btn btn-xs btn-warning"
                                  wire:confirm="{{ __('Remove certification from this post?') }}">
                                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="18" viewBox="0 -960 960 960" width="18"><path d="m710-362-56-56 86-86-86-86 56-56 86 86 86-86 56 56-86 86 86 86-56 56-86-86-86 86ZM326-90l-58-98-110-24q-15-3-24-15.5t-7-27.5l11-113-75-86q-10-11-10-26t10-26l75-86-11-113q-2-15 7-27.5t24-15.5l110-24 58-98q8-13 22-17.5t28 1.5l104 44 104-44q14-6 28-1.5t22 17.5l58 98 110 24q15 3 24 15.5t7 27.5l-11 113 19 22-58 58-17-19-86 86q10 11 10 26t-10 26l-75 86 11 113q2 15-7 27.5T802-212l-110 24-58 98q-8 13-22 17.5T584-74l-104-44-104 44q-14 6-28 1.5T326-90Z"/></svg>
                                {{ __('Remove') }}
                              </button>
                            @else
                              <button wire:click="certifyPost({{ $post->id }})"
                                  class="btn btn-xs btn-success">
                                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="18" viewBox="0 -960 960 960" width="18"><path d="m438-452-58-57q-11-11-27.5-11T324-508q-11 11-11 28t11 28l86 86q12 12 28 12t28-12l170-170q12-12 11.5-28T636-592q-12-12-28.5-12.5T579-593L438-452ZM326-90l-58-98-110-24q-15-3-24-15.5t-7-27.5l11-113-75-86q-10-11-10-26t10-26l75-86-11-113q-2-15 7-27.5t24-15.5l110-24 58-98q8-13 22-17.5t28 1.5l104 44 104-44q14-6 28-1.5t22 17.5l58 98 110 24q15 3 24 15.5t7 27.5l-11 113 75 86q10 11 10 26t-10 26l-75 86 11 113q2 15-7 27.5T802-212l-110 24-58 98q-8 13-22 17.5T584-74l-104-44-104 44q-14 6-28 1.5T326-90Z"/></svg>
                                {{ __('Certify') }}
                              </button>
                            @endif
                          </td>
                        </tr>
                        @empty
                        <tr>
                          <td colspan="4" class="text-center py-8 opacity-50">{{ __('No published posts found.') }}</td>
                        </tr>
                        @endforelse
                      </tbody>
                    </table>
                    <div class="mt-4">{{ $publishedPosts->links() }}</div>
                  </div>
                </div>
              </div>
            </div>

        </div>{{-- end tab 2 panel --}}

    </div>{{-- end tabs --}}

</div>
</div>
            </div>
        </div>
    </div>
    </div>
