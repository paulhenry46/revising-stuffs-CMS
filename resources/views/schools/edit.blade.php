<x-app-layout>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if($school->id == 0)
      <div class=" text-sm breadcrumbs mb-2">
      <ul>
  <li><a wire:navigate href="{{route('dashboard')}}">
      {{__('Dashboard')}}
    </a></li>
    <li><a wire:navigate href="{{route('settings')}}">
      {{__('Schools')}}
    </a></li>
    <li>
      {{__('New')}}
    </li>
  </ul>
</div>
@else 
<div class=" text-sm breadcrumbs mb-2">
  <ul>
  <li><a wire:navigate href="{{route('dashboard')}}">
      {{__('Dashboard')}}
    </a></li>
    <li><a wire:navigate href="{{route('settings')}}">
      {{__('Schools')}}
    </a></li>
    <li>
      {{__('Edit School :')}}{{$school->name}}
    </li>
  </ul>
</div>
@endif
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-linear-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">

<h1 class=" text-2xl font-medium text-gray-900 dark:text-white">
    @if($school->id !== 0) {{__('Edit a school')}} @else {{__('Add a new school')}} @endif
</h1>

</div>

<div class="bg-gray-200/25 dark:bg-gray-800/25 gap-6 lg:gap-8 p-6 lg:p-8">
<x-info-message/>
<form method="POST" action="@if($school->id !== 0) {{route('schools.update', $school->id)}} @else {{route('schools.store')}} @endif">
@csrf
@if($school->id !== 0) @method('put') @endif
<div class="space-y-12">

<div class="border-b dark:border-white/10 border-gray-900/10 pb-12">
  <h2 class="text-base font-semibold leading-7 text-gray-900 dark:text-white">{{__('General Informations')}}</h2>
  <p class="mt-1 text-sm leading-6 text-gray-600 dark:text-white">{{__('This information is used to classify and recognise the school.')}}</p>
  <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
    <div class="sm:col-span-3">
      <label for="name" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('Name')}}</label>
      <div class="mt-2">
        <input value="{{ old('name', $school->name) }}" type="text" name="name" id="name" autocomplete="given-name" class="input input-primary w-full">
      </div>
    </div>
    <div class="sm:col-span-3">
      <label for="email" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('Slug')}}</label>
      <div class="mt-2">
        <input value="{{ old('slug', $school->email) }}" type="text" disabled name="slug" id="email" autocomplete="given-email" class="input input-primary w-full">
      </div>
    </div>
    <div class="col-span-full">
                      <label for="about" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('Description')}}</label>
                      <div class="mt-2">
                        <textarea id="about" name="description" rows="3" class="textarea textarea-bordered w-full">{{ old('description', $school->description) }}</textarea>
                      </div>
                    </div>
                    <div class="sm:col-span-3">
    <label for="curricula[]" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('Curricula')}}</label>
    <div class="mt-2">
    <select name="curricula[]" multiple="true" class="select select-bordered w-full select-primary">
        @foreach($curricula as $curriculum)
            <option value="{{ $curriculum->id }}" {{ in_array($curriculum->id, old('curricula', $school->curricula->pluck('id')->all()) ?: []) ? 'selected' : '' }}>{{ $curriculum->name }}</option>
        @endforeach
    </select>
    </div>
    </div>
  </div>
</div>
<div class="mt-6 flex items-center justify-end gap-x-6">
<a href="{{route('settings')}}" class="link">{{__('Cancel')}}</a>
<button type="submit" class="btn btn-primary">@if($school->id !== 0) {{__('Edit')}} @else {{__('Create')}} @endif</button>
</div>
</form>

</div>
            </div>
        </div>
    </div>
</x-app-layout>