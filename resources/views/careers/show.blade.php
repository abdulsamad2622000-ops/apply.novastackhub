@extends('layouts.app')

@section('title', $job->title)

@push('head')
<style>
    .back { display:inline-flex; align-items:center; gap:6px; font-size:13.5px; color:var(--muted); text-decoration:none; margin-bottom:14px; }
    .back:hover { color:var(--text); }
    .job-hero h1 { font-family:var(--display); font-size:clamp(23px,4.6vw,30px); font-weight:700; letter-spacing:-.4px; margin:8px 0 10px; }
    .job-tags { display:flex; flex-wrap:wrap; gap:7px; }
    .jtag { font-size:12px; font-weight:500; padding:4px 11px; border-radius:999px; }
    .jtag-dept { background:#E8F0FE; color:#1E40AF; border:1px solid #D5E2FC; }
    .jtag-loc  { background:#EEF1F6; color:#475569; border:1px solid #E2E6EE; }
    .jtag-type { background:#E6F7FB; color:#0E7C97; border:1px solid #CDEEF5; }

    .jd { font-size:15px; color:#374151; line-height:1.65; }
    .jd .jd-h { font-family:var(--display); font-size:15.5px; font-weight:600; color:var(--text); margin:18px 0 6px; }
    .jd p { margin:0 0 12px; }
    .jd ul { margin:0 0 12px; padding-left:20px; }
    .jd li { margin:3px 0; }
    .jd a { color:var(--violet-600); text-decoration:underline; word-break:break-word; }
</style>
@endpush

@section('content')
    <a class="back" href="{{ route('careers.index') }}">&larr; All positions</a>

    {{-- Job description --}}
    <div class="card">
        <div class="card-accent"></div>
        <div class="card-body">
            <div class="job-hero">
                <p class="eyebrow">We're hiring</p>
                <h1>{{ $job->title }}</h1>
                <div class="job-tags">
                    @if ($job->department)<span class="jtag jtag-dept">{{ $job->department }}</span>@endif
                    <span class="jtag jtag-type">{{ $job->type }}</span>
                    <span class="jtag jtag-loc">{{ $job->location }}</span>
                </div>
            </div>

            <div class="divider"></div>

            <div class="jd">
                @php
                    $fmt = function ($text) {
                        $safe = e(trim($text));
                        $safe = preg_replace(
                            '~(https?://[^\s<]+)~',
                            '<a href="$1" target="_blank" rel="noopener">$1</a>',
                            $safe
                        );
                        return nl2br($safe);
                    };
                    $blocks = preg_split('/\n\s*\n/', trim($job->description));
                @endphp
                @foreach ($blocks as $block)
                    @php $lines = preg_split('/\n/', trim($block)); @endphp
                    @php $bulletLines = array_filter($lines, fn ($l) => str_starts_with(trim($l), '- ')); @endphp
                    @if (count($bulletLines) === count($lines))
                        <ul>
                            @foreach ($lines as $l)
                                <li>{!! $fmt(ltrim(trim($l), '- ')) !!}</li>
                            @endforeach
                        </ul>
                    @elseif (count($lines) === 1 && mb_strlen($lines[0]) < 42 && ! str_ends_with(trim($lines[0]), '.'))
                        <p class="jd-h">{{ trim($lines[0]) }}</p>
                    @else
                        <p>{!! $fmt($block) !!}</p>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    {{-- Application form --}}
    <div class="card" style="margin-top:18px;" id="apply">
        <div class="card-body">
            <p class="eyebrow">Apply now</p>
            <h2 class="lead-title" style="font-size:21px;margin-top:8px;">Submit your application</h2>
            <p class="lead-sub">Fill in the details below. Fields marked <span class="req">*</span> are required.</p>

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

            <form method="POST" action="{{ route('careers.apply', $job) }}" enctype="multipart/form-data" novalidate>
                @csrf

                <div style="position:absolute;left:-9999px;" aria-hidden="true">
                    <label>Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
                </div>

                <div class="field @error('full_name') has-error @enderror">
                    <label class="q" for="full_name">Full name <span class="req">*</span></label>
                    <input type="text" id="full_name" name="full_name" value="{{ old('full_name') }}" placeholder="e.g. Ayesha Khan" required>
                    @error('full_name')<span class="err">{{ $message }}</span>@enderror
                </div>

                <div class="field @error('email') has-error @enderror">
                    <label class="q" for="email">Email address <span class="req">*</span></label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required>
                    @error('email')<span class="err">{{ $message }}</span>@enderror
                </div>

                <div class="field @error('phone') has-error @enderror">
                    <label class="q" for="phone">Phone / WhatsApp number <span class="req">*</span></label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" placeholder="+92 3XX XXXXXXX" required>
                    @error('phone')<span class="err">{{ $message }}</span>@enderror
                </div>

                @if ($job->form_type === 'internship')
                    {{-- ===== Internship (student) form ===== --}}
                    <div class="field @error('city') has-error @enderror">
                        <label class="q" for="city">City <span class="req">*</span></label>
                        <input type="text" id="city" name="city" value="{{ old('city') }}" placeholder="e.g. Karachi" required>
                        @error('city')<span class="err">{{ $message }}</span>@enderror
                    </div>

                    <div class="field @error('education') has-error @enderror">
                        <label class="q" for="education">Education / Semester <span class="req">*</span></label>
                        <input type="text" id="education" name="education" value="{{ old('education') }}" placeholder="e.g. BSCS — 5th semester" required>
                        @error('education')<span class="err">{{ $message }}</span>@enderror
                    </div>

                    <div class="field @error('skills') has-error @enderror">
                        <label class="q" for="skills">Skills <span class="req">*</span></label>
                        <textarea id="skills" name="skills" rows="3" placeholder="e.g. HTML, CSS, JavaScript, Bootstrap…" required>{{ old('skills') }}</textarea>
                        @error('skills')<span class="err">{{ $message }}</span>@enderror
                    </div>

                    <div class="field @error('cv') has-error @enderror">
                        <label class="q" for="cv">Upload your CV / resume <span class="hint">Optional</span></label>
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
                            <input type="file" id="cv" name="cv" accept=".pdf,.doc,.docx">
                        </label>
                        <span class="file-name" id="cv-name"></span>
                        @error('cv')<span class="err">{{ $message }}</span>@enderror
                    </div>
                @else
                <div class="field @error('linkedin_url') has-error @enderror">
                    <label class="q" for="linkedin_url">LinkedIn profile link <span class="req">*</span></label>
                    <input type="url" id="linkedin_url" name="linkedin_url" value="{{ old('linkedin_url') }}" placeholder="https://linkedin.com/in/your-handle" required>
                    @error('linkedin_url')<span class="err">{{ $message }}</span>@enderror
                </div>

                <div class="field @error('portfolio_url') has-error @enderror">
                    <label class="q" for="portfolio_url">
                        Portfolio / Upwork / GitHub link
                        <span class="hint">Optional</span>
                    </label>
                    <input type="url" id="portfolio_url" name="portfolio_url" value="{{ old('portfolio_url') }}" placeholder="https://...">
                    @error('portfolio_url')<span class="err">{{ $message }}</span>@enderror
                </div>

                @php $years = ['Fresher', '0–1 year', '1–3 years', '3+ years']; @endphp
                <div class="field @error('experience_years') has-error @enderror">
                    <label class="q">Years of relevant experience <span class="req">*</span></label>
                    <div class="choices">
                        @foreach ($years as $option)
                            <label class="choice">
                                <input type="radio" name="experience_years" value="{{ $option }}" @checked(old('experience_years') === $option) required>
                                <span>{{ $option }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('experience_years')<span class="err">{{ $message }}</span>@enderror
                </div>

                <div class="field @error('experience_description') has-error @enderror">
                    <label class="q" for="experience_description">Briefly describe your relevant experience for this role <span class="req">*</span></label>
                    <textarea id="experience_description" name="experience_description" rows="4" placeholder="Tools you use, what you've worked on, results you've driven…" required>{{ old('experience_description') }}</textarea>
                    @error('experience_description')<span class="err">{{ $message }}</span>@enderror
                </div>

                @if ($job->ask_commission_question)
                    <div class="field @error('commission_only') has-error @enderror">
                        <label class="q">Have you previously worked on a commission-only basis? <span class="req">*</span></label>
                        <div class="choices">
                            @foreach (['Yes', 'No'] as $option)
                                <label class="choice">
                                    <input type="radio" name="commission_only" value="{{ $option }}" @checked(old('commission_only') === $option) required>
                                    <span>{{ $option }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('commission_only')<span class="err">{{ $message }}</span>@enderror
                    </div>
                @endif

                @if ($job->ask_outreach_question)
                    @php $platforms = ['LinkedIn', 'Upwork', 'Fiverr', 'Cold Email', 'Other']; $selectedPlatforms = old('outreach_platforms', []); @endphp
                    <div class="field @error('outreach_platforms') has-error @enderror">
                        <label class="q">Which platforms have you used for outreach?</label>
                        <div class="choices choices-grid">
                            @foreach ($platforms as $platform)
                                <label class="choice">
                                    <input type="checkbox" name="outreach_platforms[]" value="{{ $platform }}" @checked(in_array($platform, $selectedPlatforms))>
                                    <span>{{ $platform }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('outreach_platforms')<span class="err">{{ $message }}</span>@enderror
                    </div>
                @endif

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
                @endif

                <div class="field @error('additional_info') has-error @enderror">
                    <label class="q" for="additional_info">Anything else you'd like to share? <span class="hint">Optional</span></label>
                    <textarea id="additional_info" name="additional_info" rows="3" placeholder="Availability, expectations, links to past work…">{{ old('additional_info') }}</textarea>
                    @error('additional_info')<span class="err">{{ $message }}</span>@enderror
                </div>

                <div class="actions">
                    <button type="submit" class="btn" id="submitBtn">Submit application</button>
                    <a href="{{ route('careers.show', $job) }}" class="btn-ghost">Reset</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
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

    const form = document.querySelector('form');
    const submitBtn = document.getElementById('submitBtn');
    form.addEventListener('submit', () => {
        submitBtn.disabled = true;
        submitBtn.textContent = 'Submitting…';
    });
</script>
@endpush
