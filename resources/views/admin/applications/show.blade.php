@extends('layouts.app')

@section('title', $application->full_name)

@push('head')
<style>
    .back { display:inline-flex;align-items:center;gap:6px;font-size:13.5px;color:var(--muted);text-decoration:none;margin-bottom:14px; }
    .back:hover { color:var(--text); }
    .detail-head { display:flex;align-items:flex-start;justify-content:space-between;gap:16px;flex-wrap:wrap; }
    .detail-head h1 { font-family:var(--display);font-size:24px;margin:0 0 4px;letter-spacing:-.3px; }
    .detail-sub { color:var(--muted);font-size:14px; }
    .detail-sub .jpill { font-size:11.5px;background:#E8F0FE;color:#1E40AF;border:1px solid #D5E2FC;padding:3px 9px;border-radius:999px;margin-left:4px; }
    .cv-btn { display:inline-flex;align-items:center;gap:8px;font-weight:600;font-size:14px;color:#fff;text-decoration:none;
              background:linear-gradient(135deg,var(--violet),var(--indigo));padding:11px 18px;border-radius:10px;
              box-shadow:0 8px 20px -8px rgba(29,78,216,.6); }
    .edit-btn { display:inline-flex;align-items:center;font-weight:600;font-size:14px;color:var(--text);text-decoration:none;
                background:#fff;border:1px solid var(--line);padding:11px 18px;border-radius:10px; }
    .edit-btn:hover { border-color:#CFD3E0; }
    .stt { font-size:12px; padding:4px 11px; border-radius:999px; font-weight:600; }
    .stt-Pending { background:#FEF6E7; color:#B45309; }
    .stt-Shortlisted { background:#E8F0FE; color:#1E40AF; }
    .stt-Rejected { background:#FEF2F4; color:#B91C1C; }
    .stt-Hired { background:#ECFAF3; color:#0B7A52; }
    .dl { display:grid;grid-template-columns:200px 1fr;gap:0; }
    .dl dt { padding:14px 16px 14px 0;font-size:13px;color:var(--muted);font-weight:600;border-bottom:1px solid var(--line-2); }
    .dl dd { padding:14px 0;margin:0;font-size:14.5px;border-bottom:1px solid var(--line-2);word-break:break-word; }
    .dl dd a { color:var(--violet-600);text-decoration:none; }
    .dl dd a:hover { text-decoration:underline; }
    .dl > dt:last-of-type, .dl > dd:last-of-type { border-bottom:none; }
    .platforms { display:flex;flex-wrap:wrap;gap:6px; }
    .platforms span { font-size:12px;background:#E8F0FE;border:1px solid #D5E2FC;color:#1E40AF;padding:3px 10px;border-radius:999px; }
    .longtext { white-space:pre-wrap; }
    .tag { font-size:11.5px; padding:3px 9px; border-radius:999px; font-weight:600; }
    .tag-yes { background:var(--success-bg); color:#0B7A52; }
    .tag-no  { background:#F3F4F6; color:#6B7280; }
    @media (max-width:560px){ .dl{grid-template-columns:1fr;} .dl dt{padding-bottom:2px;border-bottom:none;} .dl dd{padding-top:4px;} }
    .card { max-width:880px; margin-left:auto; margin-right:auto; }
</style>
@endpush

@section('content')
    @include('admin.partials.nav')

    <a class="back" href="{{ url()->previous() !== url()->current() ? url()->previous() : route('admin.applications.index') }}">&larr; Back to applications</a>

    <div class="card">
        <div class="card-accent"></div>
        <div class="card-body">
            <div class="detail-head">
                <div>
                    <h1>{{ $application->full_name }}</h1>
                    <div class="detail-sub">
                        Applied {{ $application->created_at->format('d M Y, g:i A') }}
                        for <span class="jpill">{{ $application->job?->title ?? '— (removed)' }}</span>
                    </div>
                    <div style="margin-top:8px;">
                        <span class="stt stt-{{ $application->status }}">{{ $application->status }}</span>
                    </div>
                </div>
                <div style="display:flex;gap:8px;flex-wrap:wrap;">
                    <a class="edit-btn" href="{{ route('admin.applications.edit', $application) }}">Edit</a>
                    @if ($application->cv_path)
                    <a class="cv-btn" href="{{ route('admin.applications.cv', $application) }}">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                            <path d="M12 4v12m0 0l-5-5m5 5l5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M4 20h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        Download CV
                    </a>
                    @endif
                </div>
            </div>

            <div class="divider"></div>

            <dl class="dl">
                <dt>Email</dt>
                <dd><a href="mailto:{{ $application->email }}">{{ $application->email }}</a></dd>

                <dt>Phone / WhatsApp</dt>
                <dd><a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $application->phone) }}" target="_blank" rel="noopener">{{ $application->phone }}</a></dd>

                @if ($application->city)
                    <dt>City</dt>
                    <dd>{{ $application->city }}</dd>
                @endif

                @if ($application->education)
                    <dt>Education / Semester</dt>
                    <dd>{{ $application->education }}</dd>
                @endif

                @if ($application->skills)
                    <dt>Skills</dt>
                    <dd class="longtext">{{ $application->skills }}</dd>
                @endif

                @if ($application->linkedin_url)
                    <dt>LinkedIn</dt>
                    <dd><a href="{{ $application->linkedin_url }}" target="_blank" rel="noopener">{{ $application->linkedin_url }}</a></dd>
                @endif

                @if ($application->portfolio_url)
                    <dt>Portfolio</dt>
                    <dd><a href="{{ $application->portfolio_url }}" target="_blank" rel="noopener">{{ $application->portfolio_url }}</a></dd>
                @endif

                @if ($application->experience_years)
                    <dt>Experience</dt>
                    <dd>{{ $application->experience_years }}</dd>
                @endif

                @if (! is_null($application->commission_only))
                    <dt>Commission-only OK</dt>
                    <dd><span class="tag {{ $application->commission_only ? 'tag-yes' : 'tag-no' }}">{{ $application->commission_only ? 'Yes' : 'No' }}</span></dd>
                @endif

                @if (! empty($application->outreach_platforms))
                    <dt>Outreach platforms</dt>
                    <dd>
                        <div class="platforms">
                            @foreach ($application->outreach_platforms as $p)
                                <span>{{ $p }}</span>
                            @endforeach
                        </div>
                    </dd>
                @endif

                @if ($application->experience_description)
                    <dt>Experience details</dt>
                    <dd class="longtext">{{ $application->experience_description }}</dd>
                @endif

                <dt>Additional notes</dt>
                <dd class="longtext">{{ $application->additional_info ?: '—' }}</dd>

                <dt>CV file</dt>
                <dd>{{ $application->cv_original_name ?: '—' }}</dd>

                <dt>Internal notes</dt>
                <dd class="longtext">{{ $application->notes ?: '—' }}</dd>

                <dt>Submitted from IP</dt>
                <dd style="color:var(--muted);">{{ $application->ip_address ?: '—' }}</dd>
            </dl>
        </div>
    </div>
@endsection
