@extends('layouts.app')

@section('title', 'Admin login')

@section('content')
    <div class="card" style="margin-top:32px;max-width:420px;margin-left:auto;margin-right:auto;">
        <div class="card-accent"></div>
        <div class="card-body">
            <p class="eyebrow">Recruitment dashboard</p>
            <h1 class="lead-title" style="font-size:22px;margin-top:8px;">Sign in</h1>
            <p class="lead-sub" style="margin-bottom:22px;">Enter the admin password to review applications.</p>

            <form method="POST" action="{{ route('admin.login.attempt') }}">
                @csrf
                <div class="field @error('password') has-error @enderror">
                    <label class="q" for="password">Password</label>
                    <input type="password" id="password" name="password" autocomplete="current-password"
                           placeholder="••••••••" autofocus required>
                    @error('password')<span class="err">{{ $message }}</span>@enderror
                </div>
                <button type="submit" class="btn" style="width:100%;">Sign in</button>
            </form>
        </div>
    </div>
@endsection
