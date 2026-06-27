@php $current = request()->routeIs('admin.jobs.*') ? 'jobs' : (request()->routeIs('admin.applications.*') ? 'applications' : ''); @endphp
<div class="admin-nav">
    <div class="admin-nav-links">
        <a href="{{ route('admin.jobs.index') }}" class="{{ $current === 'jobs' ? 'is-active' : '' }}">Jobs</a>
        <a href="{{ route('admin.applications.index') }}" class="{{ $current === 'applications' ? 'is-active' : '' }}">Applications</a>
    </div>
    <form method="POST" action="{{ route('admin.logout') }}">
        @csrf
        <button class="logout-btn" type="submit">Log out</button>
    </form>
</div>

@if (session('status'))
    <div class="flash-ok">{{ session('status') }}</div>
@endif

<style>
    .admin-nav { display:flex; align-items:center; justify-content:space-between; gap:16px; margin-bottom:20px; padding-bottom:14px; border-bottom:1px solid var(--line); }
    .admin-nav-links { display:flex; gap:6px; }
    .admin-nav-links a { font-size:14px; font-weight:600; color:var(--muted); text-decoration:none; padding:8px 14px; border-radius:8px; }
    .admin-nav-links a:hover { color:var(--text); background:#F3F4F8; }
    .admin-nav-links a.is-active { color:var(--violet-600); background:#E8F0FE; }
    .logout-btn { background:none; border:1px solid var(--line); color:var(--muted); border-radius:9px; padding:8px 14px; font:inherit; font-size:13.5px; cursor:pointer; }
    .logout-btn:hover { color:var(--text); border-color:#CFD3E0; }
    .flash-ok { background:var(--success-bg); border:1px solid #D2F0E1; color:#0B7A52; font-size:13.5px; font-weight:500; padding:11px 14px; border-radius:10px; margin-bottom:18px; }
</style>
