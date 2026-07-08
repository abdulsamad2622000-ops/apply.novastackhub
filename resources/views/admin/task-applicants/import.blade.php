@extends('layouts.app')
@section('title', 'Import Task Applicants')
@section('content')
@include('admin.partials.nav')
<div class="admin-header">
    <h1>Import Task Applicants (CSV)</h1>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

@if ($errors->any())
    <div class="alert alert-error">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="admin-form">
    <p class="muted" style="margin-top:0;">
        Google Sheet ko File &rarr; Download &rarr; CSV karke yahan upload karo.
        Expected columns: Timestamp, Full Name, Phone Number, Email Address, City, Education / Semester, Skills.
    </p>
    <form method="POST" action="{{ route('admin.task-applicants.import.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="csv_file">CSV File</label>
            <input type="file" id="csv_file" name="csv_file" accept=".csv" required>
        </div>
        <button type="submit" class="btn-primary">Import</button>
        <a href="{{ route('admin.task-applicants.index') }}" class="btn-secondary">Cancel</a>
    </form>
</div>
@endsection