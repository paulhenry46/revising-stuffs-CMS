<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Certificate Verification') }} — {{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-base-200 flex items-center justify-center p-4">
    <div class="card bg-base-100 shadow-2xl w-full max-w-lg">
        <div class="card-body items-center text-center gap-4">
            @if($certificate)
                <div class="text-success">
                    <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="64" viewBox="0 -960 960 960" width="64">
                        <path d="m438-452-58-57q-11-11-27.5-11T324-508q-11 11-11 28t11 28l86 86q12 12 28 12t28-12l170-170q12-12 11.5-28T636-592q-12-12-28.5-12.5T579-593L438-452ZM326-90l-58-98-110-24q-15-3-24-15.5t-7-27.5l11-113-75-86q-10-11-10-26t10-26l75-86-11-113q-2-15 7-27.5t24-15.5l110-24 58-98q8-13 22-17.5t28 1.5l104 44 104-44q14-6 28-1.5t22 17.5l58 98 110 24q15 3 24 15.5t7 27.5l-11 113 75 86q10 11 10 26t-10 26l-75 86 11 113q2 15-7 27.5T802-212l-110 24-58 98q-8 13-22 17.5T584-74l-104-44-104 44q-14 6-28 1.5T326-90Z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-success">{{ __('Certificate is valid') }}</h1>
                <div class="divider"></div>
                <div class="w-full text-left space-y-3">
                    <div class="flex justify-between">
                        <span class="font-medium opacity-60">{{ __('Reference') }}</span>
                        <code class="font-mono text-sm">{{ $certificate->cert_id }}</code>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium opacity-60">{{ __('Name') }}</span>
                        <span>{{ $certificate->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium opacity-60">{{ __('Issued on') }}</span>
                        <span>{{ $certificate->issued_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium opacity-60">{{ __('Certified posts') }}</span>
                        <span>{{ $certificate->total_posts }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium opacity-60">{{ __('Academic years') }}</span>
                        <span>{{ implode(', ', $certificate->years ?? []) }}</span>
                    </div>
                </div>
                <p class="text-xs opacity-40 mt-4">
                    {{ __('This certificate was issued by') }} {{ config('app.name') }}.
                    {{ __('It is an activity record, not an official diploma.') }}
                </p>
            @else
                <div class="text-error">
                    <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="64" viewBox="0 -960 960 960" width="64">
                        <path d="m336-280 144-144 144 144 56-56-144-144 144-144-56-56-144 144-144-144-56 56 144 144-144 144 56 56ZM480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-error">{{ __('Certificate not found') }}</h1>
                <p class="text-sm opacity-60">
                    {{ __('No certificate was found with reference') }} <code class="font-mono">{{ $certId }}</code>.
                    {{ __('This certificate may not exist or may have been revoked.') }}
                </p>
            @endif
            <a href="{{ url('/') }}" class="btn btn-ghost mt-4">{{ __('Back to homepage') }}</a>
        </div>
    </div>
</body>
</html>
