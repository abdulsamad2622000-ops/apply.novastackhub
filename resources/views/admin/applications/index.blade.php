@extends('layouts.app')

@section('title', 'Applications')

@push('head')
<style>
    /* Force this admin page to use the full page width */
    .wrap { max-width: 100% !important; padding-left: 28px !important; padding-right: 28px !important; }

    .bar { display:flex; align-items:center; justify-content:space-between; gap:14px; flex-wrap:wrap; margin-bottom:14px; }
    .bar h1 { font-family:var(--display); font-size:22px; margin:0; letter-spacing:-.3px; }
    .chip { font-size:12.5px; color:var(--violet-600); background:#E8F0FE; border:1px solid #D5E2FC; padding:3px 10px; border-radius:999px; margin-left:8px; vertical-align:middle; font-weight:600; }
    .toolbar { display:flex; gap:8px; flex-wrap:wrap; align-items:center; margin-bottom:14px; }
    .toolbar select, .toolbar input { padding:9px 12px; font:inherit; font-size:14px; border:1px solid var(--line); border-radius:9px; background:#FBFBFE; }
    .toolbar input { width:200px; }
    .btn-sm { padding:9px 16px; font-size:14px; border-radius:9px; }
    .btn-ghost-sm { padding:9px 14px; font-size:13.5px; font-weight:600; border:1px solid var(--line); border-radius:9px; background:#fff; color:var(--text); text-decoration:none; }
    .btn-ghost-sm:hover { border-color:#CFD3E0; }

    .table-card { background:var(--card); border:1px solid var(--line); border-radius:var(--radius); box-shadow:var(--shadow); overflow:hidden; }
    .table-scroll { overflow-x:auto; }
    table.apps { width:100%; border-collapse:collapse; font-size:13.5px; white-space:nowrap; }
    table.apps thead th { text-align:left; font-size:11px; text-transform:uppercase; letter-spacing:.06em; color:var(--muted); font-weight:600; padding:11px 14px; background:#FAFBFD; border-bottom:1px solid var(--line); position:sticky; top:0; }
    table.apps tbody td { padding:11px 14px; border-bottom:1px solid var(--line-2); vertical-align:middle; }
    table.apps tbody tr:last-child td { border-bottom:none; }
    table.apps tbody tr:hover { background:#FBFAFE; }
    table.apps a.lnk { color:var(--violet-600); text-decoration:none; }
    table.apps a.lnk:hover { text-decoration:underline; }
    .nm { font-weight:600; color:var(--text); text-decoration:none; }
    .nm:hover { color:var(--violet-600); }
    .truncate { max-width:220px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; display:inline-block; vertical-align:middle; }
    .muted { color:var(--muted); }
    .stt { font-size:11px; padding:3px 9px; border-radius:999px; font-weight:600; }
    .stt-Pending { background:#FEF6E7; color:#B45309; }
    .stt-Shortlisted { background:#E8F0FE; color:#1E40AF; }
    .stt-Rejected { background:#FEF2F4; color:#B91C1C; }
    .stt-Hired { background:#ECFAF3; color:#0B7A52; }
    .acts a { font-weight:600; color:var(--violet-600); text-decoration:none; margin-right:8px; }
    .acts a:hover { text-decoration:underline; }
    .empty { text-align:center; padding:48px 20px; color:var(--muted); }

    /* bulk bar */
    .bulkbar { display:none; align-items:center; gap:12px; flex-wrap:wrap; background:#0F1E4D; color:#fff; border-radius:11px; padding:11px 16px; margin-bottom:12px; }
    .bulkbar.show { display:flex; }
    .bulkbar .sel-count { font-weight:600; font-size:14px; }
    .bulkbar select { padding:8px 11px; border-radius:8px; border:none; font:inherit; font-size:13.5px; }
    .bulkbar .bb-apply { background:var(--cyan); color:#062a33; border:none; font-weight:700; padding:8px 14px; border-radius:8px; cursor:pointer; font-size:13.5px; }
    .bulkbar .bb-del { background:#fff; color:#B91C1C; border:none; font-weight:700; padding:8px 14px; border-radius:8px; cursor:pointer; font-size:13.5px; }
    .bulkbar .bb-clear { margin-left:auto; background:none; border:1px solid rgba(255,255,255,.3); color:#fff; padding:7px 12px; border-radius:8px; cursor:pointer; font-size:13px; }
    .ck { width:16px; height:16px; accent-color:var(--violet-600); cursor:pointer; }

    .pager-row { display:flex; align-items:center; justify-content:space-between; gap:12px; margin-top:18px; flex-wrap:wrap; }
    .pager-info { font-size:13px; color:var(--muted); }
    .pager-links { display:flex; gap:8px; }
    .pager-btn { font-size:13.5px; font-weight:600; padding:8px 14px; border:1px solid var(--line); border-radius:9px; color:var(--violet-600); text-decoration:none; background:#fff; }
    .pager-btn.is-disabled { color:#B6BAC8; border-color:var(--line-2); pointer-events:none; }
</style>
@endpush

@section('content')
    @include('admin.partials.nav')

    <div class="bar">
        <h1>Applications <span class="chip">{{ $total }}</span></h1>
        <div style="display:flex; gap:8px; flex-wrap:wrap; align-items:center;">
            <button type="button" class="btn-ghost-sm" id="copyEmailsBtn"
                    data-emails="{{ $allEmails->implode('; ') }}">
                📋 Copy emails ({{ $allEmails->count() }})
            </button>
            <a class="btn-ghost-sm" href="{{ route('admin.applications.export', request()->query()) }}">⤓ Export CSV</a>
        </div>
    </div>

    {{-- Filters --}}
    <form method="GET" action="{{ route('admin.applications.index') }}" class="toolbar">
        <select name="job" onchange="this.form.submit()">
            <option value="">All jobs</option>
            @foreach ($jobs as $j)
                <option value="{{ $j->id }}" @selected($activeJob && $activeJob->id === $j->id)>{{ $j->title }}</option>
            @endforeach
        </select>
        <select name="status" onchange="this.form.submit()">
            <option value="">All statuses</option>
            @foreach ($statuses as $st)
                <option value="{{ $st }}" @selected($activeStatus === $st)>{{ $st }}</option>
            @endforeach
        </select>
        <input type="text" name="q" value="{{ $search }}" placeholder="Search name, email, phone">
        <button class="btn btn-sm" type="submit">Search</button>
    </form>

    @if ($applications->isEmpty())
        <div class="table-card"><div class="empty">
            @if ($search || $activeJob || $activeStatus) No applications match your filters. @else No applications yet. @endif
        </div></div>
    @else
        {{-- Bulk action bar --}}
        <div class="bulkbar" id="bulkbar">
            <span class="sel-count"><span id="selCount">0</span> selected</span>
            <select id="bulkStatus">
                @foreach ($statuses as $st)
                    <option value="{{ $st }}">Mark as {{ $st }}</option>
                @endforeach
            </select>
            <button type="button" class="bb-apply" id="bbApply">Apply</button>
            <button type="button" class="bb-del" id="bbDelete">Delete selected</button>
            <button type="button" class="bb-clear" id="bbClear">Clear</button>
        </div>

        <form method="POST" action="{{ route('admin.applications.bulk') }}" id="bulkForm">
            @csrf
            <input type="hidden" name="action" id="bulkAction">

            <div class="table-card">
                <div class="table-scroll">
                    <table class="apps">
                        <thead>
                            <tr>
                                <th><input type="checkbox" class="ck" id="ckAll"></th>
                                <th>Status</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>City</th>
                                <th>Education</th>
                                <th>Skills</th>
                                <th>LinkedIn</th>
                                <th>Portfolio</th>
                                <th>Applied for</th>
                                <th>Experience</th>
                                <th>Commission</th>
                                <th>Platforms</th>
                                <th>Experience details</th>
                                <th>Additional</th>
                                <th>CV</th>
                                <th>Received</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($applications as $app)
                                <tr>
                                    <td><input type="checkbox" class="ck row-ck" name="ids[]" value="{{ $app->id }}"></td>
                                    <td><span class="stt stt-{{ $app->status }}">{{ $app->status }}</span></td>
                                    <td><a class="nm" href="{{ route('admin.applications.show', $app) }}">{{ $app->full_name }}</a></td>
                                    <td><a class="lnk" href="mailto:{{ $app->email }}">{{ $app->email }}</a></td>
                                    <td>{{ $app->phone }}</td>
                                    <td>{{ $app->city ?: '—' }}</td>
                                    <td>{{ $app->education ?: '—' }}</td>
                                    <td><span class="truncate" title="{{ $app->skills }}">{{ $app->skills ?: '—' }}</span></td>
                                    <td>@if($app->linkedin_url)<a class="lnk truncate" href="{{ $app->linkedin_url }}" target="_blank" rel="noopener" title="{{ $app->linkedin_url }}">{{ $app->linkedin_url }}</a>@else<span class="muted">—</span>@endif</td>
                                    <td>@if($app->portfolio_url)<a class="lnk truncate" href="{{ $app->portfolio_url }}" target="_blank" rel="noopener" title="{{ $app->portfolio_url }}">{{ $app->portfolio_url }}</a>@else<span class="muted">—</span>@endif</td>
                                    <td>{{ $app->job?->title ?? '—' }}</td>
                                    <td>{{ $app->experience_years }}</td>
                                    <td>@if(is_null($app->commission_only))<span class="muted">—</span>@else{{ $app->commission_only ? 'Yes' : 'No' }}@endif</td>
                                    <td>{{ !empty($app->outreach_platforms) ? implode(', ', $app->outreach_platforms) : '—' }}</td>
                                    <td><span class="truncate" title="{{ $app->experience_description }}">{{ $app->experience_description }}</span></td>
                                    <td><span class="truncate" title="{{ $app->additional_info }}">{{ $app->additional_info ?: '—' }}</span></td>
                                    <td><a class="lnk" href="{{ route('admin.applications.cv', $app) }}">Download</a></td>
                                    <td>{{ $app->created_at->format('d M Y') }}</td>
                                    <td class="acts">
                                        <a href="{{ route('admin.applications.show', $app) }}">View</a>
                                        <a href="{{ route('admin.applications.edit', $app) }}">Edit</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </form>
    @endif

    @if ($applications->hasPages())
        <div class="pager-row">
            <span class="pager-info">Showing {{ $applications->firstItem() }}–{{ $applications->lastItem() }} of {{ $applications->total() }}</span>
            <span class="pager-links">
                @if ($applications->onFirstPage())<span class="pager-btn is-disabled">&larr; Prev</span>@else<a class="pager-btn" href="{{ $applications->previousPageUrl() }}">&larr; Prev</a>@endif
                @if ($applications->hasMorePages())<a class="pager-btn" href="{{ $applications->nextPageUrl() }}">Next &rarr;</a>@else<span class="pager-btn is-disabled">Next &rarr;</span>@endif
            </span>
        </div>
    @endif
@endsection

@push('scripts')
<script>
    (function () {
        const ckAll = document.getElementById('ckAll');
        const rows = Array.from(document.querySelectorAll('.row-ck'));
        const bar = document.getElementById('bulkbar');
        const countEl = document.getElementById('selCount');
        const form = document.getElementById('bulkForm');
        const actionInput = document.getElementById('bulkAction');
        if (!form) return;

        function selected() { return rows.filter(c => c.checked); }
        function refresh() {
            const n = selected().length;
            countEl.textContent = n;
            bar.classList.toggle('show', n > 0);
            if (ckAll) ckAll.checked = n > 0 && n === rows.length;
        }

        if (ckAll) ckAll.addEventListener('change', () => { rows.forEach(c => c.checked = ckAll.checked); refresh(); });
        rows.forEach(c => c.addEventListener('change', refresh));

        document.getElementById('bbApply').addEventListener('click', () => {
            if (selected().length === 0) return;
            actionInput.value = document.getElementById('bulkStatus').value;
            form.submit();
        });
        document.getElementById('bbDelete').addEventListener('click', () => {
            if (selected().length === 0) return;
            if (!confirm('Delete the selected applications? This also removes their CV files and cannot be undone.')) return;
            actionInput.value = 'delete';
            form.submit();
        });
        document.getElementById('bbClear').addEventListener('click', () => {
            rows.forEach(c => c.checked = false); if (ckAll) ckAll.checked = false; refresh();
        });
    })();

    // Copy all (filtered) emails to clipboard for pasting into Outlook BCC
    (function () {
        const btn = document.getElementById('copyEmailsBtn');
        if (!btn) return;
        const original = btn.textContent;
        btn.addEventListener('click', async () => {
            const emails = btn.dataset.emails || '';
            if (!emails.trim()) { alert('No emails to copy.'); return; }
            try {
                await navigator.clipboard.writeText(emails);
            } catch (e) {
                // Fallback for older browsers / non-secure contexts
                const ta = document.createElement('textarea');
                ta.value = emails; document.body.appendChild(ta); ta.select();
                document.execCommand('copy'); document.body.removeChild(ta);
            }
            btn.textContent = '✓ Copied!';
            setTimeout(() => { btn.textContent = original; }, 1800);
        });
    })();
</script>
@endpush
