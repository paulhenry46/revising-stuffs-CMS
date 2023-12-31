<x-app-layout>
    <x-slot name="header">
    @if($file->id !== 0)
    @php
     $breadcrumb = array (
  array(__('Dashboard'),'dashboard'),
  array(__('Posts'),'posts.index'),
  array(__($post->title),'posts.index'),
  array(__('Edit files'),NULL)
        );
      @endphp
    @else
     @php
     $breadcrumb = array (
  array(__('Dashboard'),'dashboard'),
  array(__('Posts'),'posts.index'),
  array(__($post->title),'posts.index'),
  array(__('Create files'),NULL)
        );
      @endphp
    @endif
   
     <x-breadcrumb :items=$breadcrumb/>   
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
               <x-files-edit :post=$post :file=$file/>
            </div>
        </div>
    </div>
</x-app-layout>
