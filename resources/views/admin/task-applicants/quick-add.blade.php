@extends('layouts.app')

@section('title', 'Quick Add Task Applicants')

@section('content')
@include('admin.partials.nav')

<h1>Add Task Applicant</h1>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<form method="POST" action="{{ route('admin.taskApplicants.quickadd.store') }}">
    @csrf
    <div class="form-group" style="margin-bottom:16px;">
        <label style="display:block; font-weight:600; margin-bottom:6px;">Name</label>
        <input type="text" name="name" value="{{ old('name') }}" style="width:100%; padding:10px; font-size:15px;" placeholder="Ali Khan">
    </div>
    <div class="form-group" style="margin-bottom:16px;">
        <label style="display:block; font-weight:600; margin-bottom:6px;">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" style="width:100%; padding:10px; font-size:15px;" placeholder="ali@example.com">
    </div>
    <div class="form-group" style="margin-bottom:16px;">
        <label style="display:block; font-weight:600; margin-bottom:6px;">Phone</label>
        <input type="text" name="phone" value="{{ old('phone') }}" style="width:100%; padding:10px; font-size:15px;" placeholder="03001234567">
    </div>
    <button type="submit" class="btn btn-primary">Add Applicant</button>
</form>
@endsection