<x-app-layout>
    <x-slot name="header">
        @php
     $breadcrumb = array (
  array(__('All posts'),NULL),
  array($post->title,NULL)
);

     @endphp   
     <x-breadcrumb :items=$breadcrumb/> 
        
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <x-post-view :post=$post :comments=$comments :events=$events :files=$files/>
            </div>
        </div>
    </div>
<x-nav-bottom :active=1/>
</x-app-layout>
