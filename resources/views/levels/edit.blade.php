<x-app-layout>
    
    <div class="py-4">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if($level->id == 0)
      <div class=" text-sm breadcrumbs mb-2">
  <ul>
    <li><a wire:navigate href="{{route('dashboard')}}">
      {{__('Dashboard')}}
    </a></li>
    <li><a wire:navigate href="{{route('levels.index')}}">
      {{__('Levels')}}
    </a></li>
    <li>
      {{__('New level')}}
    </li>
  </ul>
</div>
@else 
<div class=" text-sm breadcrumbs mb-2">
  <ul>
    <li><a wire:navigate href="{{route('dashboard')}}">
      {{__('Dashboard')}}
    </a></li>
    <li><a wire:navigate href="{{route('levels.index')}}">
      {{__('Levels')}}
    </a></li>
    <li>
      {{__('Edit level : ')}}{{$level->name}}
    </li>
  </ul>
</div>
@endif
        <div class="">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">

<h1 class=" text-2xl font-medium text-gray-900 dark:text-white">
    @if($level->id !== 0) {{__('Edit a level')}} @else {{__('Add a new level')}} @endif
</h1>

</div>

<div class="bg-gray-200 dark:bg-gray-800 bg-opacity-25 gap-6 lg:gap-8 p-6 lg:p-8">
<x-info-message/>
<form method="POST" action="@if($level->id !== 0) {{route('levels.update', $level->id)}} @else {{route('levels.store')}} @endif">
@csrf
@if($level->id !== 0) @method('put') @endif
<div class="space-y-12">
<div class="border-b border-gray-900/10 pb-12">
  <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
    <div class="sm:col-span-3">
      <label for="name" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('Name')}}</label>
      <div class="mt-2">
        <input type="text" name="name" id="name" autocomplete="given-name" class="w-full input input-primary" value="{{ old('name', $level->name) }}">
      </div>
    </div>
    <div class="sm:col-span-3">
    <label for="courses[]" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('Courses')}}</label>
    <div class="mt-2">
    <select name="courses[]" multiple="true" class="select select-bordered w-full select-primary">
        @foreach($courses as $course)
            <option value="{{ $course->id }}" {{ in_array($course->id, old('courses', $level->courses->pluck('id')->all()) ?: []) ? 'selected' : '' }}>{{ $course->name }}</option>
        @endforeach
    </select>
    </div>
    </div>
  </div>
</div>
</div>

<div class="mt-6 flex items-center justify-end gap-x-6">
<a href="{{route('levels.index')}}" class="link ">{{__('Cancel')}}</a>
<button type="submit" class="btn btn-primary">@if($level->id !== 0) {{__('Edit')}} @else {{__('Create')}} @endif</button>
</div>
</form>
</div>
            </div>
        </div>
    </div>
</x-app-layout>
