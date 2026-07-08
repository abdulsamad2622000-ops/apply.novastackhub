<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;

class QuickAddController extends Controller
{
    public function form()
    {
        return view('admin.applications.quick-add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'entries' => ['required', 'string'],
        ]);

        $lines = preg_split('/\r\n|\r|\n/', trim($request->entries));

        $added = 0;
        $skipped = 0;
        $errors = [];

        foreach ($lines as $lineNumber => $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }

            // Format: Name, Email  (comma separated)
            $parts = array_map('trim', explode(',', $line));

            if (count($parts) < 2 || empty($parts[0]) || empty($parts[1])) {
                $errors[] = 'Line ' . ($lineNumber + 1) . ': format sahi nahi ("Naam, email")';
                continue;
            }

            [$name, $email] = $parts;

            if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Line ' . ($lineNumber + 1) . ": invalid email ($email)";
                continue;
            }

            $existing = Application::where('email', $email)->first();

            if ($existing) {
                $skipped++;
                continue;
            }

            Application::create([
                'full_name' => $name,
                'email' => $email,
                'phone' => '',
                'linkedin_url' => null,
                'experience_years' => null,
                'experience_description' => null,
                'cv_path' => null,
                'cv_original_name' => null,
            ]);

            $added++;
        }

        return back()->with('status', "$added add hue, $skipped pehle se the (skip), " . count($errors) . ' errors.')
            ->with('quickAddErrors', $errors);
    }
}