<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Apply') · NovaStackHub</title>
    <meta name="description" content="Careers at NovaStackHub — explore open positions and apply online.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --ink:        #0C1C46;
            --ink-2:      #122A63;
            --violet:     #1D4ED8;
            --violet-600: #2563EB;
            --indigo:     #1E3A8A;
            --cyan:       #17B3D4;
            --cyan-light: #5FD3EC;
            --page:       #F4F6FA;
            --card:       #FFFFFF;
            --text:       #111827;
            --muted:      #6B7280;
            --line:       #E6E8EF;
            --line-2:     #EEF0F6;
            --danger:     #E11D48;
            --danger-bg:  #FEF2F4;
            --success:    #0F9D6B;
            --success-bg: #ECFAF3;
            --radius:     14px;
            --shadow:     0 1px 2px rgba(16,24,40,.04), 0 12px 32px -12px rgba(16,24,40,.18);
            --display: 'Poppins', ui-sans-serif, system-ui, sans-serif;
            --body: 'Inter', ui-sans-serif, system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
        }

        * { box-sizing: border-box; }

        html { -webkit-text-size-adjust: 100%; }

        body {
            margin: 0;
            font-family: var(--body);
            color: var(--text);
            background: var(--page);
            line-height: 1.55;
            -webkit-font-smoothing: antialiased;
        }

        a { color: var(--violet-600); }

        .wrap { width: 100%; max-width: 720px; margin: 0 auto; padding: 0 20px; }
        body.is-admin .wrap { max-width: none; padding: 0 32px; }

        /* ---------- Header ---------- */
        .masthead {
            position: relative;
            background: radial-gradient(120% 140% at 85% -20%, #2A56B0 0%, var(--ink-2) 40%, var(--ink) 100%);
            color: #EAEFFB;
            overflow: hidden;
        }
        .masthead::after {
            content: "";
            position: absolute; inset: 0;
            background-image:
                radial-gradient(1.5px 1.5px at 18% 30%, rgba(255,255,255,.5), transparent),
                radial-gradient(1.5px 1.5px at 67% 22%, rgba(255,255,255,.35), transparent),
                radial-gradient(1.5px 1.5px at 88% 64%, rgba(255,255,255,.4), transparent),
                radial-gradient(1.5px 1.5px at 42% 78%, rgba(255,255,255,.25), transparent);
            opacity: .7;
            pointer-events: none;
        }
        .masthead .wrap { position: relative; z-index: 1; padding-top: 22px; padding-bottom: 22px; }

        .topbar { display: flex; align-items: center; justify-content: space-between; gap: 16px; }

        .brand { display: inline-flex; align-items: center; gap: 11px; text-decoration: none; color: #fff; }
        .brand-mark { width: 30px; height: 30px; flex: none; }
        .brand-name { font-family: var(--display); font-weight: 700; font-size: 17px; letter-spacing: -.2px; color: #fff; }
        .brand-name b { color: var(--cyan-light); font-weight: 700; }

        .back-link {
            font-size: 13px; color: #BCC8E6; text-decoration: none;
            display: inline-flex; align-items: center; gap: 6px;
            padding: 7px 12px; border: 1px solid rgba(255,255,255,.14); border-radius: 999px;
            transition: border-color .15s ease, color .15s ease;
        }
        .back-link:hover { color: #fff; border-color: rgba(255,255,255,.4); }

        /* ---------- Content ---------- */
        main { padding: 34px 0 64px; }

        .eyebrow {
            font-family: var(--display);
            text-transform: uppercase; letter-spacing: .16em;
            font-size: 11.5px; font-weight: 600; color: var(--violet-600);
        }

        .card {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
        }
        .card-accent { height: 6px; background: linear-gradient(90deg, var(--violet-600) 0%, var(--cyan) 100%); }
        .card-body { padding: 30px 32px; }

        .lead-title {
            font-family: var(--display);
            font-size: clamp(22px, 4.4vw, 28px);
            font-weight: 700; letter-spacing: -.4px; line-height: 1.2;
            margin: 10px 0 8px;
        }
        .lead-sub { color: var(--muted); font-size: 15px; margin: 0; }

        .meta-row { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 16px; }
        .pill {
            font-size: 12.5px; font-weight: 500; color: #1E40AF;
            background: #E8F0FE; border: 1px solid #D5E2FC;
            padding: 5px 11px; border-radius: 999px;
        }

        .divider { height: 1px; background: var(--line-2); margin: 26px -32px; }

        /* ---------- Form ---------- */
        .field { margin-bottom: 22px; }
        .field:last-child { margin-bottom: 0; }

        label.q {
            display: block; font-weight: 600; font-size: 14.5px; color: var(--text);
            margin-bottom: 8px;
        }
        .req { color: var(--danger); margin-left: 2px; }
        .hint { display: block; font-weight: 400; color: var(--muted); font-size: 12.5px; margin-top: 2px; }

        input[type=text], input[type=email], input[type=url], input[type=tel], input[type=password], textarea {
            width: 100%;
            font: inherit; font-size: 15px; color: var(--text);
            background: #FBFBFE;
            border: 1px solid var(--line);
            border-radius: 10px;
            padding: 11px 13px;
            transition: border-color .15s ease, box-shadow .15s ease, background .15s ease;
        }
        input::placeholder, textarea::placeholder { color: #AAB0C0; }
        input:hover, textarea:hover { border-color: #CFD3E0; }
        input:focus, textarea:focus {
            outline: none; background: #fff;
            border-color: var(--violet-600);
            box-shadow: 0 0 0 3px rgba(37,99,235,.15);
        }
        textarea { resize: vertical; min-height: 96px; }

        .field.has-error input, .field.has-error textarea { border-color: var(--danger); background: var(--danger-bg); }
        .err { display: block; color: var(--danger); font-size: 12.5px; margin-top: 6px; font-weight: 500; }

        /* choice groups */
        .choices { display: flex; flex-direction: column; gap: 9px; }
        .choice {
            display: flex; align-items: center; gap: 11px;
            padding: 11px 13px;
            border: 1px solid var(--line); border-radius: 10px;
            cursor: pointer; background: #FBFBFE;
            transition: border-color .15s ease, background .15s ease;
            font-size: 14.5px;
        }
        .choice:hover { border-color: #CFD3E0; background: #fff; }
        .choice input { accent-color: var(--violet-600); width: 17px; height: 17px; flex: none; cursor: pointer; }
        .choice:has(input:checked) { border-color: var(--violet-600); background: #EFF4FE; }
        .choice:has(input:focus-visible) { box-shadow: 0 0 0 3px rgba(37,99,235,.15); }

        .choices-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 9px; }
        @media (max-width: 480px) { .choices-grid { grid-template-columns: 1fr; } }

        /* file upload */
        .file-drop {
            display: flex; align-items: center; gap: 14px;
            border: 1.5px dashed #C7CCDD; border-radius: 12px;
            padding: 16px; background: #FBFBFE; cursor: pointer;
            transition: border-color .15s ease, background .15s ease;
        }
        .file-drop:hover { border-color: var(--violet-600); background: #F8F6FE; }
        .file-drop input[type=file] { position: absolute; width: 1px; height: 1px; opacity: 0; }
        .file-ico {
            width: 42px; height: 42px; flex: none; border-radius: 10px;
            display: grid; place-items: center;
            background: linear-gradient(135deg, var(--violet) 0%, var(--indigo) 100%);
            color: #fff;
        }
        .file-text strong { display: block; font-size: 14.5px; }
        .file-text span { font-size: 12.5px; color: var(--muted); }
        .file-name { font-size: 13.5px; color: var(--success); font-weight: 600; margin-top: 8px; display: none; }
        .file-name.show { display: block; }

        /* buttons */
        .actions { display: flex; align-items: center; gap: 16px; margin-top: 28px; }
        .btn {
            font: inherit; font-weight: 600; font-size: 15px;
            border: none; cursor: pointer; border-radius: 10px;
            padding: 13px 26px; color: #fff;
            background: linear-gradient(135deg, var(--violet) 0%, var(--indigo) 100%);
            box-shadow: 0 8px 20px -8px rgba(29,78,216,.6);
            transition: transform .12s ease, box-shadow .12s ease, opacity .12s ease;
        }
        .btn:hover { transform: translateY(-1px); box-shadow: 0 12px 24px -8px rgba(29,78,216,.7); }
        .btn:active { transform: translateY(0); }
        .btn:disabled { opacity: .65; cursor: progress; transform: none; }
        .btn-ghost {
            background: none; color: var(--muted); box-shadow: none; padding: 13px 6px;
            text-decoration: none; font-weight: 500;
        }
        .btn-ghost:hover { color: var(--text); transform: none; box-shadow: none; }

        .note-warn {
            display: flex; gap: 10px; align-items: flex-start;
            background: var(--danger-bg); border: 1px solid #F7D4DC;
            color: #9F1239; border-radius: 10px; padding: 12px 14px;
            font-size: 13px; margin-bottom: 22px;
        }
        .note-warn svg { flex: none; margin-top: 1px; }

        /* footer */
        footer { padding: 26px 0 40px; text-align: center; color: var(--muted); font-size: 12.5px; }
        footer a { color: var(--muted); text-decoration: none; }
        footer a:hover { color: var(--text); }

        @media (prefers-reduced-motion: reduce) {
            * { transition: none !important; animation: none !important; }
        }

        @media (max-width: 560px) {
            .card-body { padding: 24px 20px; }
            .divider { margin: 22px -20px; }
            .actions { flex-direction: column-reverse; align-items: stretch; }
            .btn { width: 100%; }
            .btn-ghost { text-align: center; }
        }
    </style>
    @stack('head')
</head>
<body class="{{ request()->is('admin*') ? 'is-admin' : '' }}">
    <header class="masthead">
        <div class="wrap">
            <div class="topbar">
                <a class="brand" href="https://www.novastackhub.com/">
                    <svg class="brand-mark" viewBox="0 0 32 32" fill="none" aria-hidden="true">
                        <defs>
                            <linearGradient id="lm" x1="0" y1="0" x2="32" y2="32">
                                <stop offset="0" stop-color="#3CC9E8"/>
                                <stop offset="1" stop-color="#2563EB"/>
                            </linearGradient>
                        </defs>
                        <path d="M16 3l11 6-11 6L5 9l11-6z" fill="url(#lm)"/>
                        <path d="M5 15l11 6 11-6" stroke="#5FD3EC" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" opacity=".8"/>
                        <path d="M5 21l11 6 11-6" stroke="#60A5FA" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" opacity=".55"/>
                    </svg>
                    <span class="brand-name">Nova<b>Stack</b>Hub</span>
                </a>
                <a class="back-link" href="https://www.novastackhub.com/">
                    &larr; Back to site
                </a>
            </div>
        </div>
    </header>

    <main>
        <div class="wrap">
            @yield('content')
        </div>
    </main>

    <footer>
        <div class="wrap">
            &copy; {{ date('Y') }} NovaStackHub · Karachi, Pakistan ·
            <a href="https://www.novastackhub.com/">novastackhub.com</a>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
