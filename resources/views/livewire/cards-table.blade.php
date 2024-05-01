<div x-data="{selection: @entangle('selection')}">
  <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class=" text-sm breadcrumbs mb-2">
  <ul>
    <li><a wire:navigate href="{{route('dashboard')}}">
      {{__('Dashboard')}}
    </a></li>
    <li><a wire:navigate href="{{route('posts.index')}}">
      {{__('Post : ')}}{{$post->title}}
    </a></li>
    <li>
      {{__('Cards')}}
    </li>
  </ul>
</div>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">

    <h1 class=" text-2xl font-medium text-gray-900 dark:text-white">
        {{__('Manage the cards attached to this post')}}
    </h1>
    

</div>
  <div class="bg-gray-200 dark:bg-gray-800 bg-opacity-25 gap-6 lg:gap-8 p-6 lg:p-8">
    <x-info-message/>
<div class="px-4 sm:px-6 lg:px-8">
  <div class="sm:flex sm:items-center">
    <div class="sm:flex-auto">
      <h1 class="text-base font-semibold leading-6 text-gray-900 dark:text-white">{{__('Cards')}}</h1>
    </div>
   <div class="flex items-stretch justify-end mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
      <a wire:navigate href="{{route('cards.import', $post->id)}}" class=" ml-4 btn bg-indigo-600 text-white  hover:bg-indigo-500 ">{{__('Import cards')}}</a>
      <a wire:navigate href="{{route('cards.create', $post->id)}}" class=" ml-4 btn bg-indigo-600 text-white  hover:bg-indigo-500 ">{{__('Create cards')}}</a>
    </div>
  </div>
<div class="sm:col-span-2">
          <label for="name" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('Search')}}</label>
          <div class="mt-2">
            <input wire:model.live.debounce.400ms="search" type="text" name="title" id="name" autocomplete="given-name" class="input input-bordered input-primary w-full">
          </div>
        </div>
  <div class="mt-8 flow-root">
    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
   <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
        <table class="min-w-full divide-y divide-gray-300">
          <thead>
            <tr>
              <th></th>
              <th scope="col" class="py-3 pl-4 pr-3 text-left text-xs font-medium uppercase tracking-wide dark:text-gray-100 text-gray-500 sm:pl-0">ID</th>
              <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide dark:text-gray-100 text-gray-500">{{__('Front')}}</th>
              <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide dark:text-gray-100 text-gray-500">{{__('Back')}}</th>
              <th scope="col" class="relative py-3 pl-3 pr-4 sm:pr-0">
                <span class="sr-only">Edit</span>
                <button x-show="selection.length > 0" x-on:click="$wire.deleteCards(selection)" class="text-red-600 hover:text-red-300">{{__('Delete')}}</button>
              </th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 dark:bg-gray-800 bg-white">
            @foreach($cards as $card)
            <tr>
              <td><input value="{{$card->id}}" x-model="selection" type="checkbox" class="checkbox checkbox-success" /></td>
                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium dark:text-gray-100 text-gray-900 sm:pl-0">{{ $card->id }}</td>
                <td class="whitespace-nowrap px-3 py-4 text-sm dark:text-gray-100 text-gray-500">{!! $card->front !!}</td>
                <td class="whitespace-nowrap px-3 py-4 text-sm dark:text-gray-100 text-gray-500">{!! $card->back !!}</td>
                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                    <a href="{{route('cards.edit', [$post->id, $card->id])}}" class="text-indigo-600 hover:text-indigo-400" wire:navigate>{{__('Edit')}}</a>
                    <button x-on:click="$wire.deleteCard({{$card->id}})" type="submit" class="text-red-600 hover:text-red-300">{{__('Delete')}}</button>
                </td>
            </tr>
            @endforeach
          </tbody>
        </table>
        {{$cards->links()}}
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
            </div></div>
