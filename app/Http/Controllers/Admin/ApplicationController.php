<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ApplicationController extends Controller
{
    /**
     * List applications with filters (job, status, search).
     */
    public function index(Request $request)
    {
        $applications = $this->filtered($request)
            ->latest()
            ->paginate(25)
            ->withQueryString();

        return view('admin.applications.index', [
            'applications' => $applications,
            'search'       => trim((string) $request->query('q', '')),
            'jobs'         => Job::orderBy('title')->get(),
            'activeJob'    => $request->query('job') ? Job::find($request->query('job')) : null,
            'activeStatus' => $request->query('status'),
            'statuses'     => Application::STATUSES,
            'total'        => Application::count(),
            'allEmails'    => $this->filtered($request)->orderBy('id')->pluck('email')->unique()->values(),
        ]);
    }

    public function show(Application $application)
    {
        $application->load('job');

        return view('admin.applications.show', [
            'application' => $application,
        ]);
    }

    /**
     * Edit a single application (status, notes, and contact details).
     */
    public function edit(Application $application)
    {
        $application->load('job');

        return view('admin.applications.edit', [
            'application' => $application,
            'statuses'    => Application::STATUSES,
        ]);
    }

    public function update(Request $request, Application $application)
    {
        $data = $request->validate([
            'status'                 => ['required', 'in:'.implode(',', Application::STATUSES)],
            'full_name'              => ['required', 'string', 'max:255'],
            'email'                  => ['required', 'email', 'max:255'],
            'phone'                  => ['required', 'string', 'max:50'],
            'city'                   => ['nullable', 'string', 'max:120'],
            'education'              => ['nullable', 'string', 'max:160'],
            'skills'                 => ['nullable', 'string', 'max:2000'],
            'linkedin_url'           => ['nullable', 'url', 'max:255'],
            'portfolio_url'          => ['nullable', 'url', 'max:255'],
            'experience_years'       => ['nullable', 'string', 'max:50'],
            'experience_description' => ['nullable', 'string', 'max:5000'],
            'additional_info'        => ['nullable', 'string', 'max:5000'],
            'notes'                  => ['nullable', 'string', 'max:5000'],
        ]);

        $application->update($data);

        return redirect()
            ->route('admin.applications.show', $application)
            ->with('status', 'Application updated.');
    }

    /**
     * Bulk action on selected applications: set status or delete.
     */
    public function bulk(Request $request)
    {
        $data = $request->validate([
            'ids'    => ['required', 'array'],
            'ids.*'  => ['integer'],
            'action' => ['required', 'string'],
        ]);

        $query = Application::whereIn('id', $data['ids']);

        if ($data['action'] === 'delete') {
            // remove CV files too
            foreach ($query->get() as $app) {
                if ($app->cv_path) {
                    Storage::disk('public')->delete($app->cv_path);
                }
            }
            $count = $query->count();
            $query->delete();
            $msg = "{$count} application(s) deleted.";
        } elseif (in_array($data['action'], Application::STATUSES, true)) {
            $count = $query->update(['status' => $data['action']]);
            $msg = "{$count} application(s) marked as {$data['action']}.";
        } else {
            return back()->withErrors(['bulk' => 'Unknown bulk action.']);
        }

        return back()->with('status', $msg);
    }

    /**
     * Export the current (filtered) applications as a CSV file.
     */
    public function export(Request $request): StreamedResponse
    {
        $rows = $this->filtered($request)->with('job')->latest()->get();

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="applications-'.date('Y-m-d').'.csv"',
        ];

        return response()->stream(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, [
                'ID', 'Status', 'Applied For', 'Full Name', 'Email', 'Phone',
                'City', 'Education', 'Skills',
                'LinkedIn', 'Portfolio', 'Experience', 'Commission OK',
                'Outreach Platforms', 'Experience Details', 'Additional Info',
                'Notes', 'CV File', 'IP', 'Received',
            ]);

            foreach ($rows as $a) {
                fputcsv($out, [
                    $a->id,
                    $a->status,
                    $a->job?->title ?? '—',
                    $a->full_name,
                    $a->email,
                    $a->phone,
                    $a->city,
                    $a->education,
                    $a->skills,
                    $a->linkedin_url,
                    $a->portfolio_url,
                    $a->experience_years,
                    is_null($a->commission_only) ? '' : ($a->commission_only ? 'Yes' : 'No'),
                    is_array($a->outreach_platforms) ? implode(', ', $a->outreach_platforms) : '',
                    $a->experience_description,
                    $a->additional_info,
                    $a->notes,
                    $a->cv_original_name,
                    $a->ip_address,
                    $a->created_at?->format('Y-m-d H:i'),
                ]);
            }

            fclose($out);
        }, 200, $headers);
    }

    /**
     * Download an applicant's CV with its original filename.
     */
    public function downloadCv(Application $application)
    {
        abort_unless($application->cv_path && Storage::disk('public')->exists($application->cv_path), 404);

        return Storage::disk('public')->download(
            $application->cv_path,
            $application->cv_original_name
        );
    }

    /**
     * Shared filter builder for index + export.
     */
    protected function filtered(Request $request)
    {
        $search = trim((string) $request->query('q', ''));

        return Application::query()
            ->with('job')
            ->when($request->query('job'), fn ($q) => $q->where('job_id', $request->query('job')))
            ->when($request->query('status'), fn ($q) => $q->where('status', $request->query('status')))
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('full_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            });
    }
}
