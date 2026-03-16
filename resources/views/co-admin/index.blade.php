<x-app-layout>
    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="text-sm breadcrumbs mb-2">
                <ul>
                    <li><a wire:navigate href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
                    <li>{{ __('Co-Admin Panel') }}</li>
                </ul>
            </div>

            <div class="bg-white dark:bg-base-100 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white dark:bg-base-100 border-b border-gray-200 dark:border-gray-700">
                    <h1 class="text-2xl font-medium text-gray-900 dark:text-white">
                        {{ __('My management panel') }}
                    </h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{ __('Manage the courses and types available in your curricula.') }}
                    </p>
                </div>

                <div class="bg-white/25 dark:bg-base-100/25 gap-6 lg:gap-8 p-6 lg:p-8">
                    <x-info-message />

                    <div role="tablist" class="tabs tabs-bordered">
                        {{-- Courses Tab --}}
                        <input type="radio" name="co_admin_tabs" role="tab" class="tab" aria-label="{{ __('Courses') }}" checked />
                        <div role="tabpanel" class="tab-content p-10">
                            <div class="px-4 sm:px-6 lg:px-8">
                                <div class="sm:flex sm:items-center">
                                    <div class="sm:flex-auto">
                                        <h1 class="text-base font-semibold leading-6 text-gray-900 dark:text-white">
                                            {{ __('Courses') }}
                                        </h1>
                                        <p class="mt-2 text-sm text-gray-700 dark:text-white">
                                            {{ __('Courses linked to levels in your curricula.') }}
                                        </p>
                                    </div>
                                    <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                                        <a href="{{ route('co-admin.courses.create') }}" class="btn btn-primary">
                                            {{ __('New course') }}
                                        </a>
                                    </div>
                                </div>

                                <div class="mt-8 flow-root">
                                    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                                        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                                            @if($courses->isEmpty())
                                                <div class="alert alert-info">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    <span>{{ __('No courses are linked to your curricula yet. Create one to get started.') }}</span>
                                                </div>
                                            @else
                                            <table class="table table-zebra">
                                                <thead>
                                                    <tr>
                                                        <th>{{ __('Name') }}</th>
                                                        <th>{{ __('Color') }}</th>
                                                        <th>{{ __('Levels') }}</th>
                                                        <th>{{ __('Number of posts') }}</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($courses as $course)
                                                    <tr>
                                                        <td>{{ $course->name }}</td>
                                                        <td>
                                                            <span class="h-8 w-8 inline-block bg-{{ $course->color }} rounded-full border border-black border-opacity-10"></span>
                                                        </td>
                                                        <td>
                                                            @foreach($course->levels as $level)
                                                                <span class="ml-1 badge badge-outline">{{ $level->name }}</span>
                                                            @endforeach
                                                        </td>
                                                        <td>{{ $course->posts()->count() }}</td>
                                                        <td class="flex items-center justify-end gap-2 text-right">
                                                            <a href="{{ route('co-admin.courses.edit', $course->id) }}" class="link link-primary">
                                                                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M200-200h57l391-391-57-57-391 391v57Zm-80 80v-170l528-527q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L290-120H120Zm640-584-56-56 56 56Zm-141 85-28-29 57 57-29-28Z"/></svg>
                                                                <span class="sr-only">, {{ $course->name }}</span>
                                                            </a>
                                                            <form action="{{ route('co-admin.courses.destroy', $course->id) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="link link-error">
                                                                    <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"/></svg>
                                                                    <span class="sr-only">, {{ $course->name }}</span>
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Types Tab --}}
                        <input type="radio" name="co_admin_tabs" role="tab" class="tab" aria-label="{{ __('Types') }}" />
                        <div role="tabpanel" class="tab-content p-10">
                            <div class="px-4 sm:px-6 lg:px-8">
                                <div class="sm:flex sm:items-center">
                                    <div class="sm:flex-auto">
                                        <h1 class="text-base font-semibold leading-6 text-gray-900 dark:text-white">
                                            {{ __('Types') }}
                                        </h1>
                                        <p class="mt-2 text-sm text-gray-700 dark:text-white">
                                            {{ __('Post types linked to courses in your curricula.') }}
                                        </p>
                                    </div>
                                    <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                                        <a href="{{ route('co-admin.types.create') }}" class="btn btn-primary">
                                            {{ __('New type') }}
                                        </a>
                                    </div>
                                </div>

                                <div class="mt-8 flow-root">
                                    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                                        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                                            @if($types->isEmpty())
                                                <div class="alert alert-info">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    <span>{{ __('No types for your courses yet. Create one to get started.') }}</span>
                                                </div>
                                            @else
                                            <table class="table table-zebra">
                                                <thead>
                                                    <tr>
                                                        <th>{{ __('Name') }}</th>
                                                        <th>{{ __('Color') }}</th>
                                                        <th>{{ __('Course') }}</th>
                                                        <th>{{ __('Number of posts') }}</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($types as $type)
                                                    <tr>
                                                        <td>{{ $type->name }}</td>
                                                        <td>
                                                            <span class="h-8 w-8 inline-block bg-{{ $type->color }} rounded-full border border-black border-opacity-10"></span>
                                                        </td>
                                                        <td>
                                                            @if($type->course)
                                                                <span class="badge badge-outline">{{ $type->course->name }}</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ $type->posts()->count() }}</td>
                                                        <td class="flex items-center justify-end gap-2 text-right">
                                                            <a href="{{ route('co-admin.types.edit', $type->id) }}" class="link link-primary">
                                                                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M200-200h57l391-391-57-57-391 391v57Zm-80 80v-170l528-527q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L290-120H120Zm640-584-56-56 56 56Zm-141 85-28-29 57 57-29-28Z"/></svg>
                                                                <span class="sr-only">, {{ $type->name }}</span>
                                                            </a>
                                                            <form action="{{ route('co-admin.types.destroy', $type->id) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="link link-error">
                                                                    <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"/></svg>
                                                                    <span class="sr-only">, {{ $type->name }}</span>
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Settings Tab --}}
                        <input type="radio" name="co_admin_tabs" role="tab" class="tab" aria-label="{{ __('Settings') }}" @if(request('tab') === 'settings') checked @endif />
                        <div role="tabpanel" class="tab-content p-10">
                            <div class="px-4 sm:px-6 lg:px-8">
                                <div class="sm:flex-auto mb-6">
                                    <h1 class="text-base font-semibold leading-6 text-gray-900 dark:text-white">
                                        {{ __('Subdomain Settings') }}
                                    </h1>
                                    <p class="mt-2 text-sm text-gray-700 dark:text-white">
                                        {{ __('Customize the application name displayed on your curriculum subdomain.') }}
                                    </p>
                                </div>
                                <div class="grid grid-cols-2 space-x-2">
                                @forelse($managedCurricula as $curriculum)
                                    @if($curriculum->subdomain)
                                    <div class="card bg-base-100 border-primary border-2  mb-6">
                                        <div class="card-body">
                                            <h2 class="card-title">{{ $curriculum->name }}</h2>
                                            <p class="text-sm text-gray-500">
                                                {{ __('Subdomain:') }} <span class="font-mono">{{ $curriculum->subdomain }}.{{ parse_url(config('app.url'), PHP_URL_HOST) }}</span>
                                            </p>
                                            <form action="{{ route('co-admin.curricula.updateSettings', $curriculum->id) }}" method="POST" class="mt-4">
                                                @csrf
                                                @method('PUT')
                                                <div class="form-control w-full max-w-md">
                                                    <label class="label" for="app_name_{{ $curriculum->id }}">
                                                        <span class="label-text">{{ __('Application name for this subdomain') }}</span>
                                                    </label>
                                                    <input type="text" id="app_name_{{ $curriculum->id }}" name="app_name"
                                                        value="{{ old('app_name', $curriculum->app_name) }}"
                                                        placeholder="{{ env('APP_NAME') }}"
                                                        maxlength="100"
                                                        class="input input-bordered w-full max-w-md" />
                                                    <label class="label">
                                                        <span class="label-text-alt text-gray-500">{{ __('Leave empty to use the default application name.') }}</span>
                                                    </label>
                                                </div>
                                                <div class="mt-4">
                                                    <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                                                </div>
                                            </form>
                                            <div class="mt-4 flex items-center gap-x-4">
                                                <a href="{{ route('curricula.welcome-page.edit', ['curriculum' => $curriculum->id]) }}" class="btn btn-outline btn-secondary">
                                                     <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="currentColor"><path d="M240-120q-45 0-89-22t-71-58q26 0 53-20.5t27-59.5q0-50 35-85t85-35q50 0 85 35t35 85q0 66-47 113t-113 47Zm0-80q33 0 56.5-23.5T320-280q0-17-11.5-28.5T280-320q-17 0-28.5 11.5T240-280q0 23-5.5 42T220-202q5 2 10 2h10Zm230-160L360-470l358-358q11-11 27.5-11.5T774-828l54 54q12 12 12 28t-12 28L470-360Zm-190 80Z"/></svg>
                                                    {{ __('Edit Welcome Page') }}
                                                </a>
                                                <a href="{{ route('curricula.logo.edit', ['curriculum' => $curriculum->id]) }}" class="btn btn-outline btn-primary">
                                                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="currentColor"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Zm0 0v-560 560Zm80-80h400q12 0 18-11t-2-21L586-459q-6-8-16-8t-16 8L450-320l-74-99q-6-8-16-8t-16 8l-80 107q-8 10-2 21t18 11Z"/></svg>
                                               
                                                    {{ __('Edit Logo') }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                @empty
                                    <div class="alert alert-info">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <span>{{ __('No curricula with subdomains are assigned to you.') }}</span>
                                    </div>
                                @endforelse
                                </div>
                                @if($managedCurricula->where('subdomain', '!=', null)->isEmpty() && $managedCurricula->isNotEmpty())
                                    <div class="alert alert-info mt-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <span>{{ __('None of your curricula have a subdomain configured. Ask an administrator to set one up.') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Post Pack section (only shown when LaTeX is enabled) --}}
                    @if(config('features.latex_enabled'))
                    <div class="mt-8 p-6 border border-base-300 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ __('Post pack (PDF booklet)') }}
                                </h2>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('Generate a printable PDF booklet by selecting and ordering posts from your curricula.') }}
                                </p>
                            </div>
                            <a href="{{ route('co-admin.pack.create') }}" class="btn btn-primary">
                                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20"><path d="M240-80q-33 0-56.5-23.5T160-160v-640q0-33 23.5-56.5T240-880h320l240 240v480q0 33-23.5 56.5T720-80H240Zm280-520v-200H240v640h480v-440H520ZM240-800v200-200 640-640Z"/></svg>
                                {{ __('Generate a post pack') }}
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
