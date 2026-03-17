<x-app-layout>
    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="text-sm breadcrumbs mb-2">
                <ul>
                    <li><a wire:navigate href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
                    <li><a wire:navigate href="{{ route('co-admin.index', ['tab' => 'settings']) }}">{{ __('Co-Admin Panel') }}</a></li>
                    <li>{{ __('Import Curriculum') }}</li>
                </ul>
            </div>

            <div class="bg-white dark:bg-base-100 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white dark:bg-base-100 border-b border-gray-200 dark:border-gray-700">
                    <h1 class="text-2xl font-medium text-gray-900 dark:text-white">
                        {{ __('Import a Curriculum') }}
                    </h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{ __('Upload a ZIP archive previously exported from another RSCMS installation.') }}
                    </p>
                </div>

                <div class="p-6 lg:p-8">
                    <x-info-message />

                    @if ($errors->any())
                        <div class="alert alert-error mb-4">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('co-admin.import.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 max-w-lg">
                        @csrf

                        <div class="form-control w-full">
                            <label class="label" for="zip_file">
                                <span class="label-text font-semibold">{{ __('ZIP Archive') }}</span>
                            </label>
                            <input
                                type="file"
                                id="zip_file"
                                name="zip_file"
                                accept=".zip"
                                class="file-input file-input-bordered w-full @error('zip_file') file-input-error @enderror"
                                required
                            />
                            <label class="label">
                                <span class="label-text-alt text-gray-500">
                                    {{ __('Select a .zip file exported from RSCMS (max 100 MB).') }}
                                </span>
                            </label>
                            @error('zip_file')
                                <span class="text-error text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="alert alert-warning">
                            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                            <span>{{ __('Importing will create a new curriculum with all associated levels, courses, types, and posts. Existing data will not be overwritten.') }}</span>
                        </div>

                        <div class="flex gap-4">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Import') }}
                            </button>
                            <a href="{{ route('co-admin.index', ['tab' => 'settings']) }}" class="btn btn-ghost">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
