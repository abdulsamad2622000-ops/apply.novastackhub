@extends('layouts.app')

@section('title', 'My Tasks')

@section('content')
<div class="tasks-wrap">
    <div class="tasks-header">
        <div>
            <h1>Tasks</h1>
            <p class="muted">Hello, {{ $application->full_name ?? $application->email }}</p>
        </div>
        <a href="{{ route('tasks.logout') }}" class="btn btn-secondary">Logout</a>
    </div>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    @if ($tasks->isEmpty())
        <p class="muted">No tasks available yet.</p>
    @endif

    @foreach ($tasks as $task)
        @php $submission = $submissions->get($task->id); @endphp
        <div class="task-card">
            <div class="task-card-head">
                <h2>{{ $task->title }}</h2>
                @if ($submission)
                    <span class="badge badge-success">Submitted</span>
                @else
                    <span class="badge badge-pending">Pending</span>
                @endif
            </div>

            @if ($task->description)
                <p>{{ $task->description }}</p>
            @endif

            @if ($task->due_date)
                <p class="muted">Due: {{ $task->due_date->format('d M Y') }}</p>
            @endif

            @if ($submission)
                <div class="submission-info">
                    <p><strong>Your submission:</strong></p>
                    @if ($submission->link)
                        <p>Link: <a href="{{ $submission->link }}" target="_blank" rel="noopener">{{ $submission->link }}</a></p>
                    @endif
                    @if ($submission->file_path)
                        <p>File: <a href="{{ $submission->fileUrl() }}" target="_blank" rel="noopener">{{ $submission->file_original_name }}</a></p>
                    @endif
                    @if ($submission->notes)
                        <p>Notes: {{ $submission->notes }}</p>
                    @endif
                </div>
            @endif

            <details class="submit-toggle">
                <summary>{{ $submission ? 'Re-submit' : 'Submit' }}</summary>

                <form method="POST" action="{{ route('tasks.submit', $task) }}" enctype="multipart/form-data" class="task-form">
                    @csrf

                    @if ($errors->any())
                        <div class="alert alert-error">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <div class="form-group">
                        <label>Link (GitHub / Drive)</label>
                        <input type="url" name="link" value="{{ old('link', $submission->link ?? '') }}" placeholder="https://...">
                    </div>

                    <div class="form-group">
                        <label>Or Upload File</label>
                        <input type="file" name="file">
                    </div>

                    <div class="form-group">
                        <label>Notes (optional)</label>
                        <textarea name="notes" rows="3">{{ old('notes', $submission->notes ?? '') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </details>
        </div>
    @endforeach
</div>
@endsection