@extends('layouts.app')

@section('title', 'Apply')

@push('head')
<style>
    .careers-head { margin-bottom: 24px; }
    .careers-head h1 { font-family:var(--display); font-size:clamp(26px,5vw,34px); font-weight:700; letter-spacing:-.5px; margin:10px 0 8px; }
    .careers-head p { color:var(--muted); font-size:15.5px; max-width:560px; margin:0; }

    .job-list { display:flex; flex-direction:column; gap:14px; }
    .job-card {
        display:block; text-decoration:none; color:inherit;
        background:var(--card); border:1px solid var(--line); border-radius:var(--radius);
        box-shadow:var(--shadow); padding:22px 24px;
        transition:border-color .15s ease, transform .12s ease, box-shadow .15s ease;
    }
    .job-card:hover { border-color:#AFC6F5; transform:translateY(-2px); box-shadow:0 14px 34px -14px rgba(16,24,40,.24); }
    .job-card-top { display:flex; justify-content:space-between; align-items:flex-start; gap:16px; }
    .job-title { font-family:var(--display); font-size:19px; font-weight:600; letter-spacing:-.3px; margin:0 0 6px; }
    .job-summary { color:var(--muted); font-size:14.5px; margin:0; }
    .job-tags { display:flex; flex-wrap:wrap; gap:7px; margin-top:14px; }
    .jtag { font-size:12px; font-weight:500; padding:4px 11px; border-radius:999px; }
    .jtag-dept { background:#E8F0FE; color:#1E40AF; border:1px solid #D5E2FC; }
    .jtag-loc  { background:#EEF1F6; color:#475569; border:1px solid #E2E6EE; }
    .jtag-type { background:#E6F7FB; color:#0E7C97; border:1px solid #CDEEF5; }
    .apply-arrow { flex:none; color:var(--violet-600); font-weight:600; font-size:14px; white-space:nowrap; margin-top:2px; }
    .empty-careers { text-align:center; padding:56px 20px; color:var(--muted); background:var(--card); border:1px solid var(--line); border-radius:var(--radius); }
    @media (max-width:520px){ .apply-arrow{ display:none; } }
</style>
@endpush

@section('content')
    <div class="careers-head">
        <p class="eyebrow">Careers at NovaStackHub</p>
        <h1>Open positions</h1>
        <p>We build clean design and solid code for businesses worldwide. If that sounds like your kind of work, we'd love to hear from you.</p>
    </div>

    @if ($jobs->isEmpty())
        <div class="empty-careers">
            No open positions right now. Check back soon — or reach us at
            <a href="mailto:info@novastackhub.com">info@novastackhub.com</a>.
        </div>
    @else
        <div class="job-list">
            @foreach ($jobs as $job)
                <a class="job-card" href="{{ route('careers.show', $job) }}">
                    <div class="job-card-top">
                        <div>
                            <h2 class="job-title">{{ $job->title }}</h2>
                            <p class="job-summary">{{ $job->summary }}</p>
                        </div>
                        <span class="apply-arrow">View &amp; apply &rarr;</span>
                    </div>
                    <div class="job-tags">
                        @if ($job->department)
                            <span class="jtag jtag-dept">{{ $job->department }}</span>
                        @endif
                        <span class="jtag jtag-type">{{ $job->type }}</span>
                        <span class="jtag jtag-loc">{{ $job->location }}</span>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
@endsection
