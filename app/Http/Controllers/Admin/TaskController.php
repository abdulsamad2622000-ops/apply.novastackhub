<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskSubmission;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function allSubmissions(Request $request)
    {
        $activeTaskId = $request->query('task_id');

        $submissions = TaskSubmission::with(['task', 'applicant'])
            ->when($activeTaskId, fn ($q) => $q->where('task_id', $activeTaskId))
            ->latest()
            ->get();

        $tasks = Task::orderBy('sort_order')->orderBy('id')->get();

        return view('admin.tasks.all-submissions', [
            'submissions'  => $submissions,
            'tasks'        => $tasks,
            'activeTaskId' => $activeTaskId,
        ]);
    }

    public function index()
    {
        $tasks = Task::withCount('submissions')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('admin.tasks.index', ['tasks' => $tasks]);
    }

    public function create()
    {
        return view('admin.tasks.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'due_date' => ['nullable', 'date'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        Task::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'due_date' => $validated['due_date'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.tasks.index')->with('status', 'Task ban gaya Ã¢Å“â€¦');
    }

    public function edit(Task $task)
    {
        return view('admin.tasks.edit', ['task' => $task]);
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'due_date' => ['nullable', 'date'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $task->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'due_date' => $validated['due_date'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.tasks.index')->with('status', 'Task update ho gaya Ã¢Å“â€¦');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('admin.tasks.index')->with('status', 'Task delete ho gaya Ã¢Å“â€¦');
    }

    public function submissions(Task $task)
    {
        $submissions = $task->submissions()
            ->with('applicant')
            ->latest()
            ->get();

        return view('admin.tasks.submissions', ['task' => $task, 'submissions' => $submissions]);
    }

    public function updateSubmissionStatus(Request $request, TaskSubmission $submission)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,approved,needs_revision'],
            'admin_feedback' => ['nullable', 'string', 'max:2000'],
        ]);

        $submission->update([
            'status' => $validated['status'],
            'admin_feedback' => $validated['admin_feedback'] ?? null,
        ]);

        return back()->with('status', 'Submission status update ho gaya Ã¢Å“â€¦');
    }
}