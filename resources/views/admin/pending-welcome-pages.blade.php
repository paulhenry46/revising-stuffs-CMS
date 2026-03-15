@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Pending Welcome Pages for Validation</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Curriculum</th>
                <th>Preview</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pendingPages as $curriculumId => $curriculum)
            <tr>
                <td>{{ $curriculum->name ?? $curriculumId }}</td>
                <td>
                    <a href="{{ route('admin.welcome-page.preview', $curriculumId) }}" target="_blank">Preview</a>
                </td>
                <td>
                    <form action="{{ route('admin.welcome-page.approve', $curriculumId) }}" method="POST" style="display:inline-block">
                        @csrf
                        <button class="btn btn-success" type="submit">Approve</button>
                    </form>
                    <form action="{{ route('admin.welcome-page.reject', $curriculumId) }}" method="POST" style="display:inline-block">
                        @csrf
                        <button class="btn btn-danger" type="submit">Reject</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @if(count($pendingPages) === 0)
        <p>No pending welcome pages.</p>
    @endif
</div>
@endsection
