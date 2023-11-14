<x-app-layout>
    <x-slot name="header">
        @php
     $breadcrumb = array (
  array(__('New posts'),NULL)
);

     @endphp   
     <x-breadcrumb :items=$breadcrumb/>  
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">

<h1 class=" decoration-4 underline decoration-primary text-2xl font-medium text-gray-900 dark:text-white">
    {{__('New posts')}}
</h1>
<p>{{__('See the latest posts created')}}</p>

</div>


</div>
<div class="grid grid-cols-4 gap-4">
@foreach($pinnedPosts as $post)
<div class="pt-4 col-span-4 lg:col-span-1">
<x-post-card :post=$post :starred=false/>
                            </div>
                            @endforeach
@foreach($newPosts as $post)
<div class="pt-4 col-span-4 lg:col-span-1">
<x-post-card :post=$post :starred=false/>
                            </div>
                            @endforeach
                          </div>
            </div>
        </div>
    </div>
<x-nav-bottom :active=0/>
</x-app-layout>
