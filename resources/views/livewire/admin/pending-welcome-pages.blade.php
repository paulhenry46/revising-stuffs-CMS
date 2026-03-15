<div>
    @if(session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif
    <table class="table mt-2">
        <thead>
            <tr>
                <th>{{ __('Curriculum') }}</th>
                <th>{{ __('Preview') }}</th>
                <th>{{ __('Actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pendingPages as $curriculumId => $curriculum)
            <tr>
                <td>{{ $curriculum->name ?? $curriculumId }}</td>
                <td>
                    <button class="link link-primary" wire:click="download('{{ $curriculumId }}')">
            <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                    <path d="M480-320q75 0 127.5-52.5T660-500q0-75-52.5-127.5T480-680q-75 0-127.5 52.5T300-500q0 75 52.5 127.5T480-320Zm0-72q-45 0-76.5-31.5T372-500q0-45 31.5-76.5T480-608q45 0 76.5 31.5T588-500q0 45-31.5 76.5T480-392Zm0 192q-146 0-266-81.5T40-500q54-137 174-218.5T480-800q146 0 266 81.5T920-500q-54 137-174 218.5T480-200Zm0-300Zm0 220q113 0 207.5-59.5T832-500q-50-101-144.5-160.5T480-720q-113 0-207.5 59.5T128-500q50 101 144.5 160.5T480-280Z"/>
                </svg>
            </button>
                </td>
                <td>
                    <button wire:click="approve('{{ $curriculumId }}')" class="btn btn-success">{{ __('Approve') }}</button>
                    <button wire:click="reject('{{ $curriculumId }}')" class="btn btn-danger">{{ __('Reject') }}</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @if(count($pendingPages) === 0)
        <p>{{ __('No pending welcome pages.') }}</p>
    @endif
</div>
