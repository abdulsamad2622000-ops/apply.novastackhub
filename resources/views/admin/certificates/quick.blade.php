@extends('layouts.app')

@section('title', 'Quick Certificate')

@section('content')
@include('admin.partials.nav')

<div class="admin-header">
    <h1>Quick Certificate</h1>
    <a href="{{ route('admin.certificates.index') }}" class="btn btn-secondary">&larr; All Certificates</a>
</div>

@if ($errors->any())
    <div class="alert alert-error">
        <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
@endif

<div class="admin-form">
    <p style="margin-top:0;color:#5a6b7e;font-size:14px;">
        Name aur dates daalo &mdash; PDF turant download ho jayegi. Record bhi save hoga taake QR aur /verify kaam kare.
    </p>

    <form method="POST" action="{{ route('admin.certificates.quick.store') }}">
        @csrf

        <div class="form-group">
            <label for="full_name">Full Name</label>
            <input type="text" id="full_name" name="full_name" value="{{ old('full_name') }}" required autofocus>
        </div>

        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" value="{{ old('title', 'Web Development Internship') }}" required>
        </div>

        <div class="form-group">
            <label for="start_date">Internship Start Date</label>
            <input type="date" id="start_date" name="start_date" value="{{ old('start_date', '2026-06-01') }}">
        </div>

        <div class="form-group">
            <label for="end_date">Internship End Date</label>
            <input type="date" id="end_date" name="end_date" value="{{ old('end_date', '2026-07-31') }}">
        </div>

        <div class="form-group">
            <label for="email">Student Email (optional)</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}">
        </div>

        <div class="form-group"><label style="font-weight:normal;"><input type="checkbox" name="send_email" value="1"> Email this certificate to the student (email zaroori hai upar)</label></div>

        <button type="submit" class="btn btn-primary">Create Certificate</button>
    </form>
</div>
@endsection