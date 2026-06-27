<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreApplicationRequest;
use App\Models\Application;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ApplicationController extends Controller
{
    /**
     * Store an application submitted for a specific job.
     */
    public function store(StoreApplicationRequest $request, Job $job)
    {
        abort_unless($job->is_active, 404);

        $data = $request->validated();

        // CV is required for professional roles, optional for internships.
        $path = null;
        $originalName = null;
        if ($request->hasFile('cv')) {
            $file = $request->file('cv');
            $originalName = $file->getClientOriginalName();
            $safeName = Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) ?: 'cv';
            $filename = $safeName.'-'.Str::random(8).'.'.$file->getClientOriginalExtension();
            $path = $file->storeAs('cvs', $filename, 'public');
        }

        $isInternship = $job->form_type === 'internship';

        $application = $job->applications()->create([
            'full_name'              => $data['full_name'],
            'email'                  => $data['email'],
            'phone'                  => $data['phone'],
            'city'                   => $isInternship ? ($data['city'] ?? null) : null,
            'education'              => $isInternship ? ($data['education'] ?? null) : null,
            'skills'                 => $isInternship ? ($data['skills'] ?? null) : null,
            'linkedin_url'           => $isInternship ? null : ($data['linkedin_url'] ?? null),
            'portfolio_url'          => $isInternship ? null : ($data['portfolio_url'] ?? null),
            'experience_years'       => $isInternship ? null : ($data['experience_years'] ?? null),
            'experience_description' => $isInternship ? null : ($data['experience_description'] ?? null),
            'commission_only'        => (! $isInternship && $job->ask_commission_question)
                ? (($data['commission_only'] ?? null) === 'Yes')
                : null,
            'outreach_platforms'     => (! $isInternship && $job->ask_outreach_question)
                ? ($data['outreach_platforms'] ?? [])
                : null,
            'cv_path'                => $path,
            'cv_original_name'       => $originalName,
            'additional_info'        => $data['additional_info'] ?? null,
            'ip_address'             => $request->ip(),
        ]);

        $this->notifyTeam($application, $job);

        return redirect()
            ->route('careers.success', $job)
            ->with('applicant', $application->full_name);
    }

    /**
     * Thank-you page after a successful submission.
     */
    public function success(Request $request, Job $job)
    {
        if (! session()->has('applicant')) {
            return redirect()->route('careers.show', $job);
        }

        return view('careers.success', [
            'job'       => $job,
            'applicant' => session('applicant'),
        ]);
    }

    /**
     * Email a short notification to the recruitment inbox.
     * Failures are logged but never block the applicant.
     */
    protected function notifyTeam(Application $application, Job $job): void
    {
        $to = config('recruitment.notify_email');

        if (empty($to)) {
            return;
        }

        try {
            $body = "New application received for: {$job->title}\n\n"
                ."Name:        {$application->full_name}\n"
                ."Email:       {$application->email}\n"
                ."Phone:       {$application->phone}\n"
                ."LinkedIn:    {$application->linkedin_url}\n"
                ."Experience:  {$application->experience_years}\n\n"
                ."Review it in the dashboard: ".route('admin.applications.index', ['job' => $job->id]);

            Mail::raw($body, function ($message) use ($to, $job) {
                $message->to($to)->subject("New application — {$job->title}");
            });
        } catch (\Throwable $e) {
            Log::warning('Application notification email failed: '.$e->getMessage());
        }
    }
}
