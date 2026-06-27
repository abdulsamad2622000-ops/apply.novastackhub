@extends('layouts.app')

@section('title', $job->exists ? 'Edit job' : 'New job')

@push('head')
<style>
    .back { display:inline-flex; align-items:center; gap:6px; font-size:13.5px; color:var(--muted); text-decoration:none; margin-bottom:14px; }
    .back:hover { color:var(--text); }
    .grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
    @media (max-width:560px){ .grid-2{ grid-template-columns:1fr; } }
    .toggle { display:flex; align-items:flex-start; gap:11px; padding:13px; border:1px solid var(--line); border-radius:10px; background:#FBFBFE; cursor:pointer; margin-bottom:10px; }
    .toggle:has(input:checked) { border-color:var(--violet-600); background:#EFF4FE; }
    .toggle input { accent-color:var(--violet-600); width:18px; height:18px; margin-top:2px; flex:none; cursor:pointer; }
    .toggle .t-title { font-weight:600; font-size:14.5px; }
    .toggle .t-sub { font-size:12.5px; color:var(--muted); }
    .card { max-width:880px; margin-left:auto; margin-right:auto; }
</style>
@endpush

@section('content')
    @include('admin.partials.nav')

    <a class="back" href="{{ route('admin.jobs.index') }}">&larr; All jobs</a>

    <div class="card">
        <div class="card-accent"></div>
        <div class="card-body">
            <h1 class="lead-title" style="font-size:22px;">{{ $job->exists ? 'Edit job' : 'Post a new job' }}</h1>
            <p class="lead-sub">Fill in the role details. The job appears on the public careers page when set to Open.</p>

            <div class="divider"></div>

            <form method="POST" action="{{ $job->exists ? route('admin.jobs.update', $job) : route('admin.jobs.store') }}">
                @csrf
                @if ($job->exists) @method('PUT') @endif

                <div class="field @error('title') has-error @enderror">
                    <label class="q" for="title">Job title <span class="req">*</span></label>
                    <input type="text" id="title" name="title" value="{{ old('title', $job->title) }}" placeholder="e.g. Full Stack Web Developer" required>
                    @error('title')<span class="err">{{ $message }}</span>@enderror
                </div>

                <div class="grid-2">
                    <div class="field @error('department') has-error @enderror">
                        <label class="q" for="department">Department <span class="hint">Optional</span></label>
                        <input type="text" id="department" name="department" value="{{ old('department', $job->department) }}" placeholder="e.g. Engineering">
                        @error('department')<span class="err">{{ $message }}</span>@enderror
                    </div>
                    <div class="field @error('type') has-error @enderror">
                        <label class="q" for="type">Employment type <span class="req">*</span></label>
                        <input type="text" id="type" name="type" value="{{ old('type', $job->type ?: 'Full-time') }}" placeholder="Full-time / Commission-based / Contract" required>
                        @error('type')<span class="err">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="grid-2">
                    <div class="field @error('location') has-error @enderror">
                        <label class="q" for="location">Location <span class="req">*</span></label>
                        <input type="text" id="location" name="location" value="{{ old('location', $job->location ?: 'Remote') }}" placeholder="Remote / Karachi / Hybrid" required>
                        @error('location')<span class="err">{{ $message }}</span>@enderror
                    </div>
                    <div class="field @error('slug') has-error @enderror">
                        <label class="q" for="slug">URL slug <span class="hint">Optional — auto from title</span></label>
                        <input type="text" id="slug" name="slug" value="{{ old('slug', $job->slug) }}" placeholder="full-stack-web-developer">
                        @error('slug')<span class="err">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="field @error('summary') has-error @enderror">
                    <label class="q" for="summary">Short summary <span class="req">*</span>
                        <span class="hint">One or two lines shown on the careers list</span>
                    </label>
                    <textarea id="summary" name="summary" rows="2" maxlength="500" required>{{ old('summary', $job->summary) }}</textarea>
                    @error('summary')<span class="err">{{ $message }}</span>@enderror
                </div>

                <div class="field @error('form_type') has-error @enderror">
                    <label class="q" for="form_type">Application form type <span class="req">*</span>
                        <span class="hint">Professional = LinkedIn, experience, CV. Internship = City, Education, Skills (student-friendly, CV optional).</span>
                    </label>
                    @php $ft = old('form_type', $job->form_type ?: 'professional'); @endphp
                    <select class="input-select" id="form_type" name="form_type"
                            style="width:100%;font:inherit;font-size:15px;color:var(--text);background:#FBFBFE;border:1px solid var(--line);border-radius:10px;padding:11px 13px;">
                        <option value="professional" @selected($ft === 'professional')>Professional (default)</option>
                        <option value="internship" @selected($ft === 'internship')>Internship (student)</option>
                    </select>
                    @error('form_type')<span class="err">{{ $message }}</span>@enderror
                </div>

                <div class="field @error('description') has-error @enderror">
                    <label class="q" for="description">Full description <span class="req">*</span>
                        <span class="hint">Tip: short lines become headings; lines starting with "- " become bullets. Blank line = new section.</span>
                    </label>
                    <textarea id="description" name="description" rows="12" required>{{ old('description', $job->description) }}</textarea>
                    @error('description')<span class="err">{{ $message }}</span>@enderror
                </div>

                <div class="divider"></div>

                <label class="toggle">
                    <input type="checkbox" name="ask_commission_question" value="1" @checked(old('ask_commission_question', $job->ask_commission_question))>
                    <span>
                        <span class="t-title">Ask the "commission-only basis" question</span>
                        <span class="t-sub">Turn on for sales / commission roles.</span>
                    </span>
                </label>

                <label class="toggle">
                    <input type="checkbox" name="ask_outreach_question" value="1" @checked(old('ask_outreach_question', $job->ask_outreach_question))>
                    <span>
                        <span class="t-title">Ask the "outreach platforms" question</span>
                        <span class="t-sub">LinkedIn, Upwork, Fiverr, Cold Email, Other.</span>
                    </span>
                </label>

                <label class="toggle">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $job->exists ? $job->is_active : true))>
                    <span>
                        <span class="t-title">Open for applications</span>
                        <span class="t-sub">Uncheck to hide this job from the public careers page.</span>
                    </span>
                </label>

                <div class="actions">
                    <button type="submit" class="btn">{{ $job->exists ? 'Save changes' : 'Post job' }}</button>
                    <a href="{{ route('admin.jobs.index') }}" class="btn-ghost">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
