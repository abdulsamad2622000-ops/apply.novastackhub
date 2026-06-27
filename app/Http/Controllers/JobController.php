<?php

namespace App\Http\Controllers;

use App\Models\Job;

class JobController extends Controller
{
    /**
     * List all open positions.
     */
    public function index()
    {
        $jobs = Job::active()->latest()->get();

        return view('careers.index', [
            'jobs' => $jobs,
        ]);
    }

    /**
     * Show a single open position with its application form.
     */
    public function show(Job $job)
    {
        abort_unless($job->is_active, 404);

        return view('careers.show', [
            'job' => $job,
        ]);
    }
}
