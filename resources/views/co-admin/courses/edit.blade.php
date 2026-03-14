<x-app-layout>
    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="text-sm breadcrumbs mb-2">
                <ul>
                    <li><a wire:navigate href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
                    <li><a wire:navigate href="{{ route('co-admin.index') }}">{{ __('My panel') }}</a></li>
                    <li>
                        @if($course->id !== 0)
                            {{ __('Edit course: ') }}{{ $course->name }}
                        @else
                            {{ __('New course') }}
                        @endif
                    </li>
                </ul>
            </div>

            <div class="bg-base-200 dark:bg-base-200 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-base-200 dark:bg-base-200 border-b border-gray-200 dark:border-gray-700">
                    <h1 class="text-2xl font-medium text-gray-900 dark:text-white">
                        @if($course->id !== 0)
                            {{ __('Edit a course') }}
                        @else
                            {{ __('Add a new course') }}
                        @endif
                    </h1>
                </div>

                <div class="bg-base-200/25 dark:bg-base-200/25 gap-6 lg:gap-8 p-6 lg:p-8">
                    <x-info-message />

                    <form method="POST"
                          action="@if($course->id !== 0) {{ route('co-admin.courses.update', $course->id) }} @else {{ route('co-admin.courses.store') }} @endif">
                        @csrf
                        @if($course->id !== 0) @method('PUT') @endif

                        {{-- Hidden color classes so Tailwind purge keeps them --}}
                        <div class="border-blue-500 border-green-500 border-purple-500 border-yellow-500 border-orange-500 border-gray-500 border-pink-500 border-red-500
                             checked:border-blue-500 checked:border-green-500 checked:border-purple-500 checked:border-yellow-500 checked:border-orange-500 checked:border-gray-500 checked:border-pink-500 checked:border-red-500
                             [--chkbg:var(--color-blue-500)] [--chkbg:var(--color-green-500)] [--chkbg:var(--color-purple-500)] [--chkbg:var(--color-yellow-500)] [--chkbg:var(--color-orange-500)] [--chkbg:var(--color-gray-500)] [--chkbg:var(--color-pink-500)] [--chkbg:var(--color-red-500)]
                             bg-blue-100 bg-green-100 bg-purple-100 bg-yellow-100 bg-orange-100 bg-gray-100 bg-pink-100 bg-red-100
                             bg-blue-700 bg-green-700 bg-purple-700 bg-yellow-700 bg-orange-700 bg-gray-700 bg-pink-700 bg-red-700" style="display:none;"></div>

                        <div class="space-y-12">
                            <div class="border-b border-gray-900/10 pb-12">
                                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">

                                    {{-- Name --}}
                                    <div class="sm:col-span-3">
                                        <label for="name" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{ __('Name') }}</label>
                                        <div class="mt-2">
                                            <input type="text" name="name" id="name" autocomplete="given-name"
                                                   autocomplete="off"
                                                   class="input input-primary w-full"
                                                   value="{{ old('name', $course->name ?? '') }}">
                                        </div>
                                    </div>

                                    {{-- Color --}}
                                    <div class="sm:col-span-3">
                                        <fieldset>
                                            <legend class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{ __('Choose a label color') }}</legend>
                                            <div class="mt-4 flex items-center space-x-3 fieldset">
                                                @foreach(['gray-500' => 'Gray', 'blue-500' => 'Blue', 'purple-500' => 'Purple', 'pink-500' => 'Pink', 'red-500' => 'Red', 'orange-500' => 'Orange', 'yellow-500' => 'Yellow', 'green-500' => 'Green'] as $colorValue => $colorLabel)
                                                <label class="@if(old('color', $course->color) == $colorValue) ring-2 @endif relative -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-0.5 focus:outline-hidden ring-{{ $colorValue }}">
                                                    <input type="radio" name="color" value="{{ $colorValue }}" class="sr-only"
                                                           @if(old('color', $course->color) == $colorValue) checked @endif>
                                                    <span class="sr-only">{{ $colorLabel }}</span>
                                                    <span aria-hidden="true" class="h-8 w-8 bg-{{ $colorValue }} rounded-full"></span>
                                                </label>
                                                @endforeach
                                            </div>
                                        </fieldset>
                                    </div>

                                    {{-- Language --}}
                                    <div class="sm:col-span-3">
                                        <label for="lang" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{ __('Language') }}</label>
                                        <div class="mt-2">
                                            <input type="text" name="lang" id="lang" maxlength="2"
                                                   class="input input-bordered w-full max-w-xs"
                                                   value="{{ old('lang', $course->lang) }}">
                                        </div>
                                    </div>

                                    {{-- Levels (restricted to co-admin's curricula) --}}
                                    <div class="sm:col-span-3">
                                        <label for="levels[]" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">
                                            {{ __('Levels') }}
                                        </label>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">{{ __('Select the levels (from your curricula) to link this course to.') }}</p>
                                        <div class="mt-2">
                                            @if($levels->isEmpty())
                                                <div class="alert alert-warning">
                                                    <span>{{ __('No levels are available in your curricula. Ask an administrator to create levels first.') }}</span>
                                                </div>
                                            @else
                                            <select name="levels[]" multiple class="select select-bordered w-full select-primary h-36">
                                                @foreach($levels as $level)
                                                    <option value="{{ $level->id }}"
                                                        {{ in_array($level->id, old('levels', $course->exists ? $course->levels->pluck('id')->toArray() : [])) ? 'selected' : '' }}>
                                                        {{ $level->name }}
                                                        @if($level->curriculum)
                                                            ({{ $level->curriculum->name }})
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                            @endif
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <a href="{{ route('co-admin.index') }}" class="link">{{ __('Cancel') }}</a>
                            <button type="submit" class="btn btn-primary">
                                @if($course->id !== 0) {{ __('Edit') }} @else {{ __('Create') }} @endif
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
