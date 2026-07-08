@extends('layouts.app')

@section('title', 'Edit Certificate')

@section('content')
@include('admin.partials.nav')
<h1>Edit Certificate</h1>

<form method="POST" action="{{ route('admin.certificates.update', $certificate) }}" class="admin-form">
    @csrf
    @method('PUT')
    @include('admin.certificates._form')
    <button type="submit" class="btn btn-primary">Update Certificate</button>
</form>
@endsection