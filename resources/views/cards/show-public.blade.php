<x-app-layout>
    <x-slot name="header">
    @php
     $breadcrumb = array (
  array(__('Cards'),NULL)
        );
      @endphp
   
     <x-breadcrumb :items=$breadcrumb/>   
    </x-slot>
    <div>
        <div class="py-12">
              <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                  <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                      <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">
          <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
              <h1 class="text-2xl font-semibold leading-6 text-gray-900 dark:text-white">{{__('View the cards attached to')}} {{$post->title}}</h1>
            </div>
           <div class="flex items-stretch justify-end mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
              <a href="{{route('post.public.cards.learn', [$post->slug, $post->id])}}" class=" ml-4 btn btn-primary">{{__('Learn mode')}}</a>
              <a href="{{route('post.public.cards.quiz', [$post->slug, $post->id])}}" class=" ml-4 btn btn-primary">{{__('Quiz Mode')}}</a>
            </div>
          </div>
      </div>
        <div class="bg-gray-200 dark:bg-gray-800 bg-opacity-25 gap-6 lg:gap-8 p-6 lg:p-8">
          <x-info-message/>
      <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center">
          <div class="sm:flex-auto">
            <h1 class="text-base font-semibold leading-6 text-gray-900 dark:text-white">{{__('Cards')}}</h1>
          </div>
        </div>
        <div class="mt-8 flow-root">
          <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
         <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
              <ul class="space-y-8">
                @foreach ($cards as $card)
                <li class="pl-1 pr-1">
                    <div class="relative p-6 shadow-sm ring-1 ring-gray-300 rounded-xl">
                        <div class="grid sm:grid-cols-3 space-y-6 sm:space-y-0">
                            <div>
                                <span class="text-xs text-gray-500 uppercase">{{__('Term')}}</span>
                                <p class="font-medium dark:text-white text-gray-900">{!!$card->front!!}</p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500 uppercase">{{__('Definition')}}</span>
                                <p class="dark:text-white text-gray-900 sm:col-span-2">{!!$card->back!!}</p>
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