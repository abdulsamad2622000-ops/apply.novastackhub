@extends('layouts.app')

@section('title', 'Edit application')

@push('head')
<style>
    .back { display:inline-flex; align-items:center; gap:6px; font-size:13.5px; color:var(--muted); text-decoration:none; margin-bottom:14px; }
    .back:hover { color:var(--text); }
    .grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
    @media (max-width:560px){ .grid-2{ grid-template-columns:1fr; } }
    select.input { width:100%; font:inherit; font-size:15px; color:var(--text); background:#FBFBFE; border:1px solid var(--line); border-radius:10px; padding:11px 13px; }
    select.input:focus { outline:none; border-color:var(--violet-600); box-shadow:0 0 0 3px rgba(37,99,235,.15); background:#fff; }
    .meta-line { font-size:13px; color:var(--muted); margin-top:2px; }
    .card { max-width:880px; margin-left:auto; margin-right:auto; }
</style>
@endpush

@section('content')
    @include('admin.partials.nav')

    <a class="back" href="{{ route('admin.applications.show', $application) }}">&larr; Back to application</a>

    <div class="card">
        <div class="card-accent"></div>
        <div class="card-body">
            <h1 class="lead-title" style="font-size:22px;">Edit application</h1>
            <p class="meta-line">{{ $application->full_name }} · applied for {{ $application->job?->title ?? '—' }} · {{ $application->created_at->format('d M Y') }}</p>

            <div class="divider"></div>

            @if ($errors->any())
                <div class="note-warn" role="alert">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="9" stroke="#9F1239" stroke-width="2"/><path d="M12 7v6M12 16.5v.5" stroke="#9F1239" stroke-width="2" stroke-linecap="round"/></svg>
                    <span>Please fix the highlighted fields below.</span>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.applications.update', $application) }}">
                @csrf
                @method('PUT')

                <div class="field @error('status') has-error @enderror">
                    <label class="q" for="status">Status</label>
                    <select class="input" id="status" name="status">
                        @foreach ($statuses as $st)
                            <option value="{{ $st }}" @selected(old('status', $application->status) === $st)>{{ $st }}</option>
                        @endforeach
                    </select>
                    @error('status')<span class="err">{{ $message }}</span>@enderror
                </div>

                <div class="grid-2">
                    <div class="field @error('full_name') has-error @enderror">
                        <label class="q" for="full_name">Full name</label>
                        <input type="text" id="full_name" name="full_name" value="{{ old('full_name', $application->full_name) }}" required>
                        @error('full_name')<span class="err">{{ $message }}</span>@enderror
                    </div>
                    <div class="field @error('email') has-error @enderror">
                        <label class="q" for="email">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $application->email) }}" required>
                        @error('email')<span class="err">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="grid-2">
                    <div class="field @error('phone') has-error @enderror">
                        <label class="q" for="phone">Phone / WhatsApp</label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone', $application->phone) }}" required>
                        @error('phone')<span class="err">{{ $message }}</span>@enderror
                    </div>
                    <div class="field @error('experience_years') has-error @enderror">
                        <label class="q" for="experience_years">Experience</label>
                        <input type="text" id="experience_years" name="experience_years" value="{{ old('experience_years', $application->experience_years) }}" required>
                        @error('experience_years')<span class="err">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="grid-2">
                    <div class="field @error('city') has-error @enderror">
                        <label class="q" for="city">City</label>
                        <input type="text" id="city" name="city" value="{{ old('city', $application->city) }}">
                        @error('city')<span class="err">{{ $message }}</span>@enderror
                    </div>
                    <div class="field @error('education') has-error @enderror">
                        <label class="q" for="education">Education / Semester</label>
                        <input type="text" id="education" name="education" value="{{ old('education', $application->education) }}">
                        @error('education')<span class="err">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="field @error('skills') has-error @enderror">
                    <label class="q" for="skills">Skills</label>
                    <textarea id="skills" name="skills" rows="2">{{ old('skills', $application->skills) }}</textarea>
                    @error('skills')<span class="err">{{ $message }}</span>@enderror
                </div>

                <div class="grid-2">
                    <div class="field @error('linkedin_url') has-error @enderror">
                        <label class="q" for="linkedin_url">LinkedIn</label>
                        <input type="url" id="linkedin_url" name="linkedin_url" value="{{ old('linkedin_url', $application->linkedin_url) }}">
                        @error('linkedin_url')<span class="err">{{ $message }}</span>@enderror
                    </div>
                    <div class="field @error('portfolio_url') has-error @enderror">
                        <label class="q" for="portfolio_url">Portfolio</label>
                        <input type="url" id="portfolio_url" name="portfolio_url" value="{{ old('portfolio_url', $application->portfolio_url) }}">
                        @error('portfolio_url')<span class="err">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="field @error('experience_description') has-error @enderror">
                    <label class="q" for="experience_description">Experience details</label>
                    <textarea id="experience_description" name="experience_description" rows="4" required>{{ old('experience_description', $application->experience_description) }}</textarea>
                    @error('experience_description')<span class="err">{{ $message }}</span>@enderror
                </div>

                <div class="field @error('additional_info') has-error @enderror">
                    <label class="q" for="additional_info">Additional info <span class="hint">From applicant</span></label>
                    <textarea id="additional_info" name="additional_info" rows="3">{{ old('additional_info', $application->additional_info) }}</textarea>
                    @error('additional_info')<span class="err">{{ $message }}</span>@enderror
                </div>

                <div class="field @error('notes') has-error @enderror">
                    <label class="q" for="notes">Internal notes <span class="hint">Only visible to admins</span></label>
                    <textarea id="notes" name="notes" rows="3" placeholder="Interview feedback, next steps…">{{ old('notes', $application->notes) }}</textarea>
                    @error('notes')<span class="err">{{ $message }}</span>@enderror
                </div>

                <div class="actions">
                    <button type="submit" class="btn">Save changes</button>
                    <a href="{{ route('admin.applications.show', $application) }}" class="btn-ghost">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
