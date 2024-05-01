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
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
               <livewire:edit-primary-files :post=$post :state=$state />
            </div>
        </div>
    </div>
</x-app-layout>
