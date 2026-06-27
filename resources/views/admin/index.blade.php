@extends('layouts.app')

@section('title', 'Applications')

@push('head')
<style>
    .admin-bar { display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap; margin-bottom:18px; }
    .admin-bar h1 { font-family:var(--display); font-size:22px; margin:0; letter-spacing:-.3px; }
    .count-chip { font-size:12.5px; color:var(--violet-600); background:#F1ECFD; border:1px solid #E4DAFB; padding:3px 10px; border-radius:999px; margin-left:8px; vertical-align:middle; font-weight:600;}
    .search-row { display:flex; gap:8px; }
    .search-row input { width:230px; padding:9px 12px; }
    .btn-sm { padding:9px 16px; font-size:14px; border-radius:9px; }
    .logout-btn { background:none;border:1px solid var(--line);color:var(--muted);border-radius:9px;padding:9px 14px;font:inherit;font-size:13.5px;cursor:pointer; }
    .logout-btn:hover { color:var(--text); border-color:#CFD3E0; }

    .table-card { background:var(--card); border:1px solid var(--line); border-radius:var(--radius); box-shadow:var(--shadow); overflow:hidden; }
    table { width:100%; border-collapse:collapse; font-size:14px; }
    thead th { text-align:left; font-size:11.5px; text-transform:uppercase; letter-spacing:.08em; color:var(--muted); font-weight:600; padding:13px 16px; background:#FAFBFD; border-bottom:1px solid var(--line); }
    tbody td { padding:14px 16px; border-bottom:1px solid var(--line-2); vertical-align:middle; }
    tbody tr:last-child td { border-bottom:none; }
    tbody tr:hover { background:#FBFAFE; }
    .applicant-name { font-weight:600; color:var(--text); text-decoration:none; }
    .applicant-name:hover { color:var(--violet-600); }
    .applicant-sub { font-size:12.5px; color:var(--muted); }
    .tag { font-size:11.5px; padding:3px 9px; border-radius:999px; font-weight:600; white-space:nowrap; }
    .tag-yes { background:var(--success-bg); color:#0B7A52; }
    .tag-no  { background:#F3F4F6; color:#6B7280; }
    .row-actions a { font-size:13px; font-weight:600; color:var(--violet-600); text-decoration:none; white-space:nowrap; }
    .row-actions a:hover { text-decoration:underline; }
    .empty { text-align:center; padding:48px 20px; color:var(--muted); }
    .pager-row { display:flex; align-items:center; justify-content:space-between; gap:12px; margin-top:18px; flex-wrap:wrap; }
    .pager-info { font-size:13px; color:var(--muted); }
    .pager-links { display:flex; gap:8px; }
    .pager-btn { font-size:13.5px; font-weight:600; padding:8px 14px; border:1px solid var(--line); border-radius:9px; color:var(--violet-600); text-decoration:none; background:#fff; }
    .pager-btn:hover { border-color:var(--violet-600); }
    .pager-btn.is-disabled { color:#B6BAC8; border-color:var(--line-2); pointer-events:none; }
    .pager { margin-top:18px; }
    .pager nav { justify-content:center; }
    @media (max-width: 640px) {
        .hide-sm { display:none; }
        .search-row input { width:150px; }
    }
</style>
@endpush

@section('content')
    <div class="admin-bar">
        <h1>Applications <span class="count-chip">{{ $total }}</span></h1>
        <div style="display:flex;gap:8px;align-items:center;">
            <form method="GET" action="{{ route('admin.applications.index') }}" class="search-row">
                <input type="text" name="q" value="{{ $search }}" placeholder="Search name, email, phone">
                <button class="btn btn-sm" type="submit">Search</button>
            </form>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button class="logout-btn" type="submit">Log out</button>
            </form>
        </div>
    </div>

    <div class="table-card">
        @if ($applications->isEmpty())
            <div class="empty">
                @if ($search)
                    No applications match &ldquo;{{ $search }}&rdquo;.
                @else
                    No applications yet. They'll show up here as they come in.
                @endif
            </div>
        @else
            <div style="overflow-x:auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Applicant</th>
                            <th class="hide-sm">Experience</th>
                            <th class="hide-sm">Commission</th>
                            <th class="hide-sm">Received</th>
                            <th style="text-align:right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($applications as $app)
                            <tr>
                                <td>
                                    <a class="applicant-name" href="{{ route('admin.applications.show', $app) }}">{{ $app->full_name }}</a>
                                    <div class="applicant-sub">{{ $app->email }} · {{ $app->phone }}</div>
                                </td>
                                <td class="hide-sm">{{ $app->experience_years }}</td>
                                <td class="hide-sm">
                                    <span class="tag {{ $app->commission_only ? 'tag-yes' : 'tag-no' }}">
                                        {{ $app->commission_only ? 'Yes' : 'No' }}
                                    </span>
                                </td>
                                <td class="hide-sm">{{ $app->created_at->format('d M Y') }}</td>
                                <td class="row-actions" style="text-align:right;">
                                    <a href="{{ route('admin.applications.show', $app) }}">View</a>
                                    &nbsp;·&nbsp;
                                    <a href="{{ route('admin.applications.cv', $app) }}">CV</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    @if ($applications->hasPages())
        <div class="pager-row">
            <span class="pager-info">
                Showing {{ $applications->firstItem() }}–{{ $applications->lastItem() }} of {{ $applications->total() }}
            </span>
            <span class="pager-links">
                @if ($applications->onFirstPage())
                    <span class="pager-btn is-disabled">&larr; Prev</span>
                @else
                    <a class="pager-btn" href="{{ $applications->previousPageUrl() }}">&larr; Prev</a>
                @endif

                @if ($applications->hasMorePages())
                    <a class="pager-btn" href="{{ $applications->nextPageUrl() }}">Next &rarr;</a>
                @else
                    <span class="pager-btn is-disabled">Next &rarr;</span>
                @endif
            </span>
        </div>
    @endif
@endsection
