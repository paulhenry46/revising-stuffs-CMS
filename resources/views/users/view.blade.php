<x-app-layout>

    <div class="static py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class=" text-sm breadcrumbs mb-2">
      <ul>
    <li><a wire:navigate href="{{route('users.index')}}">
      {{__('Users')}}
    </a></li>
    <li>
      {{$user->name}}
    </li>
  </ul>
</div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
            

<div class=" bg-gray-200 dark:bg-gray-800 bg-opacity-25 gap-6 lg:gap-8 p-6 lg:p-8">
<x-info-message/>

<div>
<div class="">
  <div class="relative z-0 mt-1 flex h-32 w-full justify-center rounded-xl bg-cover bg-gradient-to-r from-blue-600 to-violet-600">
    <div class="absolute -bottom-12 flex h-[88px] w-[88px] items-center justify-center rounded-full border-[4px] border-base-100">
        <img class="h-full w-full rounded-full" src="{{$user->profile_photo_url}}" alt="" />
    </div>
  </div>
  <div class="mt-16 flex flex-col items-center justify-center">
    <h4 class="flex text-primary text-xl font-bold">
    
                              <p class="mr-1">{{$user->name}}</p> @if($user->hasRole('admin')) <p class="flex items-center text-error tooltip" data-tip="Is administrator">
                                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20">
                                  <path d="m438-452-58-57q-11-11-27.5-11T324-508q-11 11-11 28t11 28l86 86q12 12 28 12t28-12l170-170q12-12 11.5-28T636-592q-12-12-28.5-12.5T579-593L438-452ZM326-90l-58-98-110-24q-15-3-24-15.5t-7-27.5l11-113-75-86q-10-11-10-26t10-26l75-86-11-113q-2-15 7-27.5t24-15.5l110-24 58-98q8-13 22-17.5t28 1.5l104 44 104-44q14-6 28-1.5t22 17.5l58 98 110 24q15 3 24 15.5t7 27.5l-11 113 75 86q10 11 10 26t-10 26l-75 86 11 113q2 15-7 27.5T802-212l-110 24-58 98q-8 13-22 17.5T584-74l-104-44-104 44q-14 6-28 1.5T326-90Z" />
                                </svg>
                              </p> @elseif($user->hasRole('moderator')) <p class="flex items-center text-warning tooltip" data-tip="Is moderator">
                                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20">
                                  <path d="m438-452-58-57q-11-11-27.5-11T324-508q-11 11-11 28t11 28l86 86q12 12 28 12t28-12l170-170q12-12 11.5-28T636-592q-12-12-28.5-12.5T579-593L438-452ZM326-90l-58-98-110-24q-15-3-24-15.5t-7-27.5l11-113-75-86q-10-11-10-26t10-26l75-86-11-113q-2-15 7-27.5t24-15.5l110-24 58-98q8-13 22-17.5t28 1.5l104 44 104-44q14-6 28-1.5t22 17.5l58 98 110 24q15 3 24 15.5t7 27.5l-11 113 75 86q10 11 10 26t-10 26l-75 86 11 113q2 15-7 27.5T802-212l-110 24-58 98q-8 13-22 17.5T584-74l-104-44-104 44q-14 6-28 1.5T326-90Z" />
                                </svg>
                              </p> @elseif($user->hasRole('contributor')) <p class="flex items-center text-primary tooltip" data-tip="Is contributor">
                                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20">
                                  <path d="m438-452-58-57q-11-11-27.5-11T324-508q-11 11-11 28t11 28l86 86q12 12 28 12t28-12l170-170q12-12 11.5-28T636-592q-12-12-28.5-12.5T579-593L438-452ZM326-90l-58-98-110-24q-15-3-24-15.5t-7-27.5l11-113-75-86q-10-11-10-26t10-26l75-86-11-113q-2-15 7-27.5t24-15.5l110-24 58-98q8-13 22-17.5t28 1.5l104 44 104-44q14-6 28-1.5t22 17.5l58 98 110 24q15 3 24 15.5t7 27.5l-11 113 75 86q10 11 10 26t-10 26l-75 86 11 113q2 15-7 27.5T802-212l-110 24-58 98q-8 13-22 17.5T584-74l-104-44-104 44q-14 6-28 1.5T326-90Z" />
                                </svg>
                              </p> @endif
                            
    </h4>
  </div>
  <div class="mt-6 mb-3 flex gap-4 md:!gap-14 items-center justify-center">
    <div class="flex flex-col items-center justify-center">
      <h3 class="text-primary text-2xl font-bold"><livewire:posts-user-count :user=$user lazy /></h3>
      <p class="text-gray-500 text-sm font-normal">{{__('Posts')}}</p>
    </div>
    <div class="flex flex-col items-center justify-center">
      <h3 class="text-primary text-2xl font-bold"><livewire:thanks-count :user=$user lazy /></h3>
      <p class="text-gray-500 text-sm font-normal">{{__('Thanks')}}</p>
    </div>
    <div class="flex flex-col items-center justify-center">
      <h3 class="text-primary text-2xl font-bold"><livewire:cards-user-count :user=$user lazy /></h3>
      <p class="text-gray-500 text-sm font-normal">{{__('Cards')}}</p>
    </div>
  </div>






</div>
<h2 class="card-title">{{__('About him/her')}}</h2>
<div class="relative mt-2 flex h-32 w-full  border-primary rounded-xl border-2">
  <div class="ml-4 mt-4">{{$user->bio}}</div>
</div>
  <h2 class="card-title mt-4 mb-4">{{__('Last posts')}}</h2>
</div>

<div class="grid grid-cols-4 gap-4"> 
    @foreach($posts as $post) 
    <div wire:key="post_{{ $post->id }}" class="pt-4 col-span-4 lg:col-span-1">
      <x-post-card :post=$post :starred=false description=false />
    </div> 
    @endforeach 
  </div>

</div>
            </div>
        </div>
    </div>
</x-app-layout>