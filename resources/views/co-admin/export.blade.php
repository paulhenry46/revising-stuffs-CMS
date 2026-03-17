<x-app-layout>
    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="text-sm breadcrumbs mb-2">
                <ul>
                    <li><a wire:navigate href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
                    <li><a wire:navigate href="{{ route('co-admin.index', ['tab' => 'settings']) }}">{{ __('Co-Admin Panel') }}</a></li>
                    <li>{{ __('Export Curriculum') }}</li>
                </ul>
            </div>

            <div class="bg-white dark:bg-base-100 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white dark:bg-base-100 border-b border-gray-200 dark:border-gray-700">
                    <h1 class="text-2xl font-medium text-gray-900 dark:text-white">
                        {{ __('Export a Curriculum') }}
                    </h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{ __('Choose a curriculum to export as a ZIP archive (JSON data + uploaded files).') }}
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

                    @if($managedCurricula->isEmpty())
                        <div class="alert alert-info">
                            <span>{{ __('No curricula are assigned to your account.') }}</span>
                        </div>
                        <a href="{{ route('co-admin.index', ['tab' => 'settings']) }}" class="btn btn-ghost mt-4">
                            {{ __('Back') }}
                        </a>
                    @else
                        <form action="{{ route('co-admin.export.download') }}" method="POST" class="space-y-6 max-w-lg">
                            @csrf

                            @if($managedCurricula->count() > 1)
                                <div class="form-control w-full">
                                    <label class="label" for="curriculum_id">
                                        <span class="label-text font-semibold">{{ __('Curriculum') }}</span>
                                    </label>
                                    <select id="curriculum_id" name="curriculum_id" class="select select-bordered w-full" required>
                                        @foreach($managedCurricula as $curriculum)
                                            <option value="{{ $curriculum->id }}">{{ $curriculum->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @else
                                <input type="hidden" name="curriculum_id" value="{{ $managedCurricula->first()?->id }}">
                                <div class="alert alert-info">
                                    <span>{{ __('Curriculum selected:') }} <strong>{{ $managedCurricula->first()?->name }}</strong></span>
                                </div>
                            @endif

                            <div class="alert alert-warning">
                                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                <span>{{ __('Thumbnails are not included in the ZIP. They will be regenerated automatically after import.') }}</span>
                            </div>

                            <div class="flex gap-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Export ZIP') }}
                                </button>
                                <a href="{{ route('co-admin.index', ['tab' => 'settings']) }}" class="btn btn-ghost">
                                    {{ __('Cancel') }}
                                </a>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
