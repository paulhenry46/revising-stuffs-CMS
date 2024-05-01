<div>
<div x-data="{selection: @entangle('selection')}">
  <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class=" text-sm breadcrumbs mb-2">
  <ul>
    <li><a wire:navigate href="{{route('dashboard')}}">
      {{__('Dashboard')}}
    </a></li>
    <li><a wire:navigate href="{{route('posts.index')}}">
      {{__('Posts which need to be validated')}}
    </a></li>
  </ul>
</div>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">

    <h1 class=" text-2xl font-medium text-gray-900 dark:text-white">
        {{__('Posts which need to be validated')}}
    </h1>

</div>
<div class="bg-gray-200 dark:bg-gray-800 bg-opacity-25 gap-6 lg:gap-8 p-6 lg:p-8">
    <x-info-message/>
<div class="px-4 sm:px-6 lg:px-8">
  <div class="sm:flex sm:items-center">
    <div class="sm:flex-auto">
      <h1 class="text-base font-semibold leading-6 text-gray-900 dark:text-white">{{__('Posts')}}</h1>
    </div>
  </div>
  <div class="mt-8 flow-root">
    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
      <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
        <table class="min-w-full divide-y divide-gray-300">
          <thead>
            <tr>
                <th></th>
              <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide dark:text-gray-100 text-gray-500">{{__('Name')}}</th>
              <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide dark:text-gray-100 text-gray-500">{{__('Author')}}</th>
              <th scope="col" class="flex items-stretch justify-end  relative py-3 pl-3 pr-4 sm:pr-0">
                <span class="sr-only">Edit</span>
                <button x-show="selection.length > 0" x-on:click="$wire.validatePosts(selection)" class="btn btn-success">{{__('Validate')}}</button>
                <button x-show="selection.length > 0" onclick="mass_delete.showModal()" class="ml-4 btn btn-error">{{__('Delete')}}</button>
              </th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 dark:bg-gray-800 bg-white">
            @forelse($posts as $post)
            <tr>
                <td><input value="{{$post->id}}" x-model="selection" type="checkbox" class="checkbox checkbox-success" /></td>
                <td class="whitespace-nowrap px-3 py-4 text-sm dark:text-gray-100 text-gray-500">{{ $post->title }}</td>
                <td class="whitespace-nowrap px-3 py-4 text-sm dark:text-gray-100 text-gray-500">
                    
                    <div class="flex items-center gap-x-4">
            <img src="{{$post->user->profile_photo_url}}" alt="" class="h-8 w-8 rounded-full bg-gray-800">
            <div class="pl-2 truncate text-sm font-medium leading-6 text-white">{{ $post->user->name }}</div>
          </div>
                    
                </td>
                <td class="flex items-stretch justify-end relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                    <a href="{{route('post.public.view', [$post->slug, $post->id])}}" class="text-indigo-600 hover:text-indigo-400">

                  <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M480-320q75 0 127.5-52.5T660-500q0-75-52.5-127.5T480-680q-75 0-127.5 52.5T300-500q0 75 52.5 127.5T480-320Zm0-72q-45 0-76.5-31.5T372-500q0-45 31.5-76.5T480-608q45 0 76.5 31.5T588-500q0 45-31.5 76.5T480-392Zm0 192q-146 0-266-81.5T40-500q54-137 174-218.5T480-800q146 0 266 81.5T920-500q-54 137-174 218.5T480-200Zm0-300Zm0 220q113 0 207.5-59.5T832-500q-50-101-144.5-160.5T480-720q-113 0-207.5 59.5T128-500q50 101 144.5 160.5T480-280Z"/></svg> <span class="sr-only">, {{ $post->name }}</span></a>
                  <button x-on:click="$wire.validatePost({{ $post->id }})" class="ml-4 text-green-600 hover:text-green-400">
                  <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="m424-296 282-282-56-56-226 226-114-114-56 56 170 170Zm56 216q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z"/></svg></button>
                  <button onclick="post_{{ $post->id }}.showModal()" class="ml-4 text-red-600 hover:text-red-400">
                    <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="m376-300 104-104 104 104 56-56-104-104 104-104-56-56-104 104-104-104-56 56 104 104-104 104 56 56Zm-96 180q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520Zm-400 0v520-520Z"/></svg>
                  </button>
                   
                </td>
            </tr>
            <dialog id="post_{{ $post->id }}" class="modal">
              <div class="modal-box">
                <h3 class="font-bold text-lg">Delete post</h3>
                <p class="py-4">Please explain why are you deleteing this post !</p>
                
                  <form method="dialog">
                    <textarea wire:model="reasons" class="textarea textarea-error w-full" placeholder="Reasons"></textarea>
                  </form>
                    <div class="modal-action">
                      <form method="dialog">
                        <!-- if there is a button in form, it will close the modal -->
                        <button class="btn">Close</button>
                        </form>
                      <button x-on:click="$wire.deletePost({{ $post->id }})" class="ml-4 text-red-600 hover:text-red-400">
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
                <h3 class="font-bold text-lg">Delete multiple posts</h3>
                <p class="py-4">Please explain why are you deleting this posts !</p>
                
                  <form method="dialog">
                    <textarea wire:model="mass_reasons" class="textarea textarea-error w-full" placeholder="Reasons"></textarea>
                  </form>
                    <div class="modal-action">
                      <form method="dialog">
                        <!-- if there is a button in form, it will close the modal -->
                        <button class="btn">Close</button>
                        </form>
                      <button x-on:click="$wire.deletePosts(selection)" class="ml-4 text-red-600 hover:text-red-400">
                        <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="m376-300 104-104 104 104 56-56-104-104 104-104-56-56-104 104-104-104-56 56 104 104-104 104 56 56Zm-96 180q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520Zm-400 0v520-520Z"/></svg>
                      </button>
                    </div>
              </div>
            </dialog>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
</div>
</div>

            </div>
        </div>
    </div>
    <div style="display: none;"><div role="status" class="max-w-md p-4 space-y-4 border border-gray-200 divide-y divide-gray-200 rounded shadow animate-pulse dark:divide-gray-700 md:p-6 dark:border-gray-700">
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