@extends('layouts.app')


@section('title', 'Submissions')

@section('content')
@include('admin.partials.nav')
<div class="admin-header">
    <h1>Submissions — {{ $task->title }}</h1>
    <a href="{{ route('admin.tasks.index') }}" class="btn btn-secondary">&larr; Back to Tasks</a>
</div>

<table class="admin-table">
    <thead>
        <tr>
            <th>Applicant</th>
            <th>Email</th>
            <th>Link</th>
            <th>File</th>
            <th>Notes</th>
            <th>Submitted At</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($submissions as $submission)
            <tr>
                <td>{{ $submission->application->full_name ?? '-' }}</td>
                <td>{{ $submission->application->email ?? '-' }}</td>
                <td>
                    @if ($submission->link)
                        <a href="{{ $submission->link }}" target="_blank" rel="noopener">Open</a>
                    @else
                        -
                    @endif
                </td>
                <td>
                    @if ($submission->file_path)
                        <a href="{{ $submission->fileUrl() }}" target="_blank" rel="noopener">{{ $submission->file_original_name }}</a>
                    @else
                        -
                    @endif
                </td>
                <td>{{ $submission->notes ?: '-' }}</td>
                <td>{{ $submission->created_at->format('d M Y, h:i A') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="muted">Abhi koi submission nahi aayi.</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection