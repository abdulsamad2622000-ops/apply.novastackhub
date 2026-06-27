<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreJobRequest;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class JobController extends Controller
{
    /**
     * List all jobs with their application counts.
     */
    public function index()
    {
        $jobs = Job::withCount('applications')->latest()->get();

        return view('admin.jobs.index', [
            'jobs'              => $jobs,
            'totalApplications' => $jobs->sum('applications_count'),
        ]);
    }

    public function create()
    {
        return view('admin.jobs.form', [
            'job' => new Job(['is_active' => true]),
        ]);
    }

    public function store(StoreJobRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = $this->resolveSlug($data['slug'] ?? null, $data['title']);

        Job::create($data);

        return redirect()
            ->route('admin.jobs.index')
            ->with('status', 'Job posted.');
    }

    public function edit(Job $job)
    {
        return view('admin.jobs.form', [
            'job' => $job,
        ]);
    }

    public function update(StoreJobRequest $request, Job $job)
    {
        $data = $request->validated();
        $data['slug'] = $this->resolveSlug($data['slug'] ?? null, $data['title'], $job->id);

        $job->update($data);

        return redirect()
            ->route('admin.jobs.index')
            ->with('status', 'Job updated.');
    }

    /**
     * Open / close a job without opening the edit form.
     */
    public function toggle(Job $job)
    {
        $job->update(['is_active' => ! $job->is_active]);

        return back()->with('status', $job->is_active ? 'Job is now open.' : 'Job closed.');
    }

    public function destroy(Job $job)
    {
        $job->delete();

        return redirect()
            ->route('admin.jobs.index')
            ->with('status', 'Job deleted.');
    }

    /**
     * Build a unique slug from the provided value or the title.
     */
    protected function resolveSlug(?string $slug, string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($slug ?: $title) ?: 'job';
        $slug = $base;
        $i = 2;

        while (Job::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
