@extends('layouts.app')

@section('title', 'Submissions')

@section('content')
@include('admin.partials.nav')
<div class="admin-header">
    <h1>Submissions — {{ $task->title }}</h1>
    <a href="{{ route('admin.tasks.index') }}" class="btn btn-secondary">&larr; Back to Tasks</a>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="form-group" style="max-width:260px; margin-bottom:16px;">
    <label>Filter by Status</label>
    <select id="statusFilter" onchange="filterByStatus(this.value)">
        <option value="">All</option>
        <option value="pending">Pending</option>
        <option value="approved">Approved</option>
        <option value="needs_revision">Needs Revision</option>
    </select>
</div>

<table class="admin-table">
    <thead>
        <tr>
            <th>Applicant</th>
            <th>Email</th>
            <th>Proof</th>
            <th>Extra Details</th>
            <th>LinkedIn</th>
            <th>Submitted At</th>
            <th>Status / Feedback</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($submissions as $submission)
            <tr data-status="{{ $submission->status }}">
                <td>{{ $submission->applicant->full_name ?? '-' }}</td>
                <td>{{ $submission->applicant->email ?? '-' }}</td>
                <td>
                    @if ($submission->github_link)
                        <p><a href="{{ $submission->github_link }}" target="_blank" rel="noopener">GitHub</a></p>
                    @endif
                    @if ($submission->link)
                        <p><a href="{{ $submission->link }}" target="_blank" rel="noopener">Other Link</a></p>
                    @endif
                    @if ($submission->file_path)
                        <p><a href="{{ $submission->fileUrl() }}" target="_blank" rel="noopener">{{ $submission->file_original_name }}</a></p>
                    @endif
                    @if (! $submission->github_link && ! $submission->link && ! $submission->file_path)
                        -
                    @endif
                </td>
                <td>
                    @if ($submission->live_demo_url)
                        <p>Demo: <a href="{{ $submission->live_demo_url }}" target="_blank" rel="noopener">Open</a></p>
                    @endif
                    @if ($submission->tech_stack)
                        <p>Tech: {{ $submission->tech_stack }}</p>
                    @endif
                    @if ($submission->notes)
                        <p>Notes: {{ Str::limit($submission->notes, 100) }}</p>
                    @endif
                </td>
                <td>
                    @if ($submission->linkedin_post_link)
                        <p><a href="{{ $submission->linkedin_post_link }}" target="_blank" rel="noopener">Post Link</a></p>
                    @endif
                    @if ($submission->linkedin_screenshot_path)
                        <p><a href="{{ $submission->linkedinScreenshotUrl() }}" target="_blank" rel="noopener">
                            <img src="{{ $submission->linkedinScreenshotUrl() }}" alt="LinkedIn Screenshot" style="max-width:80px; max-height:80px; border-radius:4px;">
                        </a></p>
                    @endif
                </td>
                <td>{{ $submission->created_at->format('d M Y, h:i A') }}</td>
                <td>
                    <form method="POST" action="{{ route('admin.tasks.submissions.updateStatus', $submission) }}">
                        @csrf
                        @method('PATCH')
                        <select name="status" style="margin-bottom:6px; width:100%;">
                            @foreach (\App\Models\TaskSubmission::statuses() as $value => $label)
                                <option value="{{ $value }}" {{ $submission->status === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        <textarea name="admin_feedback" rows="2" placeholder="Feedback..." style="width:100%; margin-bottom:6px;">{{ $submission->admin_feedback }}</textarea>
                        <button type="submit" class="btn btn-primary" style="width:100%;">Save</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="muted">Abhi koi submission nahi aayi.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<script>
function filterByStatus(status) {
    document.querySelectorAll('.admin-table tbody tr[data-status]').forEach(function (row) {
        row.style.display = (!status || row.dataset.status === status) ? '' : 'none';
    });
}
</script>
@endsection