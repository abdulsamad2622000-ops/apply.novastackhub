@extends('layouts.app')

@section('title', 'Verify Certificate')

@section('content')
<div class="auth-wrap">
    <div class="auth-card">
        <h1>Verify Certificate</h1>
        <p class="muted">Enter the certificate number to check its validity.</p>

        <form method="POST" action="{{ route('verify.check') }}">
            @csrf
            <div class="form-group">
                <label for="certificate_number">Certificate Number</label>
                <input type="text" id="certificate_number" name="certificate_number" value="{{ old('certificate_number') }}" placeholder="e.g. NSH-2026-AB12CD" required autofocus>
            </div>
            <button type="submit" class="btn btn-primary">Verify</button>
        </form>

        @if (isset($searched) && $searched)
            <div style="margin-top: 24px;">
                @if ($result && $result->isValid())
                    <div class="alert alert-success">
                        <strong>✅ Valid Certificate</strong>
                        <p style="margin: 10px 0 0;"><strong>Name:</strong> {{ $result->full_name }}</p>
                        <p style="margin: 4px 0 0;"><strong>Title:</strong> {{ $result->title }}</p>
                        <p style="margin: 4px 0 0;"><strong>Issue Date:</strong> {{ $result->issue_date->format('d M Y') }}</p>
                        @if ($result->completion_date)
                            <p style="margin: 4px 0 0;"><strong>Completion Date:</strong> {{ $result->completion_date->format('d M Y') }}</p>
                        @endif
                    </div>
                @elseif ($result && ! $result->isValid())
                    <div class="alert alert-error">
                        <strong>⚠️ Certificate Revoked</strong>
                        <p style="margin: 10px 0 0;">This certificate ({{ $result->certificate_number }}) has been revoked and is no longer valid.</p>
                    </div>
                @else
                    <div class="alert alert-error">
                        <strong>❌ Not Found</strong>
                        <p style="margin: 10px 0 0;">No certificate found with this number. Please check and try again.</p>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection