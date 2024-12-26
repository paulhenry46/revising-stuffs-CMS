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
<div class="bg-white dark:bg-base-100 overflow-hidden shadow-xl sm:rounded-lg">
<div class="p-6 lg:p-8 bg-white dark:bg-base-100 border-b border-gray-200 dark:border-gray-700">

    <h1 class=" text-2xl font-medium text-gray-900 dark:text-white">
        {{__('Manage the cards attached to this post')}}
    </h1>
    

</div>
<div class="bg-white dark:bg-base-100 bg-opacity-25 gap-6 lg:gap-8 p-6 lg:p-8">
    <x-info-message/>
<div class="px-4 sm:px-6 lg:px-8">
  <div class="sm:flex sm:items-center">
    <div class="sm:flex-auto">
      <h1 class="text-base font-semibold leading-6 text-gray-900 dark:text-white">{{__('Cards')}}</h1>
    </div>
   <div class="flex items-stretch justify-end mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
   <button x-show="selection.length > 0" x-on:click="$wire.deleteCards(selection)" class="ml-4 btn btn-error">{{__('Delete')}}</button>
      <a wire:navigate href="{{route('cards.import', $post->id)}}" class=" ml-4 btn btn-primary ">{{__('Import cards')}}</a>
      <a wire:navigate href="{{route('cards.create', $post->id)}}" class=" ml-4 btn btn-primary">{{__('Create cards')}}</a>
      <a @click="MathJax.typeset();" class=" ml-4 btn btn-secondary">
      <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="currentColor"><path d="M160-160v-80h110l-16-14q-52-46-73-105t-21-119q0-111 66.5-197.5T400-790v84q-72 26-116 88.5T240-478q0 45 17 87.5t53 78.5l10 10v-98h80v240H160Zm400-10v-84q72-26 116-88.5T720-482q0-45-17-87.5T650-648l-10-10v98h-80v-240h240v80H690l16 14q49 49 71.5 106.5T800-482q0 111-66.5 197.5T560-170Z"/>
    </svg>{{__('Render LaTeX')}}</a>
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
        <table class="table table-zebra">
          <thead>
            <tr>
              <th></th>
              <th>{{__('Front')}}</th>
              <th>{{__('Back')}}</th>
              <th scope="col" class="relative py-3 pl-3 pr-4 sm:pr-0">
                <span class="sr-only">Edit</span>
              </th>
            </tr>
          </thead>
          <tbody>
            @foreach($cards as $card)
            <tr>
              <td><input value="{{$card->id}}" x-model="selection" type="checkbox" class="checkbox checkbox-success" /></td>
                
                <td>{!! $card->front !!}</td>
                <td>{!! $card->back !!}</td>
                <td class="align-middle">
            <div class="flex items-stretch justify-end relative  text-right">
                    <a href="{{route('cards.edit', [$post->id, $card->id])}}" class="link link-primary mr-4" wire:navigate>
                    <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                    <path d="M160-400v-80h280v80H160Zm0-160v-80h440v80H160Zm0-160v-80h440v80H160Zm360 560v-123l221-220q9-9 20-13t22-4q12 0 23 4.5t20 13.5l37 37q8 9 12.5 20t4.5 22q0 11-4 22.5T863-380L643-160H520Zm300-263-37-37 37 37ZM580-220h38l121-122-18-19-19-18-122 121v38Zm141-141-19-18 37 37-18-19Z"/>
                  </svg>
                    </a>
                    <button x-on:click="$wire.deleteCard({{$card->id}})"  class="link link-error">
                    <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                      <path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"/>
                    </svg>
                    </button>
            </div>
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
<script>
    
    document.addEventListener('livewire:initialized', () => {
      MathJax.typeset();
    })
</script>