@extends('layouts.app')

@section('title', 'Tasks')

@section('content')
@include('admin.partials.nav')
<div class="admin-header">
    <h1>Tasks</h1>
    <a href="{{ route('admin.tasks.create') }}" class="btn btn-primary">+ New Task</a>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<table class="admin-table">
    <thead>
        <tr>
            <th>Title</th>
            <th>Due Date</th>
            <th>Order</th>
            <th>Status</th>
            <th>Submissions</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($tasks as $task)
            <tr>
                <td>{{ $task->title }}</td>
                <td>{{ $task->due_date ? $task->due_date->format('d M Y') : '-' }}</td>
                <td>{{ $task->sort_order }}</td>
                <td>
                    @if ($task->is_active)
                        <span class="badge badge-success">Active</span>
                    @else
                        <span class="badge badge-pending">Hidden</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.tasks.submissions', $task) }}">
                        {{ $task->submissions_count }} view
                    </a>
                </td>
                <td class="actions">
                    <a href="{{ route('admin.tasks.edit', $task) }}">Edit</a>
                    <form method="POST" action="{{ route('admin.tasks.destroy', $task) }}" onsubmit="return confirm('Yeh task delete karna hai?');" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="link-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="muted">Abhi koi task nahi bana.</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection