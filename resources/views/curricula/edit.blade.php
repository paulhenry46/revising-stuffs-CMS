<x-app-layout>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if($curriculum->id == 0)
      <div class=" text-sm breadcrumbs mb-2">
      <ul>
  <li><a wire:navigate href="{{route('dashboard')}}">
      {{__('Dashboard')}}
    </a></li>
    <li><a wire:navigate href="{{route('settings')}}">
      {{__('Curricula')}}
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
      {{__('Curricula')}}
    </a></li>
    <li>
      {{__('Edit Curriculum :')}}{{$curriculum->name}}
    </li>
  </ul>
</div>
@endif
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-linear-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">

<h1 class=" text-2xl font-medium text-gray-900 dark:text-white">
    @if($curriculum->id !== 0) {{__('Edit a curriculum')}} @else {{__('Add a new curriculum')}} @endif
</h1>

</div>

<div class="bg-gray-200/25 dark:bg-gray-800/25 gap-6 lg:gap-8 p-6 lg:p-8">
<x-info-message/>
<form method="POST" action="@if($curriculum->id !== 0) {{route('curricula.update', $curriculum->id)}} @else {{route('curricula.store')}} @endif">
@csrf
@if($curriculum->id !== 0) @method('put') @endif
<div class="space-y-12">

<div class="border-b dark:border-white/10 border-gray-900/10 pb-12">
  <h2 class="text-base font-semibold leading-7 text-gray-900 dark:text-white">{{__('General Informations')}}</h2>
  <p class="mt-1 text-sm leading-6 text-gray-600 dark:text-white">{{__('This information is used to classify and recognise the curriculum.')}}</p>
  <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
    <div class="sm:col-span-3">
      <label for="name" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('Name')}}</label>
      <div class="mt-2">
        <input value="{{ old('name', $curriculum->name) }}" type="text" name="name" id="name" autocomplete="given-name" class="input input-primary w-full">
      </div>
    </div>
    <div class="sm:col-span-3">
      <label for="email" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('Slug')}}</label>
      <div class="mt-2">
        <input value="{{ old('slug', $curriculum->email) }}" type="text" disabled name="slug" id="email" autocomplete="given-email" class="input input-primary w-full">
      </div>
    </div>
    <div class="col-span-full">
                      <label for="about" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('Description')}}</label>
                      <div class="mt-2">
                        <textarea id="about" name="description" rows="3" class="textarea textarea-bordered textarea-primary w-full">{{ old('description', $curriculum->description) }}</textarea>
                      </div>
                    </div>
                    <div class="sm:col-span-3">
    <label for="levels[]" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('Courses')}}</label>
    <div class="mt-2">
    <select name="levels[]" multiple="true" class="select select-bordered w-full select-primary">
        @foreach($levels as $level)
            <option value="{{ $level->id }}" @if($curriculum->id !== 0 ) {{ in_array($level->id, old('levels', $curriculum->levels->pluck('id')->all()) ?: []) ? 'selected' : '' }} @endif>{{ $level->name }}</option>
        @endforeach
    </select>
    </div>
    </div>
  </div>
</div>

<div class="border-b dark:border-white/10 border-gray-900/10 pb-12">
  <h2 class="text-base font-semibold leading-7 text-gray-900 dark:text-white">{{__('Subdomain Settings')}}</h2>
  <p class="mt-1 text-sm leading-6 text-gray-600 dark:text-white">{{__('Configure a dedicated subdomain for this curriculum. When enabled, visitors accessing this subdomain will only see content related to this curriculum.')}}</p>
  <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
    <div class="sm:col-span-3">
      <label for="subdomain" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('Subdomain')}}</label>
      <div class="mt-2">
        <input value="{{ old('subdomain', $curriculum->subdomain) }}" type="text" name="subdomain" id="subdomain" placeholder="e.g. bac" class="input input-primary w-full">
      </div>
      <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{__('Enter only the subdomain part (e.g. "bac" for bac.example.com). Leave empty to disable subdomain.')}}</p>
    </div>
    <div class="sm:col-span-3 flex items-center gap-x-4 mt-6">
      <label for="subdomain_enabled" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('Enable Subdomain')}}</label>
      <input type="checkbox" name="subdomain_enabled" id="subdomain_enabled" class="toggle toggle-primary" value="1" {{ old('subdomain_enabled', $curriculum->subdomain_enabled ?? false) ? 'checked' : '' }}>
      <span class="text-sm text-gray-500 dark:text-gray-400">{{__('When disabled, visiting the subdomain will redirect to the main domain.')}}</span>
    </div>
    <div class="col-span-full">
      <label for="welcome_page" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('Custom Welcome Page (HTML)')}}</label>
      <div class="mt-2">
        <textarea id="welcome_page" name="welcome_page" rows="10" class="textarea textarea-bordered textarea-primary w-full font-mono text-sm" placeholder="{{__('Enter HTML content for the custom welcome page. Leave empty to use the default welcome page.')}}">{{ old('welcome_page', $curriculum->welcome_page) }}</textarea>
      </div>
      <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{__('This page will be shown to visitors arriving from the subdomain. HTML is supported. Leave empty to use the default welcome page.')}}</p>
    </div>
  </div>
</div>
<div class="mt-6 flex items-center justify-end gap-x-6">
<a href="{{route('settings')}}" class="link">{{__('Cancel')}}</a>
<button type="submit" class="btn btn-primary">@if($curriculum->id !== 0) {{__('Edit')}} @else {{__('Create')}} @endif</button>
</div>
</form>

</div>
            </div>
        </div>
    </div>
</x-app-layout>