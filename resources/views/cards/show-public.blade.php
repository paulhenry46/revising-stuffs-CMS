<x-app-layout>
    <div>
        <div class="py-12">
              <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                 
<div class="bg-base-200 dark:bg-base-200 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 lg:p-8 bg-base-200 dark:bg-base-200 border-b border-gray-200 dark:border-gray-700">
          <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
              <h1 class="text-2xl font-semibold leading-6 text-gray-900 dark:text-white">{{__('View the cards attached to')}} <a wire:navigate class="link" href="{{route('post.public.view', [$post->slug, $post->id])}}"> {{$post->title}}</a></h1>
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
                @if(Auth::id()==$post->user_id)
                @foreach ($cards as $card)
                <li class="pl-1 pr-1">
                    <div class="relative shadow-sm dark:ring-0  rounded-xl bg-white dark:bg-base-100">
                        <div class="grid sm:grid-cols-12  sm:space-y-0 ">
                            <div class="py-6 sm:col-span-4 sm:flex sm:justify-center sm:items-center sm:mx-3 px-3 sm:px-0">
                              <div class="sm:min-h-full sm:min-w-full flex justify-center sm:items-center sm:pr-2 sm:border-r-2 dark:border-neutral-500">
                              <p class="gap-3 align-middle /*flex */flex-row swap-off text-center dark:text-white text-gray-900 sm:col-span-2">{!!$card->front!!}</p>
                                
                              </div>
                              
                                <!--<span class="text-xs text-gray-500 uppercase">{{__('Term')}}</span>-->
                                
                              </div>
                              <div class=" sm:hidden px-6">
  <div class=" inset-0 flex items-center" aria-hidden="true">
    <div class="w-full border-t border-gray-300"></div>
  </div>
</div>
                            <div class="py-6 sm:col-span-7 flex justify-center sm:items-center sm:pl-3 px-3 sm:px-0">
                                <!--<span class="text-xs text-gray-500 uppercase">{{__('Definition')}}</span>-->
                                <p class="gap-3 align-middle /*flex*/ flex-row swap-off text-center dark:text-white text-gray-900 sm:col-span-2">{!!$card->back!!}</p>
                            </div>
                            <div class=" sm:col-span-1 sm:flex sm:justify-center sm:items-center ">
                              <div class="grid grid-rows-1 grid-flow-col  min-h-full min-w-full">
                              <div class="  min-h-12 text-primary transition duration-300 dark:hover:text-indigo-800 hover:cursor-pointer hover:text-indigo-400 hover:bg-primary rounded-br-lg sm:rounded-tr-lg sm:rounded-bl-none rounded-bl-lg">
                              <a wire:navigate class="flex justify-center items-center min-h-full min-w-full" href="{{route('cards.edit', [$post->id, $card->id])}}">
                              <!--<span class="text-xs text-gray-500 uppercase">{{__('Term')}}</span>-->
                              <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M200-200h57l391-391-57-57-391 391v57Zm-40 80q-17 0-28.5-11.5T120-160v-97q0-16 6-30.5t17-25.5l505-504q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L313-143q-11 11-25.5 17t-30.5 6h-97Zm600-584-56-56 56 56Zm-141 85-28-29 57 57-29-28Z"/>
                              </svg>
                              </a>
                            </div>
                            <!--<div class=" sm:flex sm:justify-center sm:items-center text-error hover:cursor-pointer hover:text-red-800 hover:bg-error rounded-br-lg">
                              
                              <--<span class="text-xs text-gray-500 uppercase">{{__('Term')}}</span>->
                              <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                      <path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"/>
                    </svg>
                              
                            </div>-->
                            </div>
                            </div>
                        </div>
                    </div>
                </li>
                @endforeach
                @else
                @foreach ($cards as $card)
                <li class="pl-1 pr-1">
                    <div class="relative p-6 shadow-sm dark:ring-0  rounded-xl bg-white dark:bg-base-100">
                        <div class="grid sm:grid-cols-12 sm:divide-x-2 space-y-6 sm:space-y-0 dark:divide-neutral-500">
                            <div class="sm:col-span-4 sm:flex sm:justify-center sm:items-center sm:mx-3">
                              
                                <!--<span class="text-xs text-gray-500 uppercase">{{__('Term')}}</span>-->
                                <p class="gap-3 align-middle /*flex */flex-row swap-off text-center dark:text-white text-gray-900 sm:col-span-2">{!!$card->front!!}</p>
                                
                              </div>
                              <div class=" sm:hidden px-6">
  <div class=" inset-0 flex items-center" aria-hidden="true">
    <div class="w-full border-t border-gray-300"></div>
  </div>
</div>
                            <div class="sm:col-span-8 sm:flex sm:justify-center sm:items-center sm:pl-3">
                                <!--<span class="text-xs text-gray-500 uppercase">{{__('Definition')}}</span>-->
                                <p class="gap-3 align-middle /*flex*/ flex-row swap-off text-center dark:text-white text-gray-900 sm:col-span-2">{!!$card->back!!}</p>
                            </div>
                        </div>
                    </div>
                </li>
                @endforeach
                @endif
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
          <script>
document.addEventListener('livewire:navigated', () => { 
    MathJax.typeset();
})
</script>

</x-app-layout>