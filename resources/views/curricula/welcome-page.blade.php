<x-app-layout>
    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="text-sm breadcrumbs mb-2">
                <ul>
                    <li><a wire:navigate href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
                    <li>{{__('Edit Welcome Page:')}} {{$curriculum->name}}</li>
                </ul>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-linear-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">
                    <h1 class="text-2xl font-medium text-gray-900 dark:text-white">
                        {{__('Edit Welcome Page for')}} {{$curriculum->name}}
                    </h1>
                    @if($curriculum->subdomain)
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{__('This page will be shown to visitors accessing')}} <strong>{{$curriculum->subdomain}}.{{parse_url(config('app.url'), PHP_URL_HOST)}}</strong>
                    </p>
                    @else
                    <p class="mt-1 text-sm text-yellow-600 dark:text-yellow-400">
                        {{__('No subdomain is configured for this curriculum. The welcome page will only be shown once a subdomain is set by the administrator.')}}
                    </p>
                    @endif
                </div>

                <div class="bg-gray-200/25 dark:bg-gray-800/25 gap-6 lg:gap-8 p-6 lg:p-8">
                    <x-info-message/>
                    <form method="POST" action="{{route('curricula.welcome-page.update', $curriculum->id)}}">
                        @csrf
                        @method('PUT')
                        <div class="space-y-6">
                            <div>
                                <label for="welcome_page" class="block text-sm font-medium leading-6 dark:text-white text-gray-900">{{__('Welcome Page Content (HTML)')}}</label>
                                <div class="mt-2">
                                    <textarea id="welcome_page" name="welcome_page" rows="20" class="textarea textarea-bordered textarea-primary w-full font-mono text-sm" placeholder="{{__('Enter HTML content for the custom welcome page. Leave empty to use the default welcome page.')}}">{{ old('welcome_page', $curriculum->welcome_page) }}</textarea>
                                </div>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{__('HTML is supported. Leave empty to use the default welcome page.')}}</p>
                            </div>
                            <div class="flex items-center justify-end gap-x-6">
                                <a href="{{route('dashboard')}}" class="link">{{__('Cancel')}}</a>
                                <button type="submit" class="btn btn-primary">{{__('Save Welcome Page')}}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
