<!DOCTYPE html>
<html>
<head><meta charset="utf-8"></head>
<body style="margin:0;padding:0;background:#f4f6fb;font-family:Arial,Helvetica,sans-serif;color:#1f2937;">
    <div style="max-width:600px;margin:0 auto;padding:24px;">
        <div style="background:#ffffff;padding:20px 26px;border-radius:12px 12px 0 0;border-bottom:3px solid #17b3d4;text-align:center;">
            <img src="https://apply.novastackhub.com/img/email-logo.png" alt="NovaStackHub" width="220" style="max-width:220px;height:auto;display:inline-block;">
        </div>

        <div style="background:#fff;padding:26px;border-radius:0 0 12px 12px;">
            <p style="font-size:16px;margin:0 0 14px;">Dear {{ $certificate->full_name }},</p>

            <p style="font-size:14px;line-height:1.7;margin:0 0 14px;">
                Congratulations on successfully completing your <strong>{{ $certificate->title }}</strong> at NovaStackHub.
                We appreciate the effort and consistency you showed throughout the programme.
            </p>

            <p style="font-size:14px;line-height:1.7;margin:0 0 18px;">
                Your certificate is attached to this email as a PDF.
            </p>

            <div style="background:#f4f6fb;border:1px solid #e5e8f0;border-radius:10px;padding:14px 16px;margin-bottom:18px;">
                <div style="font-size:13px;margin-bottom:6px;"><strong>Certificate ID:</strong> {{ $certificate->certificate_number }}</div>
                @if ($certificate->issue_date)
                    <div style="font-size:13px;"><strong>Issue date:</strong> {{ $certificate->issue_date->format('d M Y') }}</div>
                @endif
            </div>

            <p style="font-size:14px;line-height:1.7;margin:0 0 18px;">
                Anyone can verify this certificate here:<br>
                <a href="{{ $verifyUrl }}" style="color:#2563eb;">{{ $verifyUrl }}</a>
            </p>

            <p style="font-size:14px;line-height:1.7;margin:0 0 6px;">
                We wish you the very best for what comes next.
            </p>

            <p style="font-size:14px;line-height:1.7;margin:18px 0 0;">
                Warm regards,<br>
                <strong>Abdul Sammad Rehmani</strong><br>
                CEO, NovaStackHub
            </p>
        </div>

        <div style="text-align:center;font-size:11px;color:#8a93a6;padding:16px;">
            NovaStackHub &middot; Karachi, Pakistan &middot; novastackhub.com
        </div>
    </div>
</body>
</html>