<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TaskApplicant;
use Illuminate\Http\Request;

class TaskApplicantQuickAddController extends Controller
{
    public function form()
    {
        return view('admin.task-applicants.quick-add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
        ]);

        $name = trim($request->name ?? '');
        $email = trim($request->email ?? '');
        $phone = trim($request->phone ?? '');

        if (empty($email) && empty($phone)) {
            return back()->withInput()->with('status', 'Email ya Phone me se koi ek zaroor dein.');
        }

        $existing = null;
        if (! empty($email)) {
            $existing = TaskApplicant::where('email', $email)->first();
        }
        if (! $existing && ! empty($phone)) {
            $existing = TaskApplicant::where('phone', $phone)->first();
        }

        if ($existing) {
            return back()->with('status', 'Yeh applicant pehle se maujood hai (email/phone match hua).');
        }

        if (empty($name)) {
            $name = ! empty($email) ? explode('@', $email)[0] : $phone;
        }

        TaskApplicant::create([
            'full_name' => $name,
            'email' => $email ?: null,
            'phone' => $phone ?: null,
            'city' => null,
            'education' => null,
            'skills' => null,
            'form_submitted_at' => now(),
        ]);

        return back()->with('status', 'Applicant add ho gaya.');
    }
}