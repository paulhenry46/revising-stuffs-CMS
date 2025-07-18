<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-base-100 overflow-hidden  sm:rounded-lg">
                <div class="p-6 lg:p-8">
    <h1 class=" decoration-4 underline decoration-success text-2xl font-medium text-gray-900 dark:text-white">
        {{__('Your favorite posts')}}
    </h1>
    @guest
    <div class="mt-2">
    <div class="alert alert-warning">
      <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
      <div>
        <h3 class="font-bold">{{ __('You should consider this.') }}</h3>
        <ul class="mt-3 list-disc list-inside text-sm">
          <li>{{__('You must be connected to view and manage your favorite posts')}}</li>
        </ul>
      </div> 
    </div>
    </div>
    @endguest
</div>
</div>
<div class="grid grid-cols-4 gap-4">
    @auth
@forelse($posts as $post)
<div class="pt-4 col-span-4 lg:col-span-1">
<x-post-card :post=$post :starred=false description=false showGroup=false/>
                                </div>
                                @empty
                                <div class="col-span-4 rounded-box mt-6 dark:border-gray-500 border-base-300 dark:text-gray-500 text-base-content/30 flex h-72 flex-col items-center justify-center gap-6 border-2 border-dashed p-10 text-center text-balance">
                                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="m480-240-168 72q-40 17-76-6.5T200-241v-519q0-33 23.5-56.5T280-840h400q33 0 56.5 23.5T760-760v519q0 43-36 66.5t-76 6.5l-168-72Zm0-88 200 86v-518H280v518l200-86Zm0-432H280h400-200Z"/></svg>
        <div>{{__('You have not bookmarked any posts.')}}</div> 
    </div>
                                @endforelse
                                @endauth
                              </div>
            </div>
            </div>
            @auth
            <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-base-100 overflow-hidden  sm:rounded-lg">
                <div class="p-6 lg:p-8">
    <h1 class=" decoration-4 underline decoration-warning text-2xl font-medium text-gray-900 dark:text-white">
        {{__('Posts that need revision')}}
    </h1>
</div>
</div>
<div class="grid grid-cols-4 gap-4">

@forelse($RevisePosts as $post)
<div class="pt-4 col-span-4 lg:col-span-1">
<x-post-card :post=$post :starred=false description=false showGroup=false/>
                                </div>
                                @empty
                                <div class="col-span-4 rounded-box mt-6 dark:border-gray-500 border-base-300 dark:text-gray-500 text-base-content/30 flex h-72 flex-col items-center justify-center gap-6 border-2 border-dashed p-10 text-center text-balance">
                                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="m108-160 151-420q5-13 15.5-20t22.5-7q8 0 15 3t13 9l270 270q6 6 9 13t3 15q0 12-7 22.5T580-259L160-108q-12 5-23 1.5T118-118q-8-8-11.5-19t1.5-23Zm813-519q-9 9-21 9t-21-9l-3-3q-14-14-35-14t-35 14L603-479q-9 9-21 9t-21-9q-9-9-9-21t9-21l203-203q32-32 77-32t77 32l3 3q9 9 9 21t-9 21ZM399-799q9-9 21-9t21 9l5 5q32 32 32 76t-32 76l-3 3q-9 9-21 9t-21-9q-9-9-9-21t9-21l3-3q14-14 14-34t-14-34l-5-5q-9-9-9-21t9-21Zm162-80q9-9 21-9t21 9l43 43q32 32 32 77t-32 77L523-559q-9 9-21 9t-21-9q-9-9-9-21t9-21l123-123q14-14 14-35t-14-35l-43-43q-9-9-9-21t9-21Zm320 480q-9 9-21 9t-21-9l-43-43q-14-14-35-14t-35 14l-43 43q-9 9-21 9t-21-9q-9-9-9-21t9-21l43-43q32-32 77-32t77 32l43 43q9 9 9 21t-9 21Z"/></svg>
        <div>{{__('You\'ve revised all the posts! You\'re entitled to a break!')}}</div> 
    </div>
                                @endforelse
                                
                              </div>
            </div>
            </div>

            <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-base-100 overflow-hidden  sm:rounded-lg">
                <div class="p-6 lg:p-8">
    <h1 class=" decoration-4 underline decoration-secondary text-2xl font-medium text-gray-900 dark:text-white">
        {{__('Your decks')}}
    </h1>
</div>
</div>
<div class="grid grid-cols-4 gap-4 mt-3">

@forelse($decks as $deck)
        @if($deck->cards->count()>0)
        <div wire:navigate="" href="{{route('decks.show', [$deck->slug, $deck->id])}}" class="cursor-pointer col-span-1 flex rounded-md shadow-xs border-{{$deck->color}}-500 border-2">
            <div class="flex w-16 shrink-0 items-center justify-center bg-{{$deck->color}}-500 rounded-l-smd text-sm font-medium text-white">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="currentColor"><path d="M480-320q75 0 127.5-52.5T660-500q0-75-52.5-127.5T480-680q-75 0-127.5 52.5T300-500q0 75 52.5 127.5T480-320Zm0-72q-45 0-76.5-31.5T372-500q0-45 31.5-76.5T480-608q45 0 76.5 31.5T588-500q0 45-31.5 76.5T480-392Zm0 192q-146 0-266-81.5T40-500q54-137 174-218.5T480-800q146 0 266 81.5T920-500q-54 137-174 218.5T480-200Z"/></svg>
            </div>
            <div class="flex flex-1 items-center justify-between truncate rounded-r-md  dark:bg-base-200 bg-white">
                <div class="flex-1 truncate px-4 py-2 text-sm">
                    <a class="font-medium text-gray-900 dark:text-white">{{ $deck->name }}</a>
                    <p class="text-gray-500">{{ $deck->cards->count() }} {{ ('cards') }}</p>
                </div>
            </div>
        </div>
        @else
        <div class="cursor-pointer col-span-1 flex rounded-md shadow-xs border-{{$deck->color}}-500 border-2">
            <div class="flex w-16 shrink-0 items-center justify-center bg-{{$deck->color}}-500 rounded-l-smd text-sm font-medium text-white">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="currentColor"><path d="M480-320q75 0 127.5-52.5T660-500q0-75-52.5-127.5T480-680q-75 0-127.5 52.5T300-500q0 75 52.5 127.5T480-320Zm0-72q-45 0-76.5-31.5T372-500q0-45 31.5-76.5T480-608q45 0 76.5 31.5T588-500q0 45-31.5 76.5T480-392Zm0 192q-146 0-266-81.5T40-500q54-137 174-218.5T480-800q146 0 266 81.5T920-500q-54 137-174 218.5T480-200Z"/></svg>
            </div>
            <div class="flex flex-1 items-center justify-between truncate rounded-r-md  dark:bg-base-200 bg-white">
                <div class="flex-1 truncate px-4 py-2 text-sm">
                    <a class="font-medium text-gray-900 dark:text-white">{{ $deck->name }}</a>
                    <p class="text-gray-500">{{ $deck->cards->count() }} {{ ('cards') }}</p>
                </div>
            </div>
        </div>
        @endif

                                @empty
                                <div class="col-span-4 rounded-box mt-6 dark:border-gray-500 border-base-300 dark:text-gray-500 text-base-content/30 flex h-72 flex-col items-center justify-center gap-6 border-2 border-dashed p-10 text-center text-balance">
                                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="m108-160 151-420q5-13 15.5-20t22.5-7q8 0 15 3t13 9l270 270q6 6 9 13t3 15q0 12-7 22.5T580-259L160-108q-12 5-23 1.5T118-118q-8-8-11.5-19t1.5-23Zm813-519q-9 9-21 9t-21-9l-3-3q-14-14-35-14t-35 14L603-479q-9 9-21 9t-21-9q-9-9-9-21t9-21l203-203q32-32 77-32t77 32l3 3q9 9 9 21t-9 21ZM399-799q9-9 21-9t21 9l5 5q32 32 32 76t-32 76l-3 3q-9 9-21 9t-21-9q-9-9-9-21t9-21l3-3q14-14 14-34t-14-34l-5-5q-9-9-9-21t9-21Zm162-80q9-9 21-9t21 9l43 43q32 32 32 77t-32 77L523-559q-9 9-21 9t-21-9q-9-9-9-21t9-21l123-123q14-14 14-35t-14-35l-43-43q-9-9-9-21t9-21Zm320 480q-9 9-21 9t-21-9l-43-43q-14-14-35-14t-35 14l-43 43q-9 9-21 9t-21-9q-9-9-9-21t9-21l43-43q32-32 77-32t77 32l43 43q9 9 9 21t-9 21Z"/></svg>
        <div>{{__('You haven\'t created any decks yet. To do so, go to your profile. It\'s useful if you want to make your own sets of flashcards from all those on the site.')}}</div> 
    </div>
                                @endforelse
                                
                              </div>
            </div>
            </div>

            @endauth
        </div>
    </div>
</x-app-layout>


