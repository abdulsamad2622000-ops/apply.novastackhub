@extends('layouts.app')


@section('title', 'Edit Task')

@section('content')
@include('admin.partials.nav')
<h1>Edit Task</h1>

<form method="POST" action="{{ route('admin.tasks.update', $task) }}" class="admin-form">
    @csrf
    @method('PUT')
    @include('admin.tasks._form')
    <button type="submit" class="btn btn-primary">Update Task</button>
</form>
@endsection