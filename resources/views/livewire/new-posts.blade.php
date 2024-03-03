<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
  <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
    <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">
      <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
          <h1 class=" decoration-4 underline decoration-primary text-2xl font-medium text-gray-900 dark:text-white">
            {{__('New posts')}}
          </h1>
          <p>{{__('See the latest posts created')}}</p>
        </div> 
        @auth 
        @if(env('FirebasePush') == true) <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none justify-center">
          <button onclick="initFirebaseMessagingRegistration()" class="btn btn-primary">{{__('Notifications Push')}}</button>
        </div> 
        @endif <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none justify-center">
          <a href="{{route('rss.user', auth()->id())}}" class="btn btn-warning">{{__('Your RSS feed')}}</a>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none justify-center">
          <label class="cursor-pointer label">
            <span class="label-text mr-4">{{__('See only posts of your courses')}}</span>
            <input wire:model.live="restricted" type="checkbox" class="toggle toggle-primary">
          </label>
        </div> 
        @endauth 
        @guest 
        @if(env('FirebasePush') == true) <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none justify-center">
          <button onclick="my_modal_1.showModal()" class=" btn btn-primary">{{__('Notifications Push')}}</button>
        </div> 
        @endif <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none justify-center">
          <button onclick="my_modal_1.showModal()" class=" btn btn-primary">{{__('Your RSS Feed')}}</button>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none justify-center">
          <label class="cursor-pointer label">
            <span class="label-text mr-4">{{__('See only posts of your courses')}}</span>
            <input onclick="my_modal_1.showModal()" type="checkbox" class=" toggle toggle-primary">
          </label>
        </div>
        <dialog id="my_modal_1" class="modal">
          <div class="">
            <div class="alert alert-warning">
              <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
              <div>
                <h3 class="font-bold">{{ __('You should consider this.') }}</h3>
                <ul class="mt-3 list-disc list-inside text-sm">
                  <li>{{__('You must be connected and chose your courses and levels.')}}</li>
                </ul>
              </div>
              <div class="modal-action">
                <form method="dialog">
                  <!-- if there is a button in form, it will close the modal -->
                  <button class="btn">Close</button>
                </form>
              </div>
            </div>
          </div>
        </dialog> 
        @endguest
      </div>
    </div>
  </div>
  <div class="grid grid-cols-4 gap-4"> 
    @foreach($posts as $post) 
    <div wire:key="post_{{ $post->id }}" class="pt-4 col-span-4 lg:col-span-1">
      <x-post-card :post=$post :starred=false />
    </div> 
    @endforeach 
  </div>