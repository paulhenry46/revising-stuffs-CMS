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
                <x-posts-news :newPosts=$newPosts :pinnedPosts=$pinnedPosts/>
            </div>
        </div>
    </div>
<x-nav-bottom :active=0/>
</x-app-layout>
