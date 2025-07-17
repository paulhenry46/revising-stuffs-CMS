<x-app-layout>
    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if($state !== 'update')
      <div class=" text-sm breadcrumbs mb-2">
  <ul>
    <li><a wire:navigate href="{{route('dashboard')}}">
      {{__('Dashboard')}}
    </a></li>
    <li><a wire:navigate href="{{route('posts.index')}}">
      {{__('Post : ')}}{{$post->title}}
    </a></li>
    <li><a wire:navigate href="{{route('files.index', $post->id)}}">
      {{__('Files')}}
    </a></li>
    <li>
      {{__('New Primary file')}}
    </li>
  </ul>
</div>
@else 
<div class=" text-sm breadcrumbs mb-2">
  <ul>
  <li><a wire:navigate href="{{route('dashboard')}}">
      {{__('Dashboard')}}
    </a></li>
    <li><a wire:navigate href="{{route('posts.index')}}">
      {{__('Post : ')}}{{$post->title}}
    </a></li>
    <li><a wire:navigate href="{{route('files.index', $post->id)}}">
      {{__('Files')}}
    </a></li>
    <li>
      {{__('Edit Primary files')}}
    </li>
  </ul>
</div>
@endif
<div class="bg-base-200 dark:bg-base-200 overflow-hidden shadow-xl sm:rounded-lg">
<div class="p-6 lg:p-8 bg-base-200 dark:bg-base-200 border-b border-gray-200 dark:border-gray-700">
            <h1 class=" text-2xl font-medium text-gray-900 dark:text-white"> @if($state == 'update')  {{__('Edit Primary files')}} @else {{__('New Primary file')}} @endif </h1>
          </div>
            <div class="bg-base-200/25 dark:bg-base-200/25 gap-6 lg:gap-8 p-6 lg:p-8">
               <livewire:edit-primary-files :post=$post :state=$state />
            </div>
        </div>
    </div>
    </div>
</x-app-layout>
