<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskApplicant;
use App\Models\TaskSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    // Step 1: show verify form
    public function verifyForm()
    {
        return view('tasks.verify');
    }

    // Step 2: check email OR phone belongs to a task applicant
    public function verifySubmit(Request $request)
    {
        $request->validate([
            'identifier' => ['required', 'string'],
        ]);

        $value = trim($request->input('identifier'));

        $applicant = TaskApplicant::where('email', $value)
            ->orWhere('phone', $value)
            ->first();

        if (! $applicant) {
            return back()
                ->withInput()
                ->withErrors(['identifier' => 'This email or phone was not found in our records. Please enter the correct email/phone you used when filling the form.']);
        }

        Session::put('task_applicant_id', $applicant->id);

        return redirect()->route('tasks.index');
    }

    public function logout()
    {
        Session::forget('task_applicant_id');
        return redirect()->route('tasks.verify');
    }

    // Step 3: show all active tasks + this applicant's submissions
    public function index()
    {
        $applicantId = Session::get('task_applicant_id');

        if (! $applicantId) {
            return redirect()->route('tasks.verify');
        }

        $application = TaskApplicant::find($applicantId);

        if (! $application) {
            Session::forget('task_applicant_id');
            return redirect()->route('tasks.verify');
        }

        $tasks = Task::active()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $submissions = TaskSubmission::where('task_applicant_id', $applicantId)
            ->get()
            ->keyBy('task_id');

        return view('tasks.index', [
            'application' => $application,
            'tasks' => $tasks,
            'submissions' => $submissions,
        ]);
    }

    // Step 4: handle submit / re-submit for a specific task
    public function submit(Request $request, Task $task)
    {
        $applicantId = Session::get('task_applicant_id');

        if (! $applicantId) {
            return redirect()->route('tasks.verify');
        }

        $validator = Validator::make($request->all(), [
            'link' => ['nullable', 'url', 'max:2048'],
            'github_link' => ['nullable', 'url', 'max:2048'],
            'live_demo_url' => ['nullable', 'url', 'max:2048'],
            'tech_stack' => ['nullable', 'string', 'max:500'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'file' => ['nullable', 'file', 'max:20480', 'mimes:pdf,doc,docx,zip,rar,png,jpg,jpeg'],
            'linkedin_post_link' => ['required', 'url', 'max:2048'],
            'linkedin_screenshot' => ['required', 'image', 'max:10240'],
            'confirmed_own_work' => ['required', 'accepted'],
        ], [
            'linkedin_post_link.required' => 'LinkedIn post ka link zaroori hai.',
            'linkedin_screenshot.required' => 'LinkedIn screenshot upload karna zaroori hai.',
            'confirmed_own_work.required' => 'Aapko confirm karna hoga ke yeh aapka apna kaam hai.',
            'confirmed_own_work.accepted' => 'Aapko confirm karna hoga ke yeh aapka apna kaam hai.',
        ]);

        $validator->after(function ($validator) use ($request) {
            if (! $request->filled('github_link') && ! $request->filled('link') && ! $request->hasFile('file')) {
                $validator->errors()->add('proof', 'GitHub link, doosra link, ya file me se koi ek zaroor dein.');
            }
        });

        $validated = $validator->validate();

        $submission = TaskSubmission::firstOrNew([
            'task_id' => $task->id,
            'task_applicant_id' => $applicantId,
        ]);

        $submission->notes = $validated['notes'] ?? null;
        $submission->tech_stack = $validated['tech_stack'] ?? null;
        $submission->linkedin_post_link = $validated['linkedin_post_link'];
        $submission->confirmed_own_work = true;

        $submission->link = $request->filled('link') ? $validated['link'] : null;
        $submission->github_link = $request->filled('github_link') ? $validated['github_link'] : null;
        $submission->live_demo_url = $request->filled('live_demo_url') ? $validated['live_demo_url'] : null;

        if ($request->hasFile('file')) {
            if ($submission->file_path) {
                Storage::disk('public')->delete($submission->file_path);
            }

            $file = $request->file('file');
            $path = $file->store('task-submissions', 'public');

            $submission->file_path = $path;
            $submission->file_original_name = $file->getClientOriginalName();
        }

        if ($request->hasFile('linkedin_screenshot')) {
            if ($submission->linkedin_screenshot_path) {
                Storage::disk('public')->delete($submission->linkedin_screenshot_path);
            }

            $screenshot = $request->file('linkedin_screenshot');
            $screenshotPath = $screenshot->store('linkedin-screenshots', 'public');

            $submission->linkedin_screenshot_path = $screenshotPath;
            $submission->linkedin_screenshot_original_name = $screenshot->getClientOriginalName();
        }

        // Re-submission goes back to pending for admin review
        $submission->status = TaskSubmission::STATUS_PENDING;
        $submission->admin_feedback = null;

        $submission->save();

        return back()->with('status', 'Task "' . $task->title . '" submit ho gaya! ✅');
    }
}