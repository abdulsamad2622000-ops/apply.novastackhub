@extends('layouts.app')

@section('title', 'Certificates')

@section('content')
@include('admin.partials.nav')
<div class="admin-header">
    <h1>Certificates</h1>
    <div style="display:flex; gap:8px; flex-wrap:wrap;">
        <form method="POST" action="{{ route('admin.certificates.issueApproved') }}" onsubmit="return confirm('Issue certificates for all approved students?');" style="display:inline;">
            @csrf
            <button type="submit" class="btn btn-secondary">Issue for approved students</button>
        </form>
        <form method="POST" action="{{ route('admin.certificates.emailAll') }}" onsubmit="return confirm('Email certificates to all students who have not received one yet?');" style="display:inline;">@csrf<button type="submit" class="btn btn-secondary">Email all pending</button></form> <a href="{{ route('admin.certificates.create') }}" class="btn btn-primary">+ New Certificate</a>
    </div>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<form method="GET" action="{{ route('admin.certificates.index') }}" style="margin-bottom: 16px;">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or certificate number..." style="padding: 10px; width: 320px; max-width: 100%;">
    <button type="submit" class="btn btn-secondary">Search</button>
</form>

<table class="admin-table">
    <thead>
        <tr>
            <th>Certificate #</th>
            <th>Name</th>
            <th>Email</th>
            <th>Title</th>
            <th>Issue Date</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($certificates as $cert)
            <tr>
                <td>{{ $cert->certificate_number }}</td>
                <td>{{ $cert->full_name }}</td>
                <td>{{ $cert->email ?: '—' }}</td>
                <td>{{ $cert->title }}</td>
                <td>{{ $cert->issue_date->format('d M Y') }}</td>
                <td>
                    @if ($cert->status === 'valid')
                        <span class="badge badge-success">Valid</span>
                    @else
                        <span class="badge badge-pending">Revoked</span>
                    @endif
                </td>
              <td class="actions">
                    <form method="POST" action="{{ route('admin.certificates.email', $cert) }}" style="display:inline;" onsubmit="return confirm('Email this certificate to the student?');">@csrf<button type="submit" style="background:none;border:none;padding:0;color:{{ $cert->emailed_at ? '#16a34a' : '#7c3aed' }};font-weight:600;cursor:pointer;font-size:inherit;font-family:inherit;">{{ $cert->emailed_at ? 'Emailed' : 'Email' }}</button></form> <a href="{{ route('admin.certificates.view', $cert) }}" target="_blank" style="color:#0f766e; font-weight:600;">View</a> <a href="{{ route('admin.certificates.pdf', $cert) }}" style="color:#b91c1c; font-weight:600;">PDF</a> <a href="{{ route('admin.certificates.download', $cert) }}" style="color:#2563eb; font-weight:600;">PNG</a>
                    <a href="{{ route('admin.certificates.qr', $cert) }}" target="_blank">QR Code</a>
                    <a href="{{ route('admin.certificates.edit', $cert) }}">Edit</a>
                    <form method="POST" action="{{ route('admin.certificates.destroy', $cert) }}" onsubmit="return confirm('Delete this certificate?');" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="link-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="muted">No certificates yet.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<div style="margin-top: 20px;">
    {{ $certificates->links() }}
</div>
@endsection
<style>.pagination svg, nav[role="navigation"] svg { width:16px !important; height:16px !important; vertical-align:middle; } nav[role="navigation"] { display:flex; align-items:center; gap:10px; flex-wrap:wrap; font-size:14px; margin-top:16px; } nav[role="navigation"] a, nav[role="navigation"] span { padding:6px 10px; border-radius:6px; text-decoration:none; }</style>