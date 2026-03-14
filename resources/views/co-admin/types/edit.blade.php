<x-app-layout>
    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="text-sm breadcrumbs mb-2">
                <ul>
                    <li><a wire:navigate href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
                    <li><a wire:navigate href="{{ route('co-admin.index') }}">{{ __('Co-Admin Panel') }}</a></li>
                    <li>
                        @if($type->id !== 0)
                            {{ __('Edit type: ') }}{{ $type->name }}
                        @else
                            {{ __('New type') }}
                        @endif
                    </li>
                </ul>
            </div>

            <div class="bg-base-200 dark:bg-base-200 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-base-200 dark:bg-base-200 border-b border-gray-200 dark:border-gray-700">
                    <h1 class="text-2xl font-medium text-gray-900 dark:text-white">
                        @if($type->id !== 0)
                            {{ __('Edit a type') }}
                        @else
                            {{ __('Add a new type') }}
                        @endif
                    </h1>
                </div>

                <div class="bg-base-200/25 dark:bg-base-200/25 gap-6 lg:gap-8 p-6 lg:p-8">
                    <x-info-message />

                    <form method="POST"
                          action="@if($type->id !== 0) {{ route('co-admin.types.update', $type->id) }} @else {{ route('co-admin.types.store') }} @endif">
                        @csrf
                        @if($type->id !== 0) @method('PUT') @endif

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
                                            <input type="text" name="name" id="name" autocomplete="off"
                                                   class="input input-primary w-full"
                                                   value="{{ old('name', $type->name) }}">
                                        </div>
                                    </div>

                                    {{-- Color --}}
                                    <x-colors-selector :course=$type/>

                                    {{-- Course (restricted to co-admin's accessible courses) --}}
                                    <div class="sm:col-span-3">
                                        <label for="course_id" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">
                                            {{ __('Course') }}
                                        </label>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">{{ __('Select the course this type belongs to.') }}</p>
                                        <div class="mt-2">
                                            @if($courses->isEmpty())
                                                <div class="alert alert-warning">
                                                    <span>{{ __('No courses are available in your curricula yet. Create a course first.') }}</span>
                                                </div>
                                            @else
                                            <select name="course_id" id="course_id" class="select select-bordered w-full select-primary" required>
                                                <option value="" disabled {{ old('course_id', $type->exists ? $type->course_id : '') === '' ? 'selected' : '' }}>
                                                    {{ __('Select a course') }}
                                                </option>
                                                @foreach($courses as $course)
                                                    <option value="{{ $course->id }}"
                                                        {{ (int) old('course_id', $type->exists ? $type->course_id : '') === $course->id ? 'selected' : '' }}>
                                                        {{ $course->name }}
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
                                @if($type->id !== 0) {{ __('Edit') }} @else {{ __('Create') }} @endif
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
