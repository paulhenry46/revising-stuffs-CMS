<x-app-layout>
    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class=" text-sm breadcrumbs mb-2">
  <ul>
    <li><a wire:navigate href="{{route('dashboard')}}">
      {{__('Dashboard')}}
    </a></li>
    <li><a wire:navigate href="{{route('posts.index')}}">
      {{__('Posts')}}
    </a></li>
  </ul>
</div>
<div class="col-span-6 sm:col-span-4 pb-3">
                            <div class="pt-2">
                                <div class="stats shadow w-full sm:rounded-lg">
                                <div class="stat">
                                      <div class="stat-figure text-primary">
                                      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="currentColor" class="inline-block w-10 h-10 stroke-current"><path d="M320-280h320q17 0 28.5-11.5T680-320q0-17-11.5-28.5T640-360H320q-17 0-28.5 11.5T280-320q0 17 11.5 28.5T320-280Zm120-274-36-35q-11-11-27.5-11T348-588q-11 11-11 28t11 28l104 104q12 12 28 12t28-12l104-104q11-11 11.5-27.5T612-588q-11-11-27.5-11.5T556-589l-36 35v-126q0-17-11.5-28.5T480-720q-17 0-28.5 11.5T440-680v126Zm40 474q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Z"/></svg>
                                      </div>
                                      <div class="stat-title">{{__('Total Downloads')}}</div>
                                      <div class="stat-value text-primary"><livewire:stats.user.posts-downloads-user-count :user=Auth::user() lazy /></div>
                                      
                                    </div>
                                    <div class="stat">
                                      <div class="stat-figure text-primary">
                                      <svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 -960 960 960" fill="currentColor" class="inline-block w-10 h-10 stroke-current"><path d="M480-147q-14 0-28.5-5T426-168l-69-63q-106-97-191.5-192.5T80-634q0-94 63-157t157-63q53 0 100 22.5t80 61.5q33-39 80-61.5T660-854q94 0 157 63t63 157q0 115-85 211T602-230l-68 62q-11 11-25.5 16t-28.5 5Z"/></svg>
                                      </div>
                                      <div class="stat-title">{{__('Total Thanks')}}</div>
                                      <div class="stat-value text-primary"><livewire:stats.user.thanks-count :user=Auth::user() lazy /></div>
                                      
                                    </div>
                                    
                                    <div class="stat">
                                      <div class="stat-figure text-secondary">
                                      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="currentColor" class="inline-block w-10 h-10 stroke-current"><path d="M360-360H236q-24 0-35.5-21.5T203-423l299-430q10-14 26-19.5t33 .5q17 6 25 21t6 32l-32 259h155q26 0 36.5 23t-6.5 43L416-100q-11 13-27 17t-31-3q-15-7-23.5-21.5T328-139l32-221Z"/></svg>
                                      </div>
                                      <div class="stat-title">{{__('Published posts')}}</div>
                                      <div class="stat-value text-secondary"><livewire:stats.user.posts-user-count :user=Auth::user() lazy /></div>
                                      
                                    </div>
                                    
                                    <div class="stat">
                                        <div class="stat-figure text-success">
                                            <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" class="inline-block w-10 h-10 stroke-current" viewBox="0 -960 960 960" width="30"><path d="m615-395 31-112q4-12-.5-24T630-551l-95-65q-8-6-17.5-2.5T505-605l-31 112q-4 12 .5 24t15.5 20l95 65q8 6 17.5 2.5T615-395ZM160-207l-33-16q-31-13-42-44.5t3-62.5l72-156v279Zm160 87q-33 0-56.5-24T240-201v-239l107 294q3 7 5 13.5t7 12.5h-39Zm206-5q-31 11-62-3t-42-45L245-662q-11-31 3-61.5t45-41.5l301-110q31-11 61.5 3t41.5 45l178 489q11 31-3 61.5T827-235L526-125Z"/></svg>
                                        </div>
                                        <div class="stat-title">{{__('Published cards')}}</div>
                                        <div class="stat-value text-success"><livewire:stats.user.cards-user-count :user=Auth::user() lazy /></div>
                                        
                                      </div>
                                    
                                  </div>
                        </div>
</div>
            <livewire:posts-user-table :user=Auth::user() lazy />
        </div>
    </div>
</x-app-layout>
