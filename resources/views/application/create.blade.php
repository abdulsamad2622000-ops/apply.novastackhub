@extends('layouts.app')

@section('title', 'Client Acquisition Specialist')

@section('content')
    <p class="eyebrow">We're hiring · Remote</p>

    <div class="card" style="margin-top:12px;">
        <div class="card-accent"></div>
        <div class="card-body">
            <h1 class="lead-title">Client Acquisition Specialist — Application</h1>
            <p class="lead-sub">
                Thank you for your interest in joining NovaStackHub as a Client Acquisition
                Specialist. Please fill out the form below.
            </p>

            <div class="meta-row">
                <span class="pill">Remote</span>
                <span class="pill">Commission-based</span>
                <span class="pill">Lead generation</span>
            </div>

            <div class="divider"></div>

            @if ($errors->any())
                <div class="note-warn" role="alert">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                        <circle cx="12" cy="12" r="9" stroke="#9F1239" stroke-width="2"/>
                        <path d="M12 7v6M12 16.5v.5" stroke="#9F1239" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    <span>Please fix the highlighted fields below and submit again.</span>
                </div>
            @endif

            <form method="POST" action="{{ route('apply.store') }}" enctype="multipart/form-data" novalidate>
                @csrf

                {{-- Honeypot: hidden from humans, catches bots --}}
                <div style="position:absolute;left:-9999px;" aria-hidden="true">
                    <label>Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
                </div>

                {{-- Full name --}}
                <div class="field @error('full_name') has-error @enderror">
                    <label class="q" for="full_name">Full name <span class="req">*</span></label>
                    <input type="text" id="full_name" name="full_name" value="{{ old('full_name') }}"
                           placeholder="e.g. Ayesha Khan" required>
                    @error('full_name')<span class="err">{{ $message }}</span>@enderror
                </div>

                {{-- Email --}}
                <div class="field @error('email') has-error @enderror">
                    <label class="q" for="email">Email address <span class="req">*</span></label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                           placeholder="you@example.com" required>
                    @error('email')<span class="err">{{ $message }}</span>@enderror
                </div>

                {{-- Phone --}}
                <div class="field @error('phone') has-error @enderror">
                    <label class="q" for="phone">Phone / WhatsApp number <span class="req">*</span></label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                           placeholder="+92 3XX XXXXXXX" required>
                    @error('phone')<span class="err">{{ $message }}</span>@enderror
                </div>

                {{-- LinkedIn --}}
                <div class="field @error('linkedin_url') has-error @enderror">
                    <label class="q" for="linkedin_url">LinkedIn profile link <span class="req">*</span></label>
                    <input type="url" id="linkedin_url" name="linkedin_url" value="{{ old('linkedin_url') }}"
                           placeholder="https://linkedin.com/in/your-handle" required>
                    @error('linkedin_url')<span class="err">{{ $message }}</span>@enderror
                </div>

                {{-- Upwork --}}
                <div class="field @error('upwork_url') has-error @enderror">
                    <label class="q" for="upwork_url">
                        Upwork profile link
                        <span class="hint">Optional — only if you have one</span>
                    </label>
                    <input type="url" id="upwork_url" name="upwork_url" value="{{ old('upwork_url') }}"
                           placeholder="https://upwork.com/freelancers/...">
                    @error('upwork_url')<span class="err">{{ $message }}</span>@enderror
                </div>

                {{-- Experience years --}}
                @php
                    $years = ['Fresher', '0–1 year', '1–3 years', '3+ years'];
                @endphp
                <div class="field @error('experience_years') has-error @enderror">
                    <label class="q">Years of experience in lead generation / client acquisition <span class="req">*</span></label>
                    <div class="choices">
                        @foreach ($years as $option)
                            <label class="choice">
                                <input type="radio" name="experience_years" value="{{ $option }}"
                                       @checked(old('experience_years') === $option) required>
                                <span>{{ $option }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('experience_years')<span class="err">{{ $message }}</span>@enderror
                </div>

                {{-- Experience description --}}
                <div class="field @error('experience_description') has-error @enderror">
                    <label class="q" for="experience_description">
                        Briefly describe your experience in lead generation or client acquisition <span class="req">*</span>
                    </label>
                    <textarea id="experience_description" name="experience_description" rows="4"
                              placeholder="Tools you use, channels you've worked, results you've driven…" required>{{ old('experience_description') }}</textarea>
                    @error('experience_description')<span class="err">{{ $message }}</span>@enderror
                </div>

                {{-- Commission-only --}}
                <div class="field @error('commission_only') has-error @enderror">
                    <label class="q">Have you previously worked on a commission-only basis? <span class="req">*</span></label>
                    <div class="choices">
                        @foreach (['Yes', 'No'] as $option)
                            <label class="choice">
                                <input type="radio" name="commission_only" value="{{ $option }}"
                                       @checked(old('commission_only') === $option) required>
                                <span>{{ $option }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('commission_only')<span class="err">{{ $message }}</span>@enderror
                </div>

                {{-- Outreach platforms --}}
                @php
                    $platforms = ['LinkedIn', 'Upwork', 'Fiverr', 'Cold Email', 'Other'];
                    $selectedPlatforms = old('outreach_platforms', []);
                @endphp
                <div class="field @error('outreach_platforms') has-error @enderror">
                    <label class="q">Which platforms have you used for outreach?</label>
                    <div class="choices choices-grid">
                        @foreach ($platforms as $platform)
                            <label class="choice">
                                <input type="checkbox" name="outreach_platforms[]" value="{{ $platform }}"
                                       @checked(in_array($platform, $selectedPlatforms))>
                                <span>{{ $platform }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('outreach_platforms')<span class="err">{{ $message }}</span>@enderror
                </div>

                {{-- CV upload --}}
                <div class="field @error('cv') has-error @enderror">
                    <label class="q" for="cv">Upload your CV / resume <span class="req">*</span></label>
                    <label class="file-drop" for="cv">
                        <span class="file-ico">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                <path d="M12 16V4m0 0L7 9m5-5l5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M4 17v2a1 1 0 001 1h14a1 1 0 001-1v-2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </span>
                        <span class="file-text">
                            <strong>Choose a file</strong>
                            <span>PDF, DOC or DOCX · max 10 MB</span>
                        </span>
                        <input type="file" id="cv" name="cv" accept=".pdf,.doc,.docx" required>
                    </label>
                    <span class="file-name" id="cv-name"></span>
                    @error('cv')<span class="err">{{ $message }}</span>@enderror
                </div>

                {{-- Additional info --}}
                <div class="field @error('additional_info') has-error @enderror">
                    <label class="q" for="additional_info">
                        Anything else you'd like to share?
                        <span class="hint">Optional</span>
                    </label>
                    <textarea id="additional_info" name="additional_info" rows="3"
                              placeholder="Availability, expectations, links to past work…">{{ old('additional_info') }}</textarea>
                    @error('additional_info')<span class="err">{{ $message }}</span>@enderror
                </div>

                <div class="actions">
                    <button type="submit" class="btn" id="submitBtn">Submit application</button>
                    <a href="{{ route('apply.create') }}" class="btn-ghost">Clear form</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Show the chosen file name
    const cvInput = document.getElementById('cv');
    const cvName = document.getElementById('cv-name');
    cvInput.addEventListener('change', () => {
        if (cvInput.files.length) {
            cvName.textContent = '✓ ' + cvInput.files[0].name;
            cvName.classList.add('show');
        } else {
            cvName.classList.remove('show');
        }
    });

    // Prevent double submits
    const form = document.querySelector('form');
    const submitBtn = document.getElementById('submitBtn');
    form.addEventListener('submit', () => {
        submitBtn.disabled = true;
        submitBtn.textContent = 'Submitting…';
    });
</script>
@endpush
