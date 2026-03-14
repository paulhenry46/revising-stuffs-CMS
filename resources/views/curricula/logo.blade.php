<x-app-layout>
    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="text-sm breadcrumbs mb-2">
                <ul>
                    <li><a wire:navigate href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
                    <li>{{__('Logo:')}} {{$curriculum->name}}</li>
                </ul>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-linear-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">
                    <h1 class="text-2xl font-medium text-gray-900 dark:text-white">
                        {{__('Logo for')}} {{$curriculum->name}}
                    </h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{__('This logo will be displayed in the navigation bar when visitors access this curriculum\'s subdomain.')}}
                    </p>
                </div>

                <div class="bg-gray-200/25 dark:bg-gray-800/25 gap-6 lg:gap-8 p-6 lg:p-8">
                    <x-info-message/>

                    {{-- Current status --}}
                    @if($logoUrl)
                    <div class="alert alert-success mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <div class="flex items-center gap-4">
                            <span>{{__('A custom logo is currently active.')}}</span>
                            <img src="{{ $logoUrl }}" alt="{{__('Current logo')}}" class="h-10 w-auto rounded">
                        </div>
                        <form method="POST" action="{{route('curricula.logo.delete', $curriculum->id)}}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-error">{{__('Remove')}}</button>
                        </form>
                    </div>
                    @else
                    <div class="alert alert-info mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>{{__('No custom logo uploaded. The default application mark will be shown.')}}</span>
                    </div>
                    @endif

                    {{-- Upload form --}}
                    <form method="POST" action="{{route('curricula.logo.update', $curriculum->id)}}" enctype="multipart/form-data">
                        @csrf
                        <div class="space-y-6">
                            <div>
                                <label for="logo_file" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">
                                    {{$logoUrl ? __('Replace Logo') : __('Upload Logo')}}
                                </label>
                                <div class="mt-2">
                                    <input type="file" id="logo_file" name="logo_file" accept=".jpg,.jpeg,.png,.gif,.webp" class="file-input file-input-bordered file-input-primary w-full">
                                </div>
                                @error('logo_file')
                                    <p class="mt-1 text-sm text-error">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    {{__('Accepted formats: JPG, PNG, GIF, WebP. Max size: 2 MB.')}}
                                </p>
                            </div>
                            <div class="flex items-center justify-end gap-x-6">
                                <a href="{{route('curricula.edit', $curriculum->id)}}" class="link">{{__('Cancel')}}</a>
                                <button type="submit" class="btn btn-primary">{{__('Upload')}}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
