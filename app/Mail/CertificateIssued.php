<?php

namespace App\Mail;

use App\Models\Certificate;
use App\Services\CertificateImageService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CertificateIssued extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Certificate $certificate)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your NovaStackHub Internship Certificate',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.certificate',
            with: [
                'certificate' => $this->certificate,
                'verifyUrl'   => route('verify.form', ['certificate_number' => $this->certificate->certificate_number]),
            ],
        );
    }

    public function attachments(): array
    {
        $pdf = app(CertificateImageService::class)->pdf($this->certificate);

        return [
            Attachment::fromData(fn () => $pdf, 'certificate-'.$this->certificate->certificate_number.'.pdf')
                ->withMime('application/pdf'),
        ];
    }
}