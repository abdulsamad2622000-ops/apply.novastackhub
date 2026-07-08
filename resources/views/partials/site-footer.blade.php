{{-- Simple bottom bar --}}
@php $site = 'https://www.novastackhub.com'; @endphp

<footer class="nsh-footer">
    <div class="nsh-container nsh-foot-bottom">
        <p>&copy; {{ date('Y') }} <strong>NovaStackHub</strong> — All Rights Reserved.</p>
        <div class="nsh-foot-right">
            <div class="nsh-foot-socials">
                <a href="https://www.facebook.com/share/1CzWv5wcNX/" target="_blank" rel="noopener" aria-label="Facebook"><svg viewBox="0 0 24 24" fill="currentColor" width="16" height="16"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg></a>
                <a href="https://www.instagram.com/novastackhub?igsh=YWd5bXF4cW44MHNn" target="_blank" rel="noopener" aria-label="Instagram"><svg viewBox="0 0 24 24" fill="currentColor" width="16" height="16"><path d="M12 2.16c3.2 0 3.58.01 4.85.07 1.17.05 1.8.25 2.23.41.56.22.96.48 1.38.9.42.42.68.82.9 1.38.16.42.36 1.06.41 2.23.06 1.27.07 1.65.07 4.85s-.01 3.58-.07 4.85c-.05 1.17-.25 1.8-.41 2.23-.22.56-.48.96-.9 1.38-.42.42-.82.68-1.38.9-.42.16-1.06.36-2.23.41-1.27.06-1.65.07-4.85.07s-3.58-.01-4.85-.07c-1.17-.05-1.8-.25-2.23-.41-.56-.22-.96-.48-1.38-.9-.42-.42-.68-.82-.9-1.38-.16-.42-.36-1.06-.41-2.23C2.17 15.58 2.16 15.2 2.16 12s.01-3.58.07-4.85c.05-1.17.25-1.8.41-2.23.22-.56.48-.96.9-1.38.42-.42.82-.68 1.38-.9.42-.16 1.06-.36 2.23-.41C8.42 2.17 8.8 2.16 12 2.16zm0 3.68A6.16 6.16 0 1 0 18.16 12 6.16 6.16 0 0 0 12 5.84zm0 10.16A4 4 0 1 1 16 12a4 4 0 0 1-4 4zm6.4-10.4a1.44 1.44 0 1 0 1.44 1.44 1.44 1.44 0 0 0-1.44-1.44z"/></svg></a>
                <a href="https://www.linkedin.com/company/novastackhub/" target="_blank" rel="noopener" aria-label="LinkedIn"><svg viewBox="0 0 24 24" fill="currentColor" width="16" height="16"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg></a>
            </div>
        </div>
    </div>
</footer>

<style>
    .nsh-footer { background:#12256b; color:#c3d0e8; font-family:'Plus Jakarta Sans','Inter',sans-serif; margin-top:40px; }
    .nsh-container { width:100%; max-width:1200px; margin:0 auto; padding:0 20px; }
    .nsh-foot-bottom { display:flex; align-items:center; justify-content:space-between; gap:14px; flex-wrap:wrap; padding:18px 20px; font-size:13.5px; }
    .nsh-foot-bottom p { margin:0; }
    .nsh-foot-bottom strong { color:#fff; }
    .nsh-foot-socials { display:flex; gap:10px; }
    .nsh-foot-socials a { width:34px; height:34px; border-radius:8px; display:grid; place-items:center; background:rgba(255,255,255,.08); color:#cfe0f5; border:1px solid rgba(255,255,255,.12); }
    .nsh-foot-socials a:hover { background:#2563eb; color:#fff; border-color:#2563eb; }
    @media (max-width:520px){ .nsh-foot-bottom { flex-direction:column; align-items:center; text-align:center; } }
</style>