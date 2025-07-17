<x-app-layout>
    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if($file->id == 0)
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
      {{__('New file')}}
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
      {{__('Edit complementary files')}}
    </li>
  </ul>
</div>
@endif
<div class="bg-base-200 dark:bg-base-200 overflow-hidden shadow-xl sm:rounded-lg">
<div class="p-6 lg:p-8 bg-base-200 dark:bg-base-200 border-b border-gray-200 dark:border-gray-700">

<h1 class=" text-2xl font-medium text-gray-900 dark:text-white">
     {{__('Add a new file')}}
</h1>

</div>

<div class="bg-base-200/25 dark:bg-base-200/25 gap-6 lg:gap-8 p-6 lg:p-8">
<x-info-message/>

<form method="POST" action="@if($file->id !== 0) {{route('files.update', ['post' => $post->id, 'file' => $file->id])}} @else {{route('files.store', $post->id)}} @endif" enctype="multipart/form-data">
@csrf
<div class="space-y-12">
<div class="border-b dark:border-white/10 border-gray-900/10 pb-12">
  <h2 class="text-base font-semibold leading-7 text-gray-900 dark:text-white">{{__('General Informations')}}</h2>
  <p class="mt-1 text-sm leading-6 text-gray-600 dark:text-white">{{__('This information is used to classify and recognise your file.')}}</p>
  <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
    <div class="sm:col-span-3">


    <label class="form-control w-full ">
  <div class="label">
    <span class="label-text">{{__('Type')}}</span>
  </div>
  <select id="type" name="type" class="select select-bordered">
  <option  @if(old('type') == 'source') selected @endif value="source">{{__('Source File')}}</option>
  <option  @if(old('type') == 'exercise') selected @endif value="exercise">{{__('Exercise')}}</option>
  <option  @if(old('type') == 'exercise correction') selected @endif value="exercise correction">{{__('Exercise correction')}}</option>
  <option  @if(old('type') == 'card image') selected @endif value="card image">{{__('Card image')}}</option>
  <option  @if(old('type') == 'other') selected @endif value="other">{{__('Other')}}</option>
  </select>
</label>
    </div>
    <div class="sm:col-span-3">
      <label class="form-control w-full ">
  <div class="label">
    <span class="label-text">{{__('Title')}}</span>
  </div>
  <input name="name" id="name" value="{{ old('name', $file->name) }}" type="text" placeholder="Your title" class="input input-bordered w-full " />
</label>
    </div>
  </div>
</div>
<div class="border-b dark:border-white/10 border-gray-900/10 pb-12">
  <h2 class="text-base font-semibold leading-7 text-gray-900 dark:text-white">{{__('Upload your file')}}</h2>
  <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
  <div class="col-span-full">
      <label for="file" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('Your file')}}</label>
      <div class="mt-2">
        <input type="file" name="file" class="file-input file-input-sm file-input-bordered w-full max-w-xs" />
      </div>
    </div>
  </div>
</div>
  </div>
  <div class="mt-6 flex items-center justify-end gap-x-6">
<a href="{{route('files.index', $post->id)}}" class="link">{{__('Cancel')}}</a>
<button type="submit" class="btn btn-primary">{{__('Create')}}</button>
</div>
</div>


</form>
</div>

            </div>
        </div>
    </div>
</x-app-layout>
