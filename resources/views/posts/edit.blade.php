<x-app-layout>
    <x-slot name="header">
    @if($post->id !== 0)
    @php
     $breadcrumb = array (
  array(__('Dashboard'),'dashboard'),
  array(__('Posts'),'posts.index'),
  array(__('Edit'),NULL)
        );
      @endphp
    @else
     @php
     $breadcrumb = array (
  array(__('Dashboard'),'dashboard'),
  array(__('Posts'),'posts.index'),
  array(__('Create'),NULL)
        );
      @endphp
    @endif
   
     <x-breadcrumb :items=$breadcrumb/>   
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">

<h1 class=" text-2xl font-medium text-gray-900 dark:text-white">
    @if($post->id !== 0) {{__('Edit a post')}} @else {{__('Add a new post')}} @endif
</h1>

</div>

<div class="bg-gray-200 dark:bg-gray-800 bg-opacity-25 gap-6 lg:gap-8 p-6 lg:p-8">
<x-info-message/>
<form method="POST" action="@if($post->id !== 0) {{route('posts.update', $post->id)}} @else {{route('posts.store')}} @endif">
@csrf
@if($post->id !== 0) @method('put') @endif
<div class="space-y-12">

<div class="border-b dark:border-white/10 border-gray-900/10 pb-12">
  <h2 class="text-base font-semibold leading-7 text-gray-900 dark:text-white">{{__('General Informations')}}</h2>
  <p class="mt-1 text-sm leading-6 text-gray-600 dark:text-white">{{__('This information is used to classify and recognise your posts.')}}</p>
  <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
    <div class="sm:col-span-3">
      <label for="name" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('Title')}}</label>
      <div class="mt-2">
        <input value="{{ old('title', $post->title) }}" type="text" name="title" id="name" autocomplete="given-name" class="input input-bordered w-full max-w" value="{{ old('name', $post->name) }}">
      </div>
    </div>

  </div>
  @if($post->id !== 0)
  @livewire('level-course-dropdown', ['level'=>old('level_id', $post->level->id), 'course'=>old('course_id', $post->course->id), 'type'=> old('type_id', $post->type_id)])
  @else
  @livewire('level-course-dropdown', ['level'=>old('level_id', NULL), 'course'=>old('course_id', NULL), 'type'=> old('type_id', NULL)])
@endif
</div>
<div class="border-b dark:border-white/10 border-gray-900/10 pb-12">
  <h2 class="text-base font-semibold leading-7 text-gray-900 dark:text-white">{{__('Detailed Informations')}}</h2>
  <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
  <div class="col-span-full">
      <label for="about" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('Description')}}</label>
      <div class="mt-2">
        <textarea id="about" name="description" rows="3" class="textarea textarea-bordered w-full">{{ old('description', $post->description) }}</textarea>
      </div>
    </div>
  </div>

  <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
    <div class="sm:col-span-3">
      <label for="quizlet_url" class="block text-sm font-medium leading-6 text-gray-900 dark:text-white">{{__('Link of an Quizlet Asset')}}</label>
      <div class="mt-2">
        
          <input value="{{ old('quizlet_url', $post->quizlet_url) }}" type="text" name="quizlet_url" id="quizlet_url" class="input input-bordered w-full max-w-xs">
       
      </div>
    </div>
    <div class="sm:col-span-3">
      <fieldset>
<legend class="sr-only">Notifications</legend>
<div class="space-y-5">
<div class="relative flex items-start">
  <div class="flex h-6 items-center">
    <input {{ (old('dark_version', $post->dark_version) == 1 or old('dark_version', $post->dark_version) == 'on') ? 'checked' : '' }} id="dark_version" aria-describedby="dark_version-description" name="dark_version" type="checkbox" checked="checked" class="checkbox checkbox-primary checkbox-sm" />
  </div>
  <div class="ml-3 text-sm leading-6">
    <label for="dark_version" class="font-medium text-gray-900 dark:text-white">{{__('Dark Version')}}</label>
    <p id="dark_version-description" class="text-gray-500 dark:text-gray-400">{{__('You provide a dark version of this ressource.')}}</p>
    <p id="dark_version-description" class="text-error">{{__('If it is checked, you wont be able to upload images of handwritten ressources.')}}</p>
  </div>
</div>
<div class="relative flex items-start">
  <div class="flex h-6 items-center">
    <input {{ (old('public', $post->public) == 'public') ? 'checked' : '' }} id="public" aria-describedby="public-description" name="public" type="checkbox" class="checkbox checkbox-primary checkbox-sm" />

  </div>
  <div class="ml-3 text-sm leading-6">
    <label for="public" class="font-medium text-gray-900 dark:text-white">{{__('Set to Public')}}</label>
    <p id="public-description" class="text-gray-500 dark:text-gray-400">{{__('Mark your post as public if it is not specific to your class/year/school. If in doubt, do not tick.')}}</p>
  </div>
</div>
</div>
</fieldset>
    </div>
</div>
</div>
@can('publish own posts')
<div class="border-b dark:border-white/10 border-gray-900/10 pb-12">
  <h2 class="text-base font-semibold leading-7 text-gray-900 dark:text-white">{{__('Advanced options')}}</h2>
  <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
    <div class="sm:col-span-6">
      <fieldset>
<legend class="sr-only">Notifications</legend>
<div class="space-y-5">
<div class="sm:col-span-3">
<div class="relative flex items-start">
  <div class="flex h-6 items-center">
    <input {{ (old('published', $post->published) === 1) ? 'checked' : '' }} id="published" aria-describedby="dark_version-description" name="published" type="checkbox" class="checkbox checkbox-primary checkbox-sm" />
  </div>
  <div class="ml-3 text-sm leading-6">
    <label for="published" class="font-medium text-gray-900 dark:text-white">{{__('Publish post')}}</label>
    <p id="published-description" class="text-gray-500 dark:text-gray-400">{{__('Publish this article if all is fine.')}}</p>
  </div>
</div>
</div>
@can('manage all posts')
<div class="sm:col-span-3">
<div class="relative flex items-start">
  <div class="flex h-6 items-center">
    <input {{ (old('pinned', $post->pinned) === 1) ? 'checked' : '' }} id="pinned" aria-describedby="public-description" name="pinned" type="checkbox"  class="checkbox checkbox-warning checkbox-sm" />
  </div>
  <div class="ml-3 text-sm leading-6">
    <label for="pinned" class="font-medium text-gray-900 dark:text-white">{{__('Pin post')}}</label>
    <p id="pinned-description" class="text-gray-500 dark:text-gray-400">{{__('Highlight this post on the news page')}}</p>
  </div>
</div>
</div>
<div class="sm:col-span-3">
<label for="name" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('Date')}}</label>
<div class="mt-2">
  <input value="{{ old('date', $post->created_at) }}" type="datetime-local" name="date" id="date" class="input input-bordered max-w">
</div>
</div>
@endcan
</div>
</fieldset>
    </div>
</div>
</div>
@endcan
<div class="mt-6 flex items-center justify-end gap-x-6">
<a href="{{route('posts.index')}}" class="text-sm font-semibold leading-6 text-red-500">{{__('Cancel')}}</a>
<button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">@if($post->id !== 0) {{__('Edit')}} @else {{__('Create')}} @endif</button>
</div>
</form>

</div>

            </div>
        </div>
    </div>
</x-app-layout>
