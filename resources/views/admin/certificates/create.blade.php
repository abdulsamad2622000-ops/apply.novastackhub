@extends('layouts.app')

@section('title', 'New Certificate')

@section('content')
@include('admin.partials.nav')
<h1>New Certificate</h1>

<form method="POST" action="{{ route('admin.certificates.store') }}" class="admin-form">
    @csrf
    @include('admin.certificates._form', ['certificate_number_default' => $suggested_number])
    <button type="submit" class="btn btn-primary">Create Certificate</button>
</form>
@endsection