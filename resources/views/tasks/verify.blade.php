@extends('layouts.app')

@section('title', 'Submit Task')

@section('content')
<div class="auth-wrap">
    <div class="auth-card">
        <h1>Task Submission</h1>
<p class="muted">Apna email ya phone number daalein jo form fill karte waqt use kiya tha.</p>
        @if ($errors->any())
            <div class="alert alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('tasks.verify.submit') }}">
            @csrf
       <div class="form-group">
                <label for="identifier">Email ya Phone Number</label>
                <input type="text" id="identifier" name="identifier" value="{{ old('identifier') }}" required autofocus>
            </div>
            <button type="submit" class="btn btn-primary">Verify &amp; Continue</button>
        </form>
    </div>
</div>
@endsection