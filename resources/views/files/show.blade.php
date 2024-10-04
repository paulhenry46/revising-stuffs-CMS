<x-app-layout>
  <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class=" text-sm breadcrumbs mb-2">
  <ul>
    <li><a wire:navigate href="{{route('dashboard')}}">
      {{__('Dashboard')}}
    </a></li>
    <li><a wire:navigate href="{{route('posts.index')}}">
      {{__('Post : ')}}{{$post->title}}
    </a></li>
    <li>
      {{__('Files')}}</li>
  </ul>
</div>
<div class="bg-white dark:bg-base-200 overflow-hidden sm:rounded-lg">
            <div class="p-6 lg:p-8 bg-white dark:bg-base-200 border-b border-gray-200 dark:border-gray-700">

<h1 class=" text-2xl font-medium text-gray-900 dark:text-white">
    {{__('Manage your files')}}
</h1>

</div>

<div class="bg-white dark:bg-base-200 bg-opacity-25 gap-6 lg:gap-8 p-6 lg:p-8">
<x-info-message />
<div class="px-4 sm:px-6 lg:px-8">
<div class="sm:flex sm:items-center">
<div class="sm:flex-auto">
  <h1 class="text-base font-semibold leading-6 text-gray-900 dark:text-white">{{__('Files')}}</h1>
  <p class="mt-2 text-sm text-gray-700 dark:text-white">{{__('List of the files attached to this post')}} ({{$post->title}})</p>
</div>
<div class="flex items-stretch justify-end mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
  @if ($files->where('type', '=', 'primary light')->count() > 0)
  <a href="{{route('files.edit', $post->id)}}" class="btn btn-primary"><svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="currentColor"><path d="M480-120q-75 0-140.5-28.5t-114-77q-48.5-48.5-77-114T120-480q0-75 28.5-140.5t77-114q48.5-48.5 114-77T480-840q82 0 155.5 35T760-706v-94h80v240H600v-80h110q-41-56-101-88t-129-32q-117 0-198.5 81.5T200-480q0 117 81.5 198.5T480-200q105 0 183.5-68T756-440h82q-15 137-117.5 228.5T480-120Zm112-192L440-464v-216h80v184l128 128-56 56Z"/></svg>{{__('Update Primary file(s)')}}</a>
  @else
  <a href="{{route('files.primary.create', $post->id)}}" class=" btn btn-error "><svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="currentColor"><path d="M480-280q17 0 28.5-11.5T520-320q0-17-11.5-28.5T480-360q-17 0-28.5 11.5T440-320q0 17 11.5 28.5T480-280Zm-40-160h80v-240h-80v240Zm40 360q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z"/></svg><div class="ml-2">{{__('Add Primary file(s)')}}</div></a>
  @endif
  <a href="{{route('files.create', $post->id)}}" class="ml-4 btn btn-neutral"><svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v268q-19-9-39-15.5t-41-9.5v-243H200v560h242q3 22 9.5 42t15.5 38H200Zm0-120v40-560 243-3 280Zm80-40h163q3-21 9.5-41t14.5-39H280v80Zm0-160h244q32-30 71.5-50t84.5-27v-3H280v80Zm0-160h400v-80H280v80ZM720-40q-83 0-141.5-58.5T520-240q0-83 58.5-141.5T720-440q83 0 141.5 58.5T920-240q0 83-58.5 141.5T720-40Zm-20-80h40v-100h100v-40H740v-100h-40v100H600v40h100v100Z"/></svg>{{__('New file')}}</a>
</div>
</div>
<div class="mt-8 flow-root">
<div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
  <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
    <div class="grid md:grid-cols-4 grid-cols-1 gap-4">
    @forelse($files as $file)
    <div>
    <div class="card bg-base-100 border-2 
    @if(str_contains($file->type, 'primary'))
            border-success 
            @elseif(str_contains($file->type, 'card'))
            border-warning
            @else
            border-neutral 
            @endif
    ">
        <div class="card-body">
            <h2 class="card-title">{{ $file->name }}</h2>  
            <div class="justify-end card-actions">
                <a href="{{url('storage/'.$file->file_path.'')}}" class="btn btn-primary">{{__('See')}}</a>
                
                @if(!str_contains($file->type, 'primary'))

<form action="{{route('files.destroy', ['post' => $post->id, 'file' => $file->id])}}" method="POST">
    @csrf
    @method('DELETE')
<button type="submit" class="btn btn-neutral"><svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"/></svg><span class="sr-only">, {{ $file->name }}</span></button></form>
@endif
            </div>
            ID : {{basename($file->file_path)}}
        </div>
    </div>
    </div>
    @empty
    <div class="sm:col-span-4 rounded-box border-base-300 text-base-content/30 flex h-72 flex-col items-center justify-center gap-6 border-2 border-dashed p-10 text-center [text-wrap:balance]">
    <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="currentColor"><path d="M480-280q17 0 28.5-11.5T520-320q0-17-11.5-28.5T480-360q-17 0-28.5 11.5T440-320q0 17 11.5 28.5T480-280Zm-40-160h80v-240h-80v240Zm40 360q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z"/></svg> 
        <div>{{__('No files registered with this post.')}}</div> 
    </div>
    @endforelse
    </div>
    <div class="text-blue-500 text-red-500 text-orange-500 text-green-500 text-yellow-500 text-purple-500  text-pink-500" style="display: none;"></div>
  </div>
</div>
</div>
</div>
</div>
            </div>
        </div>
    </div>
</x-app-layout>
