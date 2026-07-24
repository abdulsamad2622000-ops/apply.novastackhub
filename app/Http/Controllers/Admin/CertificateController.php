<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\TaskApplicant;
use App\Models\TaskSubmission;
use App\Services\CertificateImageService;
use App\Mail\CertificateIssued;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Builder\Builder;


class CertificateController extends Controller
{
    public function index(Request $request)
    {
        $certificates = Certificate::query()
            ->when($request->search, function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    $q->where('certificate_number', 'like', "%{$search}%")
                        ->orWhere('full_name', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.certificates.index', ['certificates' => $certificates]);
    }

    public function create()
    {
        return view('admin.certificates.create', [
            'suggested_number' => $this->generateNumber(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'certificate_number' => ['required', 'string', 'max:100', 'unique:certificates,certificate_number'],
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'application_id' => ['nullable', 'exists:applications,id'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
            'issue_date' => ['required', 'date'],
            'completion_date' => ['nullable', 'date'],
            'status' => ['required', 'in:valid,revoked'],
            'notes' => ['nullable', 'string'],
        ]);

        Certificate::create($validated);

        return redirect()->route('admin.certificates.index')->with('status', 'Certificate created ÃƒÂ¢Ã…â€œÃ¢â‚¬Â¦');
    }

    public function edit(Certificate $certificate)
    {
        return view('admin.certificates.edit', ['certificate' => $certificate]);
    }

    public function update(Request $request, Certificate $certificate)
    {
        $validated = $request->validate([
            'certificate_number' => ['required', 'string', 'max:100', 'unique:certificates,certificate_number,' . $certificate->id],
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'application_id' => ['nullable', 'exists:applications,id'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
            'issue_date' => ['required', 'date'],
            'completion_date' => ['nullable', 'date'],
            'status' => ['required', 'in:valid,revoked'],
            'notes' => ['nullable', 'string'],
        ]);

        $certificate->update($validated);

        return redirect()->route('admin.certificates.index')->with('status', 'Certificate updated ÃƒÂ¢Ã…â€œÃ¢â‚¬Â¦');
    }

    public function destroy(Certificate $certificate)
    {
        $certificate->delete();

        return redirect()->route('admin.certificates.index')->with('status', 'Certificate deleted ÃƒÂ¢Ã…â€œÃ¢â‚¬Â¦');
    }
public function qrCode(Certificate $certificate)
    {
        $url = route('verify.form', ['certificate_number' => $certificate->certificate_number]);

        $builder = new Builder();
        $result = $builder->build(
            data: $url,
            size: 400,
            margin: 10,
        );

        return response($result->getString(), 200, [
            'Content-Type' => 'image/png',
            'Content-Disposition' => 'inline; filename="qr-' . $certificate->certificate_number . '.png"',
        ]);
    }       public function download(Certificate $certificate, CertificateImageService $service)
    {
        $png = $service->png($certificate);

        return response($png, 200, [
            'Content-Type' => 'image/png',
            'Content-Disposition' => 'attachment; filename="certificate-'.$certificate->certificate_number.'.png"',
        ]);
    }

    public function view(Certificate $certificate, CertificateImageService $service)
    {
        return response($service->png($certificate), 200, [
            'Content-Type' => 'image/png',
            'Content-Disposition' => 'inline; filename="certificate-'.$certificate->certificate_number.'.png"',
        ]);
    }

    public function pdf(Certificate $certificate, CertificateImageService $service)
    {
        return response($service->pdf($certificate), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="certificate-'.$certificate->certificate_number.'.pdf"',
        ]);
    }

    public function sendEmail(Certificate $certificate)
    {
        $email = $this->resolveEmail($certificate);

        if (! $email) {
            return back()->with('status', 'No email found for '.$certificate->full_name.'. Add it via Edit first.');
        }

        try {
            Mail::to($email)->send(new CertificateIssued($certificate));
            $certificate->update(['emailed_at' => now()]);

            return back()->with('status', 'Certificate emailed to '.$email);
        } catch (\Throwable $e) {
            return back()->with('status', 'Failed to email '.$email.': '.$e->getMessage());
        }
    }

    public function sendAllEmails()
    {
        $certificates = Certificate::where('status', 'valid')->whereNull('emailed_at')->get();

        $sent = 0;
        $failed = 0;
        $noEmail = 0;

        foreach ($certificates as $certificate) {
            $email = $this->resolveEmail($certificate);

            if (! $email) {
                $noEmail++;
                continue;
            }

            try {
                Mail::to($email)->send(new CertificateIssued($certificate));
                $certificate->update(['emailed_at' => now()]);
                $sent++;
                usleep(400000);
            } catch (\Throwable $e) {
                $failed++;
            }
        }

        return back()->with('status', "Emails sent: {$sent}, failed: {$failed}, no address: {$noEmail}");
    }

    private function resolveEmail(Certificate $certificate): ?string
    {
        if ($certificate->email) {
            return $certificate->email;
        }

        $applicant = TaskApplicant::whereRaw('LOWER(full_name) = ?', [mb_strtolower(trim($certificate->full_name))])
            ->whereNotNull('email')
            ->first();

        if ($applicant) {
            $certificate->update(['email' => $applicant->email]);

            return $applicant->email;
        }

        return null;
    }

    public function issueApproved()
    {
        $applicantIds = TaskSubmission::where('status', 'approved')
            ->whereNotNull('task_applicant_id')
            ->distinct()
            ->pluck('task_applicant_id');

        $created = 0;
        $skipped = 0;

        foreach ($applicantIds as $id) {
            $applicant = TaskApplicant::find($id);
            if (! $applicant) {
                continue;
            }

            if (Certificate::where('full_name', $applicant->full_name)->exists()) {
                $skipped++;
                continue;
            }

            Certificate::create([
                'certificate_number' => $this->generateNumber(),
                'full_name'          => $applicant->full_name,
                'email'              => $applicant->email,
                'title'              => 'Web Development Internship',
                'application_id'     => null,
                'start_date'         => CertificateImageService::DEFAULT_START,
                'end_date'           => CertificateImageService::DEFAULT_END,
                'issue_date'         => now()->toDateString(),
                'completion_date'    => now()->toDateString(),
                'status'             => 'valid',
            ]);

            $created++;
        }

        return back()->with('status', "{$created} certificate(s) issued, {$skipped} already existed");
    }

    private function generateNumber(): string
    {
        do {
            $number = 'NSH-' . date('Y') . '-' . strtoupper(Str::random(6));
        } while (Certificate::where('certificate_number', $number)->exists());

        return $number;
    }
}