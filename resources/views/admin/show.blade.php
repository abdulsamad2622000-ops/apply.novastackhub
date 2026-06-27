@extends('layouts.app')

@section('title', $application->full_name)

@push('head')
<style>
    .back { display:inline-flex;align-items:center;gap:6px;font-size:13.5px;color:var(--muted);text-decoration:none;margin-bottom:14px; }
    .back:hover { color:var(--text); }
    .detail-head { display:flex;align-items:flex-start;justify-content:space-between;gap:16px;flex-wrap:wrap; }
    .detail-head h1 { font-family:var(--display);font-size:24px;margin:0 0 4px;letter-spacing:-.3px; }
    .detail-sub { color:var(--muted);font-size:14px; }
    .cv-btn { display:inline-flex;align-items:center;gap:8px;font-weight:600;font-size:14px;color:#fff;text-decoration:none;
              background:linear-gradient(135deg,var(--violet),var(--indigo));padding:11px 18px;border-radius:10px;
              box-shadow:0 8px 20px -8px rgba(109,40,217,.6); }
    .dl { display:grid;grid-template-columns:200px 1fr;gap:0; }
    .dl > div { padding:14px 0;border-bottom:1px solid var(--line-2);display:contents; }
    .dl dt { padding:14px 16px 14px 0;font-size:13px;color:var(--muted);font-weight:600;border-bottom:1px solid var(--line-2); }
    .dl dd { padding:14px 0;margin:0;font-size:14.5px;border-bottom:1px solid var(--line-2);word-break:break-word; }
    .dl dd a { color:var(--violet-600);text-decoration:none; }
    .dl dd a:hover { text-decoration:underline; }
    .dl > dt:last-of-type, .dl > dd:last-of-type { border-bottom:none; }
    .platforms { display:flex;flex-wrap:wrap;gap:6px; }
    .platforms span { font-size:12px;background:#F1ECFD;border:1px solid #E4DAFB;color:#4B3A86;padding:3px 10px;border-radius:999px; }
    .longtext { white-space:pre-wrap; }
    .tag { font-size:11.5px; padding:3px 9px; border-radius:999px; font-weight:600; }
    .tag-yes { background:var(--success-bg); color:#0B7A52; }
    .tag-no  { background:#F3F4F6; color:#6B7280; }
    @media (max-width:560px){ .dl{grid-template-columns:1fr;} .dl dt{padding-bottom:2px;border-bottom:none;} .dl dd{padding-top:4px;} }
</style>
@endpush

@section('content')
    <a class="back" href="{{ route('admin.applications.index') }}">&larr; All applications</a>

    <div class="card">
        <div class="card-accent"></div>
        <div class="card-body">
            <div class="detail-head">
                <div>
                    <h1>{{ $application->full_name }}</h1>
                    <div class="detail-sub">Applied {{ $application->created_at->format('d M Y, g:i A') }}</div>
                </div>
                <a class="cv-btn" href="{{ route('admin.applications.cv', $application) }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                        <path d="M12 4v12m0 0l-5-5m5 5l5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M4 20h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    Download CV
                </a>
            </div>

            <div class="divider"></div>

            <dl class="dl">
                <dt>Email</dt>
                <dd><a href="mailto:{{ $application->email }}">{{ $application->email }}</a></dd>

                <dt>Phone / WhatsApp</dt>
                <dd><a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $application->phone) }}">{{ $application->phone }}</a></dd>

                <dt>LinkedIn</dt>
                <dd><a href="{{ $application->linkedin_url }}" target="_blank" rel="noopener">{{ $application->linkedin_url }}</a></dd>

                <dt>Upwork</dt>
                <dd>
                    @if ($application->upwork_url)
                        <a href="{{ $application->upwork_url }}" target="_blank" rel="noopener">{{ $application->upwork_url }}</a>
                    @else
                        <span style="color:var(--muted);">—</span>
                    @endif
                </dd>

                <dt>Experience</dt>
                <dd>{{ $application->experience_years }}</dd>

                <dt>Commission-only OK</dt>
                <dd><span class="tag {{ $application->commission_only ? 'tag-yes' : 'tag-no' }}">{{ $application->commission_only ? 'Yes' : 'No' }}</span></dd>

                <dt>Outreach platforms</dt>
                <dd>
                    @if (!empty($application->outreach_platforms))
                        <div class="platforms">
                            @foreach ($application->outreach_platforms as $p)
                                <span>{{ $p }}</span>
                            @endforeach
                        </div>
                    @else
                        <span style="color:var(--muted);">—</span>
                    @endif
                </dd>

                <dt>Experience details</dt>
                <dd class="longtext">{{ $application->experience_description }}</dd>

                <dt>Additional notes</dt>
                <dd class="longtext">{{ $application->additional_info ?: '—' }}</dd>

                <dt>CV file</dt>
                <dd>{{ $application->cv_original_name }}</dd>

                <dt>Submitted from IP</dt>
                <dd style="color:var(--muted);">{{ $application->ip_address ?: '—' }}</dd>
            </dl>
        </div>
    </div>
@endsection
