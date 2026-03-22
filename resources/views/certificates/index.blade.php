<x-app-layout>
    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="text-sm breadcrumbs mb-4">
                <ul>
                    <li><a wire:navigate href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
                    <li>{{ __('My Certificates') }}</li>
                </ul>
            </div>

            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-semibold">{{ __('My Certificates') }}</h1>
                @php
                    $certifiedCount = App\Models\Post::where('user_id', Auth::id())->whereNotNull('certified_at')->count();
                @endphp
                @if($certifiedCount >= 25)
                <a href="{{ route('certificates.create') }}" class="btn btn-primary">
                    <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20">
                        <path d="M440-440H200q-17 0-28.5-11.5T160-480q0-17 11.5-28.5T200-520h240v-240q0-17 11.5-28.5T480-800q17 0 28.5 11.5T520-760v240h240q17 0 28.5 11.5T800-480q0 17-11.5 28.5T760-440H520v240q0 17-11.5 28.5T480-160q-17 0-28.5-11.5T440-200v-240Z"/>
                    </svg>
                    {{ __('Generate new certificate') }}
                </a>
                @endif
            </div>

            <x-info-message />

            @if($certificates->isEmpty())
                <div class="rounded-box border-base-300 text-base-content/30 flex h-64 flex-col items-center justify-center gap-4 border-2 border-dashed p-10 text-center">
                    <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="48" viewBox="0 -960 960 960" width="48">
                        <path d="m438-452-58-57q-11-11-27.5-11T324-508q-11 11-11 28t11 28l86 86q12 12 28 12t28-12l170-170q12-12 11.5-28T636-592q-12-12-28.5-12.5T579-593L438-452ZM326-90l-58-98-110-24q-15-3-24-15.5t-7-27.5l11-113-75-86q-10-11-10-26t10-26l75-86-11-113q-2-15 7-27.5t24-15.5l110-24 58-98q8-13 22-17.5t28 1.5l104 44 104-44q14-6 28-1.5t22 17.5l58 98 110 24q15 3 24 15.5t7 27.5l-11 113 75 86q10 11 10 26t-10 26l-75 86 11 113q2 15-7 27.5T802-212l-110 24-58 98q-8 13-22 17.5T584-74l-104-44-104 44q-14 6-28 1.5T326-90Z"/>
                    </svg>
                    <div>
                        <p class="font-medium">{{ __('No certificates yet') }}</p>
                        <p class="text-sm mt-1">{{ __('You need at least 25 certified posts to generate a certificate.') }}</p>
                    </div>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="table table-zebra w-full bg-base-100 shadow-xl rounded-lg">
                        <thead>
                            <tr>
                                <th>{{ __('Reference') }}</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Posts at issue') }}</th>
                                <th>{{ __('Issued on') }}</th>
                                <th class="text-right">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($certificates as $cert)
                            <tr>
                                <td>
                                    <code class="text-xs font-mono">{{ $cert->cert_id }}</code>
                                </td>
                                <td>{{ $cert->name }}</td>
                                <td>{{ $cert->total_posts }}</td>
                                <td>{{ $cert->issued_at->format('d/m/Y') }}</td>
                                <td class="flex justify-end gap-2">
                                    <a href="{{ route('certificates.verify', $cert->cert_id) }}" target="_blank"
                                        class="btn btn-xs btn-ghost" title="{{ __('Verify') }}">
                                        <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="16" viewBox="0 -960 960 960" width="16">
                                            <path d="M480-320q75 0 127.5-52.5T660-500q0-75-52.5-127.5T480-680q-75 0-127.5 52.5T300-500q0 75 52.5 127.5T480-320Z"/>
                                        </svg>
                                        {{ __('Verify') }}
                                    </a>
                                    @if(config('features.latex_enabled'))
                                    <a href="{{ route('certificates.download', $cert) }}"
                                        class="btn btn-xs btn-primary" title="{{ __('Download PDF') }}">
                                        <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="16" viewBox="0 -960 960 960" width="16">
                                            <path d="M480-320 280-520l56-58 104 104v-326h80v326l104-104 56 58-200 200ZM240-160q-33 0-56.5-23.5T160-240v-120h80v120h480v-120h80v120q0 33-23.5 56.5T720-160H240Z"/>
                                        </svg>
                                        {{ __('Download') }}
                                    </a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
