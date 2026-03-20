<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-base-200 dark:bg-base-200 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-base-200 dark:bg-base-200 border-b border-gray-200 dark:border-gray-700">
                    <h1 class="text-2xl font-medium text-gray-900 dark:text-white">
                        {{ __('Report an error') }} — {{ $post->title }}
                    </h1>
                </div>
                <div class="bg-base-200/25 dark:bg-base-200/25 gap-6 lg:gap-8 p-6 lg:p-8">
                    <p class="mb-4">
                        {{ __('You found an error in this document? Thank you for reporting it!') }}
                    </p>

                    <div class="mb-4">
                        <span class="font-semibold">{{ __('Post') }}:</span>
                        <a class="link link-primary link-hover ml-1" href="{{ route('post.public.view', [$post->slug, $post]) }}">
                            {{ $post->title }}
                        </a>
                    </div>

                    <div class="mb-4">
                        <span class="font-semibold">{{ __('Author') }}:</span>
                        <span class="ml-1">{{ $post->user->name ?? '' }}</span>
                        @if ($post->user->social_network_link)
                            — <a class="link link-primary link-hover" href="{{ $post->user->social_network_link }}" target="_blank" rel="noopener noreferrer">{{ $post->user->social_network_link }}</a>
                        @endif
                    </div>

                    <div class="mb-4">
                        <span class="font-semibold">{{ __('License') }}:</span>
                        <span class="ml-1">{{ $post->user->license ?? __('All rights reserved') }}</span>
                    </div>

                    <p class="mt-4 text-sm text-gray-500">
                        {{ __('To report an error, please use the comments section on the post page or contact the site administrators.') }}
                    </p>

                    <a class="btn btn-primary mt-6" href="{{ route('post.public.view', [$post->slug, $post]) }}">
                        {{ __('Go to the post') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
