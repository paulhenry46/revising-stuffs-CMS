<div class="mt-6 mb-3 card bg-base-100 dark:bg-base-200 @guest z-0 @endguest">
   @guest
      <div class="absolute inset-0 flex justify-center items-center z-10">
      <div class="alert alert-warning w-1/2">
      <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
      <div>
        <h3 class="font-bold">{{ __('You should consider this.') }}</h3>
        <ul class="mt-3 list-disc list-inside text-sm">
          <li>{{__('To save your progress on this post, you must be connected.')}}</li>
        </ul>
      </div> 
    </div>
    </div>
    @endguest
  <div class="card-body @guest opacity-25 @endguest">
   
    @auth
    <div class="grid grid-cols-1 sm:grid-cols-2 mt-2 md:space-x-3">
      <div>
        <div class='flex mb-4'>
          <span class="card-title rounded-full bg-primary w-12 h-12 flex flex-col justify-center items-center mr-2 text-white"> 1 </span>
          <h3 class="card-title">{{__('Learning')}}</h3>
        </div>
        <div class='grid grid-cols-2'>
          <div>
            <div class='text-center'>
              <div class="relative radial-progress text-gray-200" style="--size:12rem; --value:100;" role="progressbar">
                <div class="absolute radial-progress z-10 @if($learning_percent >=75) text-success @elseif($learning_percent >=40) text-warning @else text-error @endif" style="--size:12rem; --value:{{$learning_percent}};" role="progressbar">
                  {{$learning_percent}}%
                </div>
                <br>
              </div>
              <br>
            </div>
          </div>
          <div class='flex items-center'>
            <div>
              <div class='flex  mt-4'>
                <span class="rounded-full bg-success w-12 flex flex-col items-center mr-2 dark:text-white"> {{ $mastered_cards }} </span>
                {{ __('Learned cards') }}
              </div>
              <div class='flex mt-4'>
                <span class="rounded-full bg-warning w-12 flex flex-col items-center mr-2 dark:text-white"> {{ $learning_cards }} </span>
                {{ __('Learning cards') }}
              </div>
            </div>
          </div>
        </div>
        <div class='flex  mt-4 items-center'>
                @if($learning_percent ==100)
                <svg class='mr-3 h-12' xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960"  fill="currentColor"><path d="m80-80 200-560 360 360L80-80Zm502-378-42-42 224-224q32-32 77-32t77 32l24 24-42 42-24-24q-14-14-35-14t-35 14L582-458ZM422-618l-42-42 24-24q14-14 14-34t-14-34l-26-26 42-42 26 26q32 32 32 76t-32 76l-24 24Zm80 80-42-42 144-144q14-14 14-35t-14-35l-64-64 42-42 64 64q32 32 32 77t-32 77L502-538Zm160 160-42-42 64-64q32-32 77-32t77 32l64 64-42 42-64-64q-14-14-35-14t-35 14l-64 64Z"/>
                </svg>
                {{__('Congrats ! You have learned all the cards ! Now, review them regularly to make sure you don\'t forget them.')}}
                @else
                <svg class='mr-3 h-12' xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960"  fill="currentColor"><path d="M400-240q-33 0-56.5-23.5T320-320v-50q-57-39-88.5-100T200-600q0-117 81.5-198.5T480-880q117 0 198.5 81.5T760-600q0 69-31.5 129.5T640-370v50q0 33-23.5 56.5T560-240H400Zm0 160q-17 0-28.5-11.5T360-120v-40h240v40q0 17-11.5 28.5T560-80H400Z"/>
                </svg>
                {{__('A little more effort! You already know many cards!')}}
                @endif
          </div>
      </div>
      <div class='mt-4 md:mt-0'>
        <div class='flex mb-4'>
          <span class="card-title rounded-full bg-primary w-12 h-12 flex flex-col justify-center items-center mr-2 text-white"> 2 </span>
          <h3 class="card-title">{{__('Memorising')}}</h3>
        </div>
        <div class='grid grid-cols-3 h-48'>
          <div class='grid grid-rows-3 px-8'>
            <div class='row-span-2'>
            </div>
            <div class=' grid grid-rows-2 @if($masteryLevel < 2) bg-base-300 dark:bg-base-100 @else bg-yellow-200 dark:bg-yellow-800  @endif'>
              <div class='@if($masteryLevel >= 3) bg-warning @endif'>
              </div>
              <div class='@if($masteryLevel >= 2) bg-warning @endif'>
              </div>
            </div>
          </div>
          <div class='grid grid-rows-3 px-8'>
            <div class='row-span-1'>
            </div>
            <div class=' row-span-2 grid grid-rows-2 @if($masteryLevel < 4) bg-base-300 dark:bg-base-100 @else bg-green-200 dark:bg-green-800 @endif'>
              <div class='@if($masteryLevel >= 5) bg-green-500 @endif'>
              </div>
              <div class='@if($masteryLevel >= 4) bg-green-500 @endif'>
              </div>
            </div>
          </div>
          <div class='grid grid-rows-4 mx-8 @if($masteryLevel < 6) bg-base-300 dark:bg-base-100 @else bg-emerald-200 dark:bg-emerald-800 @endif' >
              <div class='@if($masteryLevel >= 9) bg-success  @endif'>
              </div>
              <div class='@if($masteryLevel >= 8) bg-success  @endif'>
              </div>
              <div class='@if($masteryLevel >= 7) bg-success  @endif'>
              </div>
              <div class='@if($masteryLevel >= 6) bg-success  @endif'>
              </div>
          </div>
        </div>
        <div class='grid grid-cols-3'>
          <div class='text-center'>
            {{ __('Consolidation') }}
          </div>
          <div class='text-center'>
            {{ __('Medium-term') }}
          </div>
          <div class='text-center'>
            {{ __('Long-term') }}
          </div>
        </div>
        <div class='flex  mt-4 items-center'>
                <span class="@if($masteryLevel >= 6) bg-success @elseif($masteryLevel >= 4) bg-green-500 @else  bg-warning @endif rounded-full w-12 flex flex-col items-center mr-1 dark:text-white"> {{ $masteryLevel }} </span>
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="currentColor"><path d="M579-480 285-774q-15-15-14.5-35.5T286-845q15-15 35.5-15t35.5 15l307 308q12 12 18 27t6 30q0 15-6 30t-18 27L356-115q-15 15-35 14.5T286-116q-15-15-15-35.5t15-35.5l293-293Z"/></svg>
                <span class="@if($masteryLevel +1 >= 6) bg-success @elseif($masteryLevel +1 >= 4) bg-green-500 @else  bg-warning @endif rounded-full w-12 flex flex-col items-center ml-1 mr-4 dark:text-white"> {{ $masteryLevel +1}} </span>
                @if($numberBeforeNextRevision >= 1)
                {{ __('Upgrading to the next level in') }} {{ $numberBeforeNextRevision }} {{ __('days') }}
                @else
                  {{ __('You can now try to upgrade your level. Simply click on Revise button')  }}
                @endif
          </div>
      </div>
    </div>
    @endauth
    @guest
      <div class="grid grid-cols-2 mt-2 space-x-3">
      <div>
        <div class="flex">
          <span class="card-title rounded-full bg-primary w-12 h-12 flex flex-col justify-center items-center mr-2 text-white"> 1 </span>
          <h3 class="card-title">{{ __('Learning') }}</h3>
        </div>
        <div class="grid grid-cols-2">
          <div>
            <div class="text-center">
              <div class="relative radial-progress text-gray-200" style="--size:12rem; --value:100;" role="progressbar">
                <div class="absolute radial-progress z-10  text-success " style="--size:12rem; --value:100;" role="progressbar">
                  100%
                </div>
                <br>
              </div>
              <br>
            </div>
          </div>
          <div class="flex items-center">
            <div>
              <div class="flex  mt-4">
                <span class="rounded-full bg-success w-12 flex flex-col items-center mr-2 dark:text-white">36</span>
                {{ __('Learned cards') }}
              </div>
              <div class="flex mt-4">
                <span class="rounded-full bg-warning w-12 flex flex-col items-center mr-2 dark:text-white">0</span>
                {{ __('Learning cards') }}
              </div>
            </div>
          </div>
        </div>
        <div class="flex  mt-4 items-center">
                <!--[if BLOCK]><![endif]-->                <svg class="mr-3 h-12" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="currentColor"><path d="m80-80 200-560 360 360L80-80Zm502-378-42-42 224-224q32-32 77-32t77 32l24 24-42 42-24-24q-14-14-35-14t-35 14L582-458ZM422-618l-42-42 24-24q14-14 14-34t-14-34l-26-26 42-42 26 26q32 32 32 76t-32 76l-24 24Zm80 80-42-42 144-144q14-14 14-35t-14-35l-64-64 42-42 64 64q32 32 32 77t-32 77L502-538Zm160 160-42-42 64-64q32-32 77-32t77 32l64 64-42 42-64-64q-14-14-35-14t-35 14l-64 64Z"></path>
                </svg>
                {{ __('Congrats ! You have learned all the cards ! Now, review them regularly to make sure you don\'t forget them.') }}
                <!--[if ENDBLOCK]><![endif]-->
          </div>
      </div>
      <div>
        <div class="flex">
          <span class="card-title rounded-full bg-primary w-12 h-12 flex flex-col justify-center items-center mr-2 text-white"> 2 </span>
          <h3 class="card-title">{{ __('Memorising') }}</h3>
        </div>
        <div class="grid grid-cols-3 h-48">
          <div class="grid grid-rows-3 px-8">
            <div class="row-span-2">
            </div>
            <div class=" grid grid-rows-2  bg-yellow-200 dark:bg-yellow-800  ">
              <div class="">
              </div>
              <div class=" bg-warning ">
              </div>
            </div>
          </div>
          <div class="grid grid-rows-3 px-8">
            <div class="row-span-1">
            </div>
            <div class=" row-span-2 grid grid-rows-2  bg-base-300 dark:bg-base-100 ">
              <div class="">
              </div>
              <div class="">
              </div>
            </div>
          </div>
          <div class="grid grid-rows-4 mx-8  bg-base-300 dark:bg-base-100 ">
              <div class="">
              </div>
              <div class="">
              </div>
              <div class="">
              </div>
              <div class="">
              </div>
          </div>
        </div>
        <div class="grid grid-cols-3">
          <div class="text-center">
            {{ __('Consolidation') }}
          </div>
          <div class="text-center">
            {{ __('Medium-term') }}
          </div>
          <div class="text-center">
            {{ __('Long-term') }}
          </div>
        </div>
        <div class="flex  mt-4 items-center">
                <span class="  bg-warning  rounded-full w-12 flex flex-col items-center mr-1 dark:text-white"> 2 </span>
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="currentColor"><path d="M579-480 285-774q-15-15-14.5-35.5T286-845q15-15 35.5-15t35.5 15l307 308q12 12 18 27t6 30q0 15-6 30t-18 27L356-115q-15 15-35 14.5T286-116q-15-15-15-35.5t15-35.5l293-293Z"></path></svg>
                <span class="  bg-warning  rounded-full w-12 flex flex-col items-center ml-1 mr-4 dark:text-white"> 3 </span>
                              {{ __('Upgrading to the next level in') }} 4 {{ __('days') }}
                
          </div>
      </div>
    </div>
    @endguest

    <div class='divider'></div>

     <div class="flow-root">  
    <p class="float-right">
      @auth
    <button class="btn btn-ghost dark:bg-base-100" onclick="modal_reinit.showModal()"><svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="m376-300 104-104 104 104 56-56-104-104 104-104-56-56-104 104-104-104-56 56 104 104-104 104 56 56Zm-96 180q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520Zm-400 0v520-520Z"/></svg>
    {{__('Delete progress')}}</button>
    @endauth
    <button class="btn btn-ghost dark:bg-base-100" onclick="modal_graph.showModal()"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="currentColor"><path d="M680-160q-17 0-28.5-11.5T640-200v-200q0-17 11.5-28.5T680-440h80q17 0 28.5 11.5T800-400v200q0 17-11.5 28.5T760-160h-80Zm-240 0q-17 0-28.5-11.5T400-200v-560q0-17 11.5-28.5T440-800h80q17 0 28.5 11.5T560-760v560q0 17-11.5 28.5T520-160h-80Zm-240 0q-17 0-28.5-11.5T160-200v-360q0-17 11.5-28.5T200-600h80q17 0 28.5 11.5T320-560v360q0 17-11.5 28.5T280-160h-80Z"/></svg>
    {{__('See graph')}}</button>
</p>
</div>
<dialog id="modal_reinit" class="modal">
  <div class="modal-box">
    <h3 class="font-bold text-lg">{{__('Are you sure ?')}}</h3>
    <p class="py-4">{{__('You will lose your progress on this post. We\'ll have to start all over again.')}}</p>
    <div class="modal-action">
      <form method="dialog">
        <!-- if there is a button in form, it will close the modal -->
        <button class="btn">{{__('Close')}}</button> <button wire:click=reinitialize class="ml-2 btn btn-error ">{{__('Reinitialize')}}</button>
      </form>
    </div>
  </div>
</dialog>

<dialog id="modal_graph" class="modal">
  <div class="modal-box">
    <h3 class="font-bold text-lg">{{__('Graph of progress')}}</h3>
    <x-mary-chart wire:model="HistoryChart" class="min-h-60 md:min-h-72" />
    <div class="modal-action">
      <form method="dialog">
        <!-- if there is a button in form, it will close the modal -->
        <button class="btn">{{__('Close')}}</button>
      </form>
    </div>
  </div>
</dialog>

  </div>
</div>
</div>