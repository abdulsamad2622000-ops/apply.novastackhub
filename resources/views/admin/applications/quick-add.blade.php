@extends('layouts.app')

@section('title', 'Quick Add Applicants')

@section('content')
<h1>Quick Add Applicants</h1>
<p class="muted">Har line me ek student: <strong>Naam, Email</strong> (comma se separate). Ek sath 200 bhi paste kar sakte ho.</p>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

@if (session('quickAddErrors') && count(session('quickAddErrors')) > 0)
    <div class="alert alert-error">
        <ul>
            @foreach (session('quickAddErrors') as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('admin.applications.quickadd.store') }}">
    @csrf
    <div class="form-group">
        <textarea name="entries" rows="15" style="width:100%; font-family: monospace;" placeholder="Ali Khan, ali@example.com&#10;Sara Ahmed, sara@example.com&#10;Bilal Raza, bilal@example.com">{{ old('entries') }}</textarea>
    </div>
    <button type="submit" class="btn btn-primary">Add All</button>
</form>
@endsection