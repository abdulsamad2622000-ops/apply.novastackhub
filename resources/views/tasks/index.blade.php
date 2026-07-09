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
                    @if ($submission->status === 'approved')
                        <span class="badge badge-success">Approved</span>
                    @elseif ($submission->status === 'needs_revision')
                        <span class="badge badge-error">Needs Revision</span>
                    @else
                        <span class="badge badge-pending">Pending Review</span>
                    @endif
                @else
                    <span class="badge badge-pending">Not Submitted</span>
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
                    @if ($submission->github_link)
                        <p>GitHub: <a href="{{ $submission->github_link }}" target="_blank" rel="noopener">{{ $submission->github_link }}</a></p>
                    @endif
                    @if ($submission->link)
                        <p>Other Link: <a href="{{ $submission->link }}" target="_blank" rel="noopener">{{ $submission->link }}</a></p>
                    @endif
                    @if ($submission->file_path)
                        <p>File: <a href="{{ $submission->fileUrl() }}" target="_blank" rel="noopener">{{ $submission->file_original_name }}</a></p>
                    @endif
                    @if ($submission->live_demo_url)
                        <p>Live Demo: <a href="{{ $submission->live_demo_url }}" target="_blank" rel="noopener">{{ $submission->live_demo_url }}</a></p>
                    @endif
                    @if ($submission->tech_stack)
                        <p>Technologies: {{ $submission->tech_stack }}</p>
                    @endif
                    @if ($submission->notes)
                        <p>Notes: {{ $submission->notes }}</p>
                    @endif
                    @if ($submission->linkedin_post_link)
                        <p>LinkedIn Post: <a href="{{ $submission->linkedin_post_link }}" target="_blank" rel="noopener">{{ $submission->linkedin_post_link }}</a></p>
                    @endif
                    @if ($submission->linkedin_screenshot_path)
                        <p>LinkedIn Screenshot: <a href="{{ $submission->linkedinScreenshotUrl() }}" target="_blank" rel="noopener">{{ $submission->linkedin_screenshot_original_name }}</a></p>
                    @endif
                    @if ($submission->admin_feedback)
                        <p><strong>Admin Feedback:</strong> {{ $submission->admin_feedback }}</p>
                    @endif
                </div>
            @endif

            <details class="submit-toggle">
                <summary>{{ $submission ? 'Re-submit' : 'Submit' }}</summary>

                <form method="POST" action="{{ route('tasks.submit', $task) }}" enctype="multipart/form-data" class="task-form">
                    @csrf

                    @if ($errors->any())
                        <div class="alert alert-error">
                            <ul style="margin:0; padding-left: 18px;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <h4>Kaam ka Proof (koi ek zaroori)</h4>

                    <div class="form-group">
                        <label>GitHub Repo Link</label>
                        <input type="url" name="github_link" value="{{ old('github_link', $submission->github_link ?? '') }}" placeholder="https://github.com/...">
                    </div>

                    <div class="form-group">
                        <label>Doosra Link (Drive, etc.)</label>
                        <input type="url" name="link" value="{{ old('link', $submission->link ?? '') }}" placeholder="https://...">
                    </div>

                    <div class="form-group">
                        <label>Ya Upload File</label>
                        <input type="file" name="file">
                        @if ($submission && $submission->file_original_name)
                            <p class="muted">Current: {{ $submission->file_original_name }}</p>
                        @endif
                    </div>

                    <hr>
                    <h4>Extra Details</h4>

                    <div class="form-group">
                        <label>Live Demo URL (Netlify/Vercel)</label>
                        <input type="url" name="live_demo_url" value="{{ old('live_demo_url', $submission->live_demo_url ?? '') }}" placeholder="https://...">
                    </div>

                    <div class="form-group">
                        <label>Technologies Used</label>
                        <input type="text" name="tech_stack" value="{{ old('tech_stack', $submission->tech_stack ?? '') }}" placeholder="HTML, CSS, JS...">
                    </div>

                    <div class="form-group">
                        <label>Challenges / What You Learned</label>
                        <textarea name="notes" rows="3">{{ old('notes', $submission->notes ?? '') }}</textarea>
                    </div>

                    <hr>
                    <h4>LinkedIn Proof (zaroori)</h4>

                    <div class="form-group">
                        <label>LinkedIn Post Link</label>
                        <input type="url" name="linkedin_post_link" value="{{ old('linkedin_post_link', $submission->linkedin_post_link ?? '') }}" placeholder="https://www.linkedin.com/..." required>
                    </div>

                    <div class="form-group">
                        <label>Screenshot Upload</label>
                        <input type="file" name="linkedin_screenshot" accept="image/*" {{ $submission && $submission->linkedin_screenshot_path ? '' : 'required' }}>
                        @if ($submission && $submission->linkedin_screenshot_original_name)
                            <p class="muted">Current: {{ $submission->linkedin_screenshot_original_name }}</p>
                        @endif
                    </div>

                    <hr>

                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="confirmed_own_work" value="1" {{ old('confirmed_own_work', $submission->confirmed_own_work ?? false) ? 'checked' : '' }} required>
                            I confirm this is my own work
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </details>
        </div>
    @endforeach
</div>
@endsection