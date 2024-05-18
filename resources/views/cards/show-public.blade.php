<x-app-layout>
    <div>
        <div class="py-12">
              <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                 
<div class="bg-base-200 dark:bg-base-200 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 lg:p-8 bg-base-200 dark:bg-base-200 border-b border-gray-200 dark:border-gray-700">
          <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
              <h1 class="text-2xl font-semibold leading-6 text-gray-900 dark:text-white">{{__('View the cards attached to')}} {{$post->title}}</h1>
            </div>
           <div class="flex items-stretch justify-end mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
           @guest
           <button onclick="csv_guest.showModal()" class=" ml-4 btn btn-primary">
           <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
            <path d="M480-337q-8 0-15-2.5t-13-8.5L308-492q-12-12-11.5-28t11.5-28q12-12 28.5-12.5T365-549l75 75v-286q0-17 11.5-28.5T480-800q17 0 28.5 11.5T520-760v286l75-75q12-12 28.5-11.5T652-548q11 12 11.5 28T652-492L508-348q-6 6-13 8.5t-15 2.5ZM240-160q-33 0-56.5-23.5T160-240v-80q0-17 11.5-28.5T200-360q17 0 28.5 11.5T240-320v80h480v-80q0-17 11.5-28.5T760-360q17 0 28.5 11.5T800-320v80q0 33-23.5 56.5T720-160H240Z"/>
          </svg>
           {{__('CSV Export')}}
      </button>
      @endguest
      @auth
      <a href="{{route('post.public.cards.export', [$post->slug, $post->id])}}" class=" ml-4 btn btn-primary">
           <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
            <path d="M480-337q-8 0-15-2.5t-13-8.5L308-492q-12-12-11.5-28t11.5-28q12-12 28.5-12.5T365-549l75 75v-286q0-17 11.5-28.5T480-800q17 0 28.5 11.5T520-760v286l75-75q12-12 28.5-11.5T652-548q11 12 11.5 28T652-492L508-348q-6 6-13 8.5t-15 2.5ZM240-160q-33 0-56.5-23.5T160-240v-80q0-17 11.5-28.5T200-360q17 0 28.5 11.5T240-320v80h480v-80q0-17 11.5-28.5T760-360q17 0 28.5 11.5T800-320v80q0 33-23.5 56.5T720-160H240Z"/>
          </svg>
           {{__('CSV Export')}}
      </a>
      @endauth
              <a href="{{route('post.public.cards.learn', [$post->slug, $post->id])}}" class=" ml-4 btn btn-primary">{{__('Learn mode')}}</a>
              <a href="{{route('post.public.cards.quiz', [$post->slug, $post->id])}}" class=" ml-4 btn btn-primary">{{__('Quiz Mode')}}</a>
            </div>
            @guest
            <dialog id="csv_guest" class="modal">
  <div class="modal-box">
    <h3 class="font-bold text-lg">{{('Warning')}}</h3>
    <p class="py-4">{{('You must be logged to export cards to CSV.')}}</p>
    <div class="modal-action">
      <form method="dialog">
        <!-- if there is a button in form, it will close the modal -->
        <button class="btn">Close</button>
      </form>
    </div>
  </div>
</dialog>

            @endguest
          </div>
          <div role="alert" class="mt-4 alert alert-success">
  <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
  <span>{{__('To track your progress in learning the cards in this resource, you can use the "Review" mode, which will automatically record your score. Depending on your level of mastery, the site will then suggest that you revisit the cards at different intervals to fix them in your long-term memory. ')}} </br>{{__('You must be logged in to use this function.')}}</span>
</div>
      </div>
      <div class="bg-base-200 dark:bg-base-200 bg-opacity-25 gap-6 lg:gap-8 p-6 lg:p-8">
          <x-info-message/>
      <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center">
          <div class="sm:flex-auto">
            <h1 class="w-1/2 text-base font-semibold leading-6 text-gray-900 dark:text-white">{{__('Cards')}}</h1>
          </div>
        </div>
        <div class="mt-8 flow-root">
          <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
         <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
              <ul class="space-y-8">
                @foreach ($cards as $card)
                <li class="pl-1 pr-1">
                    <div class="relative p-6 shadow-sm dark:ring-0  rounded-xl bg-white dark:bg-base-100">
                        <div class="grid sm:grid-cols-3 space-y-6 sm:space-y-0">
                            <div>
                                <span class="text-xs text-gray-500 uppercase">{{__('Term')}}</span>
                                <p class="gap-3 align-middle flex flex-row swap-off text-center dark:text-white text-gray-900 sm:col-span-2">{!!$card->front!!}</p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500 uppercase">{{__('Definition')}}</span>
                                <p class="gap-3 align-middle flex flex-row swap-off text-center dark:text-white text-gray-900 sm:col-span-2">{!!$card->back!!}</p>
                            </div>
                        </div>
                    </div>
                </li>
                @endforeach
              </ul>
             
            </div>
      </div>
      </div>
        </div>
      </div>
      </div>
                  </div>
              </div>
          </div>
</x-app-layout>