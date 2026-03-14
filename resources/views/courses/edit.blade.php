<x-app-layout>
    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if($course->id == 0)
      <div class=" text-sm breadcrumbs mb-2">
  <ul>
    <li><a wire:navigate href="{{route('dashboard')}}">
      {{__('Dashboard')}}
    </a></li>
    <li><a wire:navigate href="{{route('courses.index')}}">
      {{__('Courses')}}
    </a></li>
    <li>
      {{__('New course')}}
    </li>
  </ul>
</div>
@else 
<div class=" text-sm breadcrumbs mb-2">
  <ul>
    <li><a wire:navigate href="{{route('dashboard')}}">
      {{__('Dashboard')}}
    </a></li>
    <li><a wire:navigate href="{{route('courses.index')}}">
      {{__('Posts')}}
    </a></li>
    <li>
      {{__('Edit course : ')}}{{$course->name}}
    </li>
  </ul>
</div>
@endif
<div class="bg-base-200 dark:bg-base-200 overflow-hidden shadow-xl sm:rounded-lg">
<div class="p-6 lg:p-8 bg-base-200 dark:bg-base-200 border-b border-gray-200 dark:border-gray-700">

<h1 class=" text-2xl font-medium text-gray-900 dark:text-white">
    @if($course->id !== 0) {{__('Edit a course')}} @else {{__('Add a new course')}} @endif
</h1>

</div>

<div class="bg-base-200/25 dark:bg-base-200/25 gap-6 lg:gap-8 p-6 lg:p-8">
<x-info-message/>
<form method="POST" action="@if($course->id !== 0) {{route('courses.update', $course->id)}} @else {{route('courses.store')}} @endif">
@csrf
@if($course->id !== 0) @method('put') @endif
<div class="space-y-12">

<div class="border-b border-gray-900/10 pb-12">
  <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
    <div class="sm:col-span-3">
      <label for="name" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('Name')}}</label>
      <div class="mt-2">
        <input type="text" name="name" id="name" autocomplete="given-name" class="input input-bordered max-w w-full" value="{{ old('name', $course->name) }}">
      </div>
    </div>

    <div class="sm:col-span-3">
      <x-colors-selector :course=$course/>
    </div>
    <div class="sm:col-span-3">
    <label for="lang" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('Language')}}</label>
      <div class="mt-2">
        <input type="text" name="lang" id="lang" class="input input-bordered max-w w-full" value="{{ old('lang', $course->lang) }}">
      </div>
      </div>
  </div>
</div>
</div>

<div class="mt-6 flex items-center justify-end gap-x-6">
<a href="{{route('courses.index')}}" class="link">{{__('Cancel')}}</a>
<button type="submit" class="btn btn-primary">@if($course->id !== 0) {{__('Edit')}} @else {{__('Create')}} @endif</button>
</div>
</form>
</div>

            </div>
        </div>
    </div>
</x-app-layout>
