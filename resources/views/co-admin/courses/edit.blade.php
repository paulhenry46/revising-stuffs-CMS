<x-app-layout>
    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="text-sm breadcrumbs mb-2">
                <ul>
                    <li><a wire:navigate href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
                    <li><a wire:navigate href="{{ route('co-admin.index') }}">{{ __('Co-Admin Panel') }}</a></li>
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
                                        <x-colors-selector :course=$course/>
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
                                                <div class="flex flex-col gap-2">
                                                    @foreach($levels as $level)
                                                        <label class="inline-flex items-center">
                                                            <input type="checkbox" name="levels[]" value="{{ $level->id }}"
                                                                class="checkbox checkbox-primary"
                                                                {{ in_array($level->id, old('levels', $course->exists ? $course->levels->pluck('id')->toArray() : [])) ? 'checked' : '' }}>
                                                            <span class="ml-2">
                                                                {{ $level->name }}
                                                                @if($level->curriculum)
                                                                    ({{ $level->curriculum->name }})
                                                                @endif
                                                            </span>
                                                        </label>
                                                    @endforeach
                                                </div>
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
