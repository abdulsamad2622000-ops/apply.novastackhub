@extends('layouts.app')

@section('title', 'New Task')

@section('content')
@include('admin.partials.nav')
<h1>New Task</h1>

<form method="POST" action="{{ route('admin.tasks.store') }}" class="admin-form">
    @csrf
    @include('admin.tasks._form')
    <button type="submit" class="btn btn-primary">Create Task</button>
</form>
@endsection