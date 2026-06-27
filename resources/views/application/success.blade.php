@extends('layouts.app')

@section('title', 'Application received')

@section('content')
    <div class="card" style="margin-top:24px;">
        <div class="card-accent"></div>
        <div class="card-body" style="text-align:center; padding-top:42px; padding-bottom:42px;">
            <div style="width:64px;height:64px;margin:0 auto 20px;border-radius:50%;
                        background:var(--success-bg);display:grid;place-items:center;">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none">
                    <path d="M5 13l4 4L19 7" stroke="#0F9D6B" stroke-width="2.4"
                          stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>

            <h1 class="lead-title" style="margin-bottom:10px;">Application received</h1>
            <p class="lead-sub" style="max-width:440px;margin:0 auto;">
                Thanks{{ $applicant ? ', '.\Illuminate\Support\Str::of($applicant)->before(' ') : '' }} —
                your application for the Client Acquisition Specialist role is in.
                Our team will review it and reach out if there's a fit.
            </p>

            <div class="actions" style="justify-content:center;margin-top:30px;">
                <a href="https://www.novastackhub.com/" class="btn" style="text-decoration:none;">Explore NovaStackHub</a>
            </div>
        </div>
    </div>
@endsection
