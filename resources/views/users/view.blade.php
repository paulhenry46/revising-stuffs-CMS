<x-app-layout>
  <div class="static py-4">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class=" text-sm breadcrumbs mb-2">
        <ul>
          <li>
            <a wire:navigate href="{{route('users.index')}}">
              {{__('Users')}}
            </a>
          </li>
          <li>
            {{$user->name}}
          </li>
        </ul>
      </div>
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
      <div class="bg-base-200 dark:bg-base-100 bg-opacity-25 gap-6 lg:gap-8 p-6 lg:p-8">
          <x-info-message />
          <div>
            <div class="">
              <div class="relative z-0 mt-1 flex h-32 w-full justify-center rounded-xl bg-cover bg-primary">
                <div class="absolute -bottom-12 flex h-[88px] w-[88px] items-center justify-center rounded-full border-[4px] border-base-100">
                  <img class="h-full w-full rounded-full" src="{{$user->profile_photo_url}}" alt="" />
                </div>
              </div>
              <div class="mt-16 flex flex-col items-center justify-center">
                <h4 class="flex text-primary text-xl font-bold">
                  <p class="mr-1">{{$user->name}}</p> @if($user->hasRole('admin')) <p class="flex items-center text-error tooltip" data-tip="{{__('Administrator')}}">
                    <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20">
                      <path d="m438-452-58-57q-11-11-27.5-11T324-508q-11 11-11 28t11 28l86 86q12 12 28 12t28-12l170-170q12-12 11.5-28T636-592q-12-12-28.5-12.5T579-593L438-452ZM326-90l-58-98-110-24q-15-3-24-15.5t-7-27.5l11-113-75-86q-10-11-10-26t10-26l75-86-11-113q-2-15 7-27.5t24-15.5l110-24 58-98q8-13 22-17.5t28 1.5l104 44 104-44q14-6 28-1.5t22 17.5l58 98 110 24q15 3 24 15.5t7 27.5l-11 113 75 86q10 11 10 26t-10 26l-75 86 11 113q2 15-7 27.5T802-212l-110 24-58 98q-8 13-22 17.5T584-74l-104-44-104 44q-14 6-28 1.5T326-90Z" />
                    </svg>
                  </p> @elseif($user->hasRole('moderator')) <p class="flex items-center text-warning tooltip" data-tip="{{__('Moderator')}}">
                    <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20">
                      <path d="m438-452-58-57q-11-11-27.5-11T324-508q-11 11-11 28t11 28l86 86q12 12 28 12t28-12l170-170q12-12 11.5-28T636-592q-12-12-28.5-12.5T579-593L438-452ZM326-90l-58-98-110-24q-15-3-24-15.5t-7-27.5l11-113-75-86q-10-11-10-26t10-26l75-86-11-113q-2-15 7-27.5t24-15.5l110-24 58-98q8-13 22-17.5t28 1.5l104 44 104-44q14-6 28-1.5t22 17.5l58 98 110 24q15 3 24 15.5t7 27.5l-11 113 75 86q10 11 10 26t-10 26l-75 86 11 113q2 15-7 27.5T802-212l-110 24-58 98q-8 13-22 17.5T584-74l-104-44-104 44q-14 6-28 1.5T326-90Z" />
                    </svg>
                  </p> @elseif($user->hasRole('contributor')) <p class="flex items-center text-primary tooltip" data-tip="{{__('Contributor')}}">
                    <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20">
                      <path d="m438-452-58-57q-11-11-27.5-11T324-508q-11 11-11 28t11 28l86 86q12 12 28 12t28-12l170-170q12-12 11.5-28T636-592q-12-12-28.5-12.5T579-593L438-452ZM326-90l-58-98-110-24q-15-3-24-15.5t-7-27.5l11-113-75-86q-10-11-10-26t10-26l75-86-11-113q-2-15 7-27.5t24-15.5l110-24 58-98q8-13 22-17.5t28 1.5l104 44 104-44q14-6 28-1.5t22 17.5l58 98 110 24q15 3 24 15.5t7 27.5l-11 113 75 86q10 11 10 26t-10 26l-75 86 11 113q2 15-7 27.5T802-212l-110 24-58 98q-8 13-22 17.5T584-74l-104-44-104 44q-14 6-28 1.5T326-90Z" />
                    </svg>
                  </p> @endif
                </h4> 
                <p>{{$user->curriculum->name}} ({{$user->level->name}})  · {{$user->school->name}}</p>
                @if($user->social_network_link !== NULL) @php $link = $user->social_network_link; @endphp <div class="flex justify-center">
                  <a href="{{$user->social_network_link}}" class=""> @if (str_contains($link, 'instagram.com')) <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24" viewBox="0 0 24 24">
                      <path d="M 8 3 C 5.239 3 3 5.239 3 8 L 3 16 C 3 18.761 5.239 21 8 21 L 16 21 C 18.761 21 21 18.761 21 16 L 21 8 C 21 5.239 18.761 3 16 3 L 8 3 z M 18 5 C 18.552 5 19 5.448 19 6 C 19 6.552 18.552 7 18 7 C 17.448 7 17 6.552 17 6 C 17 5.448 17.448 5 18 5 z M 12 7 C 14.761 7 17 9.239 17 12 C 17 14.761 14.761 17 12 17 C 9.239 17 7 14.761 7 12 C 7 9.239 9.239 7 12 7 z M 12 9 A 3 3 0 0 0 9 12 A 3 3 0 0 0 12 15 A 3 3 0 0 0 15 12 A 3 3 0 0 0 12 9 z"></path>
                    </svg> @elseif (str_contains($link, 'github.com')) <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24" viewBox="0 0 24 24">
                      <path d="M10.9,2.1c-4.6,0.5-8.3,4.2-8.8,8.7c-0.5,4.7,2.2,8.9,6.3,10.5C8.7,21.4,9,21.2,9,20.8v-1.6c0,0-0.4,0.1-0.9,0.1 c-1.4,0-2-1.2-2.1-1.9c-0.1-0.4-0.3-0.7-0.6-1C5.1,16.3,5,16.3,5,16.2C5,16,5.3,16,5.4,16c0.6,0,1.1,0.7,1.3,1c0.5,0.8,1.1,1,1.4,1 c0.4,0,0.7-0.1,0.9-0.2c0.1-0.7,0.4-1.4,1-1.8c-2.3-0.5-4-1.8-4-4c0-1.1,0.5-2.2,1.2-3C7.1,8.8,7,8.3,7,7.6c0-0.4,0-0.9,0.2-1.3 C7.2,6.1,7.4,6,7.5,6c0,0,0.1,0,0.1,0C8.1,6.1,9.1,6.4,10,7.3C10.6,7.1,11.3,7,12,7s1.4,0.1,2,0.3c0.9-0.9,2-1.2,2.5-1.3 c0,0,0.1,0,0.1,0c0.2,0,0.3,0.1,0.4,0.3C17,6.7,17,7.2,17,7.6c0,0.8-0.1,1.2-0.2,1.4c0.7,0.8,1.2,1.8,1.2,3c0,2.2-1.7,3.5-4,4 c0.6,0.5,1,1.4,1,2.3v2.6c0,0.3,0.3,0.6,0.7,0.5c3.7-1.5,6.3-5.1,6.3-9.3C22,6.1,16.9,1.4,10.9,2.1z"></path>
                    </svg> @elseif (str_contains($link, 'tiktok.com')) <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24" viewBox="0 0 24 24">
                      <path d="M 6 3 C 4.3550302 3 3 4.3550302 3 6 L 3 18 C 3 19.64497 4.3550302 21 6 21 L 18 21 C 19.64497 21 21 19.64497 21 18 L 21 6 C 21 4.3550302 19.64497 3 18 3 L 6 3 z M 12 7 L 14 7 C 14 8.005 15.471 9 16 9 L 16 11 C 15.395 11 14.668 10.734156 14 10.285156 L 14 14 C 14 15.654 12.654 17 11 17 C 9.346 17 8 15.654 8 14 C 8 12.346 9.346 11 11 11 L 11 13 C 10.448 13 10 13.449 10 14 C 10 14.551 10.448 15 11 15 C 11.552 15 12 14.551 12 14 L 12 7 z"></path>
                    </svg> @elseif (str_contains($link, 'discord.com')) <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24" viewBox="0 0 24 24">
                      <path d="M21,23l-4.378-3.406L17,21H5c-1.105,0-2-0.895-2-2V5c0-1.105,0.895-2,2-2h14c1.105,0,2,0.895,2,2V23z M16.29,8.57	c0,0-1.23-0.95-2.68-1.06l-0.3,0.61C12.86,8.04,12.4,7.98,12,7.98c-0.41,0-0.88,0.06-1.31,0.14l-0.3-0.61	C8.87,7.66,7.71,8.57,7.71,8.57s-1.37,1.98-1.6,5.84C7.49,15.99,9.59,16,9.59,16l0.43-0.58c-0.44-0.15-0.82-0.35-1.21-0.65	l0.09-0.24c0.72,0.33,1.65,0.53,3.1,0.53s2.38-0.2,3.1-0.53l0.09,0.24c-0.39,0.3-0.77,0.5-1.21,0.65L14.41,16	c0,0,2.1-0.01,3.48-1.59C17.66,10.55,16.29,8.57,16.29,8.57z M10,13.38c-0.52,0-0.94-0.53-0.94-1.18c0-0.65,0.42-1.18,0.94-1.18	s0.94,0.53,0.94,1.18C10.94,12.85,10.52,13.38,10,13.38z M14,13.38c-0.52,0-0.94-0.53-0.94-1.18c0-0.65,0.42-1.18,0.94-1.18	s0.94,0.53,0.94,1.18C14.94,12.85,14.52,13.38,14,13.38z"></path>
                    </svg> @elseif (str_contains($link, 'tg.me')) <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24" viewBox="0 0 24 24">
                      <path d="M 20.302734 2.984375 C 20.013769 2.996945 19.748583 3.080055 19.515625 3.171875 C 19.300407 3.256634 18.52754 3.5814726 17.296875 4.0976562 C 16.06621 4.61384 14.435476 5.2982348 12.697266 6.0292969 C 9.2208449 7.4914211 5.314238 9.1361259 3.3125 9.9785156 C 3.243759 10.007156 2.9645852 10.092621 2.65625 10.328125 C 2.3471996 10.564176 2.0039062 11.076462 2.0039062 11.636719 C 2.0039062 12.088671 2.2295201 12.548966 2.5019531 12.8125 C 2.7743861 13.076034 3.0504903 13.199244 3.28125 13.291016 L 3.28125 13.289062 C 4.0612776 13.599827 6.3906939 14.531938 6.9453125 14.753906 C 7.1420423 15.343433 7.9865895 17.867278 8.1875 18.501953 L 8.1855469 18.501953 C 8.3275588 18.951162 8.4659791 19.243913 8.6582031 19.488281 C 8.7543151 19.610465 8.8690398 19.721184 9.0097656 19.808594 C 9.0637596 19.842134 9.1235454 19.868148 9.1835938 19.892578 C 9.191962 19.896131 9.2005867 19.897012 9.2089844 19.900391 L 9.1855469 19.894531 C 9.2029579 19.901531 9.2185841 19.911859 9.2363281 19.917969 C 9.2652427 19.927926 9.2852873 19.927599 9.3242188 19.935547 C 9.4612233 19.977694 9.5979794 20.005859 9.7246094 20.005859 C 10.26822 20.005859 10.601562 19.710937 10.601562 19.710938 L 10.623047 19.695312 L 12.970703 17.708984 L 15.845703 20.369141 C 15.898217 20.443289 16.309604 21 17.261719 21 C 17.829844 21 18.279025 20.718791 18.566406 20.423828 C 18.853787 20.128866 19.032804 19.82706 19.113281 19.417969 L 19.115234 19.416016 C 19.179414 19.085834 21.931641 5.265625 21.931641 5.265625 L 21.925781 5.2890625 C 22.011441 4.9067171 22.036735 4.5369631 21.935547 4.1601562 C 21.834358 3.7833495 21.561271 3.4156252 21.232422 3.2226562 C 20.903572 3.0296874 20.591699 2.9718046 20.302734 2.984375 z M 19.908203 5.1738281 C 19.799442 5.7198576 17.33401 18.105877 17.181641 18.882812 L 13.029297 15.041016 L 10.222656 17.414062 L 11 14.375 C 11 14.375 16.362547 8.9468594 16.685547 8.6308594 C 16.945547 8.3778594 17 8.2891719 17 8.2011719 C 17 8.0841719 16.939781 8 16.800781 8 C 16.675781 8 16.506016 8.1197812 16.416016 8.1757812 C 15.272368 8.8887854 10.401283 11.664685 8.0058594 13.027344 C 7.8617016 12.96954 5.6973962 12.100458 4.53125 11.634766 C 6.6055146 10.76177 10.161156 9.2658083 13.472656 7.8730469 C 15.210571 7.142109 16.840822 6.4570977 18.070312 5.9414062 C 19.108158 5.5060977 19.649538 5.2807035 19.908203 5.1738281 z M 17.152344 19.025391 C 17.152344 19.025391 17.154297 19.025391 17.154297 19.025391 C 17.154252 19.025621 17.152444 19.03095 17.152344 19.03125 C 17.153615 19.024789 17.15139 19.03045 17.152344 19.025391 z"></path>
                    </svg> @else <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                      <path d="M376-178q-18-33-31.5-68.5T322-320H204q29 50 72.5 87t99.5 55ZM170-400h136q-3-20-4.5-39.5T300-480q0-21 1.5-40.5T306-560H170q-5 20-7.5 39.5T160-480q0 21 2.5 40.5T170-400Zm34-240h118q9-38 22.5-73.5T376-782q-56 18-99.5 55T204-640Zm200 0h152q-12-44-31-83t-45-75q-26 36-45 75t-31 83Zm234 0h118q-29-50-72.5-87T584-782q18 33 31.5 68.5T638-640ZM480-80q-82 0-155-31.5t-127.5-86Q143-252 111.5-325T80-480q0-83 31.5-155.5t86-127Q252-817 325-848.5T480-880q83 0 155.5 31.5t127 86q54.5 54.5 86 127T880-480q0 10-.5 20t-1.5 20h-81q2-10 2.5-19.5t.5-20.5q0-21-2.5-40.5T790-560H654q3 20 4.5 39.5T660-480v20.5q0 9.5-1 19.5h-80q1-10 1-19.5V-480q0-21-1.5-40.5T574-560H386q-3 20-4.5 39.5T380-480q0 21 1.5 40.5T386-400h134v80H404q12 44 31 83t45 75q11-16 21-32.5t19-33.5v146q-10 1-19.5 1.5T480-80Zm318-25L680-223v89h-80v-226h226v80h-90l118 118-56 57Z" />
                    </svg> @endif </a>
                </div> @endif
              </div>
              <div class="mt-6 mb-3 flex gap-4 md:!gap-14 items-center justify-center">
                <div class="flex flex-col items-center justify-center">
                  <h3 class="text-primary text-2xl font-bold">
                    <livewire:stats.user.posts-user-count :user=$user lazy />
                  </h3>
                  <p class="text-gray-500 text-sm font-normal">{{__('Posts')}}</p>
                </div>
                <div class="flex flex-col items-center justify-center">
                  <h3 class="text-primary text-2xl font-bold">
                    <livewire:stats.user.thanks-count :user=$user lazy />
                  </h3>
                  <p class="text-gray-500 text-sm font-normal">{{__('Thanks')}}</p>
                </div>
                <div class="flex flex-col items-center justify-center">
                  <h3 class="text-primary text-2xl font-bold">
                    <livewire:stats.user.cards-user-count :user=$user lazy />
                  </h3>
                  <p class="text-gray-500 text-sm font-normal">{{__('Cards')}}</p>
                </div>
              </div>
            </div>
            <h2 class="card-title">{{__('About me')}}</h2>
            <div class=" mt-2 w-full  border-primary rounded-xl border-2">
              <div class="ml-4 mt-4 mb-4"> {!!nl2br(e($user->bio))!!} </div>
              <div class="border-t-2 border-dashed border-primary"></div>
              <p class="ml-4 mt-4 mb-4">@if(!empty($user->license)) {{__('My posts are licensed under')}}
                <a wire:navigate class="link" href="{{route('about.licensing')}}">{{$user->license}}</a>@else {{__('This user didn\'t chose a license for his/her posts')}} @endif
              </p>
            </div>
            <h2 class="card-title mt-4 mb-4">{{__('Last posts')}}</h2>
          </div>
          <div class="grid grid-cols-4 gap-4"> @foreach($posts as $post) <div wire:key="post_{{ $post->id }}" class="pt-4 col-span-4 lg:col-span-1">
              <x-post-card :post=$post :starred=false description=false showGroup=false/>
            </div> @endforeach </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>