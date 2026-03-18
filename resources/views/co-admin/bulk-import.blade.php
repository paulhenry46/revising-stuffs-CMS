<x-app-layout>
    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="text-sm breadcrumbs mb-2">
                <ul>
                    <li><a wire:navigate href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
                    <li><a wire:navigate href="{{ route('co-admin.index') }}">{{ __('Co-Admin Panel') }}</a></li>
                    <li>{{ __('Bulk ZIP Import') }}</li>
                </ul>
            </div>

            <div class="bg-white dark:bg-base-100 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white dark:bg-base-100 border-b border-gray-200 dark:border-gray-700">
                    <h1 class="text-2xl font-medium text-gray-900 dark:text-white">
                        {{ __('Bulk ZIP Import') }}
                    </h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{ __('Import posts in bulk from a ZIP archive. Each top-level folder in the ZIP must match an existing course name. Files inside each folder become posts; the filename (without extension) is used as the post title.') }}
                    </p>
                </div>

                <div class="bg-white/25 dark:bg-base-100/25 p-6 lg:p-8">
                    <x-info-message />

                    <div class="mb-6 alert alert-info">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="font-semibold">{{ __('Expected ZIP structure') }}</p>
                            <pre class="mt-1 text-xs font-mono whitespace-pre">Course Name/
    Fiche C1.pdf
    Fiche C2.pdf
Course Name 2/
    Fiche 1.pdf</pre>
                            <p class="mt-1 text-xs">{{ __('Folders that do not match any existing course in your curricula will be skipped. All imported posts will be set to unpublished.') }}</p>
                        </div>
                    </div>

                    <form action="{{ route('co-admin.bulk-import.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">

                            {{-- Level --}}
                            <div class="form-control w-full">
                                <label class="label" for="level_id">
                                    <span class="label-text font-semibold">{{ __('Level') }}</span>
                                </label>
                                <select id="level_id" name="level_id" class="select select-bordered w-full" required>
                                    <option value="" disabled {{ old('level_id') ? '' : 'selected' }}>{{ __('Select a level') }}</option>
                                    @foreach($levels as $level)
                                        <option value="{{ $level->id }}" {{ old('level_id') == $level->id ? 'selected' : '' }}>
                                            {{ $level->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('level_id')
                                    <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                                @enderror
                            </div>

                            {{-- Type --}}
                            <div class="form-control w-full">
                                <label class="label" for="type_id">
                                    <span class="label-text font-semibold">{{ __('Type') }}</span>
                                </label>
                                <select id="type_id" name="type_id" class="select select-bordered w-full" required>
                                    <option value="" disabled {{ old('type_id') ? '' : 'selected' }}>{{ __('Select a type') }}</option>
                                    @foreach($types as $type)
                                        <option value="{{ $type->id }}" {{ old('type_id') == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                            @if($type->course)
                                                ({{ $type->course->name }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('type_id')
                                    <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                                @enderror
                            </div>

                        </div>

                        {{-- ZIP file --}}
                        <div class="form-control w-full mt-6">
                            <label class="label" for="zip_file">
                                <span class="label-text font-semibold">{{ __('ZIP Archive') }}</span>
                            </label>
                            <input type="file" id="zip_file" name="zip_file" accept=".zip,application/zip,application/x-zip-compressed"
                                   class="file-input file-input-bordered w-full max-w-lg" required />
                            @error('zip_file')
                                <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                            @enderror
                        </div>

                        <div class="mt-6 flex items-center gap-x-4">
                            <button type="submit" class="btn btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="currentColor">
                                    <path d="M440-320v-326L336-542l-56-58 200-200 200 200-56 58-104-104v326h-80ZM240-160q-33 0-56.5-23.5T160-240v-120h80v120h480v-120h80v120q0 33-23.5 56.5T720-160H240Z"/>
                                </svg>
                                {{ __('Import') }}
                            </button>
                            <a href="{{ route('co-admin.index') }}" class="link">{{ __('Cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
