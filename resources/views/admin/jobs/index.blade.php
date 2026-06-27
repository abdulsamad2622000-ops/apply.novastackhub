@extends('layouts.app')

@section('title', 'Manage jobs')

@push('head')
<style>
    .wrap { max-width: 100% !important; padding-left: 28px !important; padding-right: 28px !important; }
    .bar { display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap; margin-bottom:18px; }
    .bar h1 { font-family:var(--display); font-size:22px; margin:0; letter-spacing:-.3px; }
    .chip { font-size:12.5px; color:var(--violet-600); background:#E8F0FE; border:1px solid #D5E2FC; padding:3px 10px; border-radius:999px; margin-left:8px; vertical-align:middle; font-weight:600; }
    .btn-sm { padding:10px 16px; font-size:14px; border-radius:9px; text-decoration:none; display:inline-block; }
    .table-card { background:var(--card); border:1px solid var(--line); border-radius:var(--radius); box-shadow:var(--shadow); overflow:hidden; }
    table { width:100%; border-collapse:collapse; font-size:14px; }
    thead th { text-align:left; font-size:11.5px; text-transform:uppercase; letter-spacing:.08em; color:var(--muted); font-weight:600; padding:13px 16px; background:#FAFBFD; border-bottom:1px solid var(--line); }
    tbody td { padding:14px 16px; border-bottom:1px solid var(--line-2); vertical-align:middle; }
    tbody tr:last-child td { border-bottom:none; }
    tbody tr:hover { background:#FBFAFE; }
    .jname { font-weight:600; color:var(--text); text-decoration:none; }
    .jname:hover { color:var(--violet-600); }
    .jmeta { font-size:12.5px; color:var(--muted); }
    .badge { font-size:11.5px; padding:3px 9px; border-radius:999px; font-weight:600; white-space:nowrap; }
    .badge-open { background:var(--success-bg); color:#0B7A52; }
    .badge-closed { background:#F3F4F6; color:#6B7280; }
    .count-link { font-weight:600; color:var(--violet-600); text-decoration:none; }
    .count-link:hover { text-decoration:underline; }
    .row-actions { display:flex; gap:6px; justify-content:flex-end; flex-wrap:wrap; }
    .mini { font-size:12.5px; font-weight:600; padding:6px 11px; border-radius:8px; border:1px solid var(--line); background:#fff; cursor:pointer; text-decoration:none; color:var(--text); }
    .mini:hover { border-color:#CFD3E0; }
    .mini-danger { color:var(--danger); }
    .mini-danger:hover { border-color:#F7C4CE; background:var(--danger-bg); }
    .empty { text-align:center; padding:48px 20px; color:var(--muted); }
    @media (max-width:680px){ .hide-sm{ display:none; } }
</style>
@endpush

@section('content')
    @include('admin.partials.nav')

    <div class="bar">
        <h1>Jobs <span class="chip">{{ $jobs->count() }}</span></h1>
        <a href="{{ route('admin.jobs.create') }}" class="btn btn-sm">+ New job</a>
    </div>

    <div class="table-card">
        @if ($jobs->isEmpty())
            <div class="empty">No jobs yet. Click <strong>+ New job</strong> to post your first opening.</div>
        @else
            <div style="overflow-x:auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Position</th>
                            <th class="hide-sm">Status</th>
                            <th class="hide-sm">Applications</th>
                            <th style="text-align:right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jobs as $job)
                            <tr>
                                <td>
                                    <a class="jname" href="{{ route('careers.show', $job) }}" target="_blank" rel="noopener">{{ $job->title }}</a>
                                    <div class="jmeta">{{ $job->department ? $job->department.' · ' : '' }}{{ $job->type }} · {{ $job->location }}</div>
                                </td>
                                <td class="hide-sm">
                                    <span class="badge {{ $job->is_active ? 'badge-open' : 'badge-closed' }}">{{ $job->is_active ? 'Open' : 'Closed' }}</span>
                                </td>
                                <td class="hide-sm">
                                    <a class="count-link" href="{{ route('admin.applications.index', ['job' => $job->id]) }}">{{ $job->applications_count }}</a>
                                </td>
                                <td>
                                    <div class="row-actions">
                                        <a class="mini" href="{{ route('admin.jobs.edit', $job) }}">Edit</a>
                                        <form method="POST" action="{{ route('admin.jobs.toggle', $job) }}">
                                            @csrf @method('PATCH')
                                            <button class="mini" type="submit">{{ $job->is_active ? 'Close' : 'Open' }}</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.jobs.destroy', $job) }}"
                                              onsubmit="return confirm('Delete this job? Applications stay but lose their link.');">
                                            @csrf @method('DELETE')
                                            <button class="mini mini-danger" type="submit">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
