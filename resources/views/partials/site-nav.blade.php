{{-- NovaStackHub main-site style TOPBAR + NAVBAR (self-contained, links to main website) --}}
@php $site = 'https://www.novastackhub.com'; @endphp

<div class="nsh-topbar">
    <div class="nsh-container nsh-topbar-inner">
        <div class="nsh-topbar-left">
            <a href="https://www.google.com/maps/search/?api=1&query=China+Shopping+Center+A.H.+Road+Saddar+Karachi" target="_blank" rel="noopener"><i class="bi bi-geo-alt"></i> China Shopping Center, A.H. Road, Saddar, Karachi</a>
            <a href="mailto:info@novastackhub.com"><i class="bi bi-envelope"></i> info@novastackhub.com</a>
            <a href="https://wa.me/923168738819" target="_blank" rel="noopener"><i class="bi bi-whatsapp"></i> +92 316 8738819</a>
        </div>
        <div class="nsh-topbar-right">
            <a href="{{ $site }}/help">Help</a>
            <a href="{{ $site }}/support">Support</a>
            <a href="{{ $site }}/faq">FAQs</a>
            <a href="https://www.facebook.com/share/1CzWv5wcNX/" target="_blank" rel="noopener" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
            <a href="https://www.instagram.com/novastackhub?igsh=YWd5bXF4cW44MHNn" target="_blank" rel="noopener" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
            <a href="https://www.linkedin.com/company/novastackhub/" target="_blank" rel="noopener" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
        </div>
    </div>
</div>

<nav class="nsh-nav">
    <div class="nsh-container nsh-nav-inner">
        <a class="nsh-brand" href="{{ $site }}/">
            <img src="{{ $site }}/img/logo.png" alt="NovaStackHub" class="nsh-logo">
            <span class="nsh-brand-textwrap">
                <span class="nsh-brand-name">NovaStackhub</span>
                <span class="nsh-brand-tagline">Empowering Digital Vision</span>
            </span>
        </a>

        <button class="nsh-toggler" type="button" aria-label="Toggle menu" onclick="document.getElementById('nshMenu').classList.toggle('open')">
            <span></span><span></span><span></span>
        </button>

        <div class="nsh-menu" id="nshMenu">
         <ul class="nsh-links">
                <li><a class="{{ request()->routeIs('careers.*') ? 'is-active' : '' }}" href="{{ route('careers.index') }}">Apply</a></li>
<li><a class="{{ request()->routeIs('tasks.*') ? 'is-active' : '' }}" href="{{ route('tasks.verify') }}">Submission</a></li>    
          <li><a class="{{ request()->routeIs('verify.*') ? 'is-active' : '' }}" href="{{ route('verify.form') }}">Verification</a></li>
            </ul>
            <a href="{{ $site }}/#contact" class="nsh-cta">CONTACT US &rarr;</a>
        </div>
    </div>
</nav>

<style>
    .nsh-container { width:100%; max-width:1200px; margin:0 auto; padding:0 20px; }
    .nsh-topbar { background:#12256b; color:rgba(255,255,255,.8); font-size:13px; }
    .nsh-topbar-inner { display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap; padding:8px 20px; }
    .nsh-topbar-left, .nsh-topbar-right { display:flex; align-items:center; gap:18px; flex-wrap:wrap; }
    .nsh-topbar a { color:rgba(255,255,255,.82); text-decoration:none; display:inline-flex; align-items:center; gap:5px; }
    .nsh-topbar a:hover { color:#fff; }
    .nsh-topbar-right a { gap:0; }
    .nsh-nav { background:#fff; border-bottom:1px solid #eef1f6; position:sticky; top:0; z-index:1000; box-shadow:0 2px 14px rgba(16,24,40,.05); font-family:'Plus Jakarta Sans','Inter',sans-serif; }
    .nsh-nav-inner { display:flex; align-items:center; justify-content:space-between; gap:16px; }
    .nsh-brand { display:flex; align-items:center; gap:10px; text-decoration:none; padding:8px 0; }
    .nsh-logo { height:54px; width:auto; object-fit:contain; }
    .nsh-brand-textwrap { display:flex; flex-direction:column; line-height:1.1; }
    .nsh-brand-name { font-weight:800; font-size:20px; color:#1e3a8a; letter-spacing:-.02em; }
    .nsh-brand-tagline { font-size:11px; color:#5a6b7e; font-weight:500; }
    .nsh-menu { display:flex; align-items:center; gap:18px; }
    .nsh-links { list-style:none; display:flex; align-items:center; gap:4px; margin:0; padding:0; }
    .nsh-links a { color:#16263a; font-size:14px; font-weight:600; text-decoration:none; padding:10px 12px; border-radius:8px; display:block; }
    .nsh-links a:hover { color:#2563eb; }
    .nsh-links a.is-active { color:#2563eb; }
    .nsh-cta { background:linear-gradient(135deg,#2563eb,#1e3a8a); color:#fff !important; font-weight:700; font-size:13.5px; text-decoration:none; padding:11px 22px; border-radius:8px; white-space:nowrap; box-shadow:0 8px 20px -8px rgba(37,99,235,.6); }
    .nsh-cta:hover { transform:translateY(-1px); }
    .nsh-toggler { display:none; flex-direction:column; gap:5px; background:none; border:1px solid #d6deea; border-radius:9px; padding:9px 10px; cursor:pointer; }
    .nsh-toggler span { width:22px; height:2px; background:#1e3a8a; border-radius:2px; }
    @media (max-width: 900px) {
        .nsh-topbar-left a:first-child { display:none; }
        .nsh-toggler { display:flex; }
        .nsh-menu { display:none; position:absolute; left:0; right:0; top:100%; background:#fff; flex-direction:column; align-items:stretch; gap:0; padding:10px 20px 18px; border-bottom:1px solid #eef1f6; box-shadow:0 12px 24px -8px rgba(16,24,40,.15); }
        .nsh-menu.open { display:flex; }
        .nsh-links { flex-direction:column; align-items:stretch; gap:0; }
        .nsh-links a { padding:13px 6px; border-bottom:1px solid #f0f3f8; }
        .nsh-cta { text-align:center; margin-top:12px; }
        .nsh-nav-inner { position:relative; }
    }
</style>