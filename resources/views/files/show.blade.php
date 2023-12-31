<x-app-layout>
    <x-slot name="header">
        @php
     $breadcrumb = array (
  array(__('Dashboard'),'dashboard'),
  array(__('Posts'),'posts.index'),
  array(__('Files'),NULL)
);

     @endphp   
     <x-breadcrumb :items=$breadcrumb/> 
        
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <x-files-show :post=$post :files=$files/>
            </div>
        </div>
    </div>
</x-app-layout>
