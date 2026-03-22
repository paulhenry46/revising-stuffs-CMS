<x-app-layout>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="text-sm breadcrumbs mb-4">
                <ul>
                    <li><a wire:navigate href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
                    <li>{{ __('Generate Certificate') }}</li>
                </ul>
            </div>

            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title text-2xl">
                        <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="28" viewBox="0 -960 960 960" width="28">
                            <path d="m438-452-58-57q-11-11-27.5-11T324-508q-11 11-11 28t11 28l86 86q12 12 28 12t28-12l170-170q12-12 11.5-28T636-592q-12-12-28.5-12.5T579-593L438-452ZM326-90l-58-98-110-24q-15-3-24-15.5t-7-27.5l11-113-75-86q-10-11-10-26t10-26l75-86-11-113q-2-15 7-27.5t24-15.5l110-24 58-98q8-13 22-17.5t28 1.5l104 44 104-44q14-6 28-1.5t22 17.5l58 98 110 24q15 3 24 15.5t7 27.5l-11 113 75 86q10 11 10 26t-10 26l-75 86 11 113q2 15-7 27.5T802-212l-110 24-58 98q-8 13-22 17.5T584-74l-104-44-104 44q-14 6-28 1.5T326-90Z"/>
                        </svg>
                        {{ __('Community Commitment Certificate') }}
                    </h2>
                    <p class="text-sm opacity-70">
                        {{ __('Generate a PDF certificate attesting your contribution to the community. Choose the name to display and the academic years to include.') }}
                    </p>

                    @if($errors->has('general'))
                        <div class="alert alert-error mt-2">
                            <span>{{ $errors->first('general') }}</span>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('certificates.store') }}" class="mt-4 space-y-6">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium mb-1" for="name">
                                {{ __('Name to display on the certificate') }}
                            </label>
                            <input type="text" name="name" id="name"
                                class="input input-bordered w-full @error('name') input-error @enderror"
                                value="{{ old('name', Auth::user()->name) }}"
                                required maxlength="120">
                            @error('name')
                                <p class="text-error text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">
                                {{ __('Academic years of contribution') }}
                            </label>
                            <p class="text-xs opacity-60 mb-2">
                                {{ __('Enter each academic year in the format "2024 - 2025". Add as many years as you wish.') }}
                            </p>
                            <div id="years-container" class="space-y-2">
                                <div class="flex gap-2 items-center year-row">
                                    <input type="text" name="years[]"
                                        class="input input-bordered flex-1 @error('years') input-error @enderror"
                                        placeholder="{{ date('Y') - 1 }} - {{ date('Y') }}"
                                        value="{{ old('years.0') }}"
                                        maxlength="20">
                                    <button type="button" class="btn btn-ghost btn-sm remove-year hidden" title="{{ __('Remove') }}">
                                        <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20">
                                            <path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360Z"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            @error('years')
                                <p class="text-error text-sm mt-1">{{ $message }}</p>
                            @enderror
                            @error('years.*')
                                <p class="text-error text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <button type="button" id="add-year" class="btn btn-outline btn-sm mt-2">
                                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="18" viewBox="0 -960 960 960" width="18">
                                    <path d="M440-440H200q-17 0-28.5-11.5T160-480q0-17 11.5-28.5T200-520h240v-240q0-17 11.5-28.5T480-800q17 0 28.5 11.5T520-760v240h240q17 0 28.5 11.5T800-480q0 17-11.5 28.5T760-440H520v240q0 17-11.5 28.5T480-160q-17 0-28.5-11.5T440-200v-240Z"/>
                                </svg>
                                {{ __('Add year') }}
                            </button>
                        </div>

                        <div class="card-actions justify-end">
                            <a href="{{ route('certificates.index') }}" class="btn btn-ghost">{{ __('Cancel') }}</a>
                            <button type="submit" class="btn btn-primary">
                                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20">
                                    <path d="M480-320q75 0 127.5-52.5T660-500q0-75-52.5-127.5T480-680q-75 0-127.5 52.5T300-500q0 75 52.5 127.5T480-320Zm0-72q-45 0-76.5-31.5T372-500q0-45 31.5-76.5T480-608q45 0 76.5 31.5T588-500q0 45-31.5 76.5T480-392Zm0 192q-146 0-266-81.5T40-500q54-137 174-218.5T480-800q146 0 266 81.5T920-500q-54 137-174 218.5T480-200Z"/>
                                </svg>
                                {{ __('Generate certificate') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const container = document.getElementById('years-container');
        const addBtn = document.getElementById('add-year');

        function updateRemoveButtons() {
            const rows = container.querySelectorAll('.year-row');
            rows.forEach(row => {
                const btn = row.querySelector('.remove-year');
                btn.classList.toggle('hidden', rows.length <= 1);
            });
        }

        addBtn.addEventListener('click', () => {
            const firstInput = container.querySelector('.year-row input');
            const newRow = document.createElement('div');
            newRow.classList.add('flex', 'gap-2', 'items-center', 'year-row');
            newRow.innerHTML = `
                <input type="text" name="years[]"
                    class="input input-bordered flex-1"
                    placeholder="{{ date('Y') }} - {{ date('Y') + 1 }}"
                    maxlength="20">
                <button type="button" class="btn btn-ghost btn-sm remove-year" title="{{ __('Remove') }}">
                    <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20">
                        <path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360Z"/>
                    </svg>
                </button>`;
            container.appendChild(newRow);
            newRow.querySelector('.remove-year').addEventListener('click', removeYear);
            updateRemoveButtons();
        });

        function removeYear(e) {
            e.currentTarget.closest('.year-row').remove();
            updateRemoveButtons();
        }

        container.querySelectorAll('.remove-year').forEach(btn => {
            btn.addEventListener('click', removeYear);
        });
    </script>
</x-app-layout>
