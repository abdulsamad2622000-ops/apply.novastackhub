@extends('layouts.app')
@section('title', 'Task Applicants')
@section('content')
@include('admin.partials.nav')
<div class="admin-header">
    <h1>Task Applicants</h1>
    <a href="{{ route('admin.task-applicants.import') }}" class="btn-primary">Import CSV</a>
</div>



<table class="admin-table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>City</th>
            <th>Education</th>
            <th>Skills</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @forelse ($applicants as $applicant)
            <tr>
                <td>{{ $applicant->full_name }}</td>
                <td>{{ $applicant->email ?? '—' }}</td>
                <td>{{ $applicant->phone ?? '—' }}</td>
                <td>{{ $applicant->city ?? '—' }}</td>
                <td>{{ $applicant->education ?? '—' }}</td>
                <td>{{ $applicant->skills ?? '—' }}</td>
                <td class="actions">
                    <form method="POST" action="{{ route('admin.task-applicants.destroy', $applicant) }}" onsubmit="return confirm('Delete this applicant?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="link-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="7">Koi applicant nahi mila. Pehle CSV import karo.</td></tr>
        @endforelse
    </tbody>
</table>

<div class="pagination-simple">
    @if ($applicants->onFirstPage())
        <span class="pg-btn pg-disabled">&laquo; Previous</span>
    @else
        <a href="{{ $applicants->previousPageUrl() }}" class="pg-btn">&laquo; Previous</a>
    @endif

    <span class="pg-info">Page {{ $applicants->currentPage() }} of {{ $applicants->lastPage() }}</span>

    @if ($applicants->hasMorePages())
        <a href="{{ $applicants->nextPageUrl() }}" class="pg-btn">Next &raquo;</a>
    @else
        <span class="pg-btn pg-disabled">Next &raquo;</span>
    @endif
</div>
@endsection