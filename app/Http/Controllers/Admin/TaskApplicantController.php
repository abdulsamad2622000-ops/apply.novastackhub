<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TaskApplicant;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TaskApplicantController extends Controller
{
    public function index()
    {
        $applicants = TaskApplicant::orderBy('full_name')->paginate(30);

        return view('admin.task-applicants.index', compact('applicants'));
    }

    public function importForm()
    {
        return view('admin.task-applicants.import');
    }

    public function importStore(Request $request)
    {
        $request->validate([
            'csv_file' => ['required', 'file', 'mimes:csv,txt'],
        ]);

        $path = $request->file('csv_file')->getRealPath();
        $handle = fopen($path, 'r');

        // Read header row
        $header = fgetcsv($handle);
        $header = array_map(fn ($h) => strtolower(trim($h)), $header);

        $map = [
            'timestamp' => null,
            'full name' => null,
            'phone number' => null,
            'email address' => null,
            'city' => null,
            'education / semester' => null,
            'skills' => null,
        ];

        foreach ($header as $index => $col) {
            foreach ($map as $key => $val) {
                if (str_contains($col, $key) || str_contains($key, $col)) {
                    $map[$key] = $index;
                }
            }
        }

        $imported = 0;
        $skipped = 0;

        while (($row = fgetcsv($handle)) !== false) {
            $fullName = $map['full name'] !== null ? trim($row[$map['full name']] ?? '') : null;
            $email = $map['email address'] !== null ? trim($row[$map['email address']] ?? '') : null;
            $phone = $map['phone number'] !== null ? trim($row[$map['phone number']] ?? '') : null;
            $city = $map['city'] !== null ? trim($row[$map['city']] ?? '') : null;
            $education = $map['education / semester'] !== null ? trim($row[$map['education / semester']] ?? '') : null;
            $skills = $map['skills'] !== null ? trim($row[$map['skills']] ?? '') : null;
            $timestampRaw = $map['timestamp'] !== null ? trim($row[$map['timestamp']] ?? '') : null;

            if (! $fullName || (! $email && ! $phone)) {
                $skipped++;
                continue;
            }

            $timestamp = null;
            if ($timestampRaw) {
                try {
                    $timestamp = Carbon::parse($timestampRaw);
                } catch (\Exception $e) {
                    $timestamp = null;
                }
            }

            TaskApplicant::updateOrCreate(
                $email ? ['email' => $email] : ['phone' => $phone],
                [
                    'full_name' => $fullName,
                    'email' => $email ?: null,
                    'phone' => $phone ?: null,
                    'city' => $city ?: null,
                    'education' => $education ?: null,
                    'skills' => $skills ?: null,
                    'form_submitted_at' => $timestamp,
                ]
            );

            $imported++;
        }

        fclose($handle);

        return redirect()->route('admin.task-applicants.index')
            ->with('status', "Import complete: {$imported} records imported/updated, {$skipped} skipped (missing name/email/phone).");
    }

    public function destroy(TaskApplicant $taskApplicant)
    {
        $taskApplicant->delete();

        return back()->with('status', 'Applicant deleted.');
    }
}