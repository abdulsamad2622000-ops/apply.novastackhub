<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLogin(Request $request)
    {
        if ($request->session()->get('admin_authenticated')) {
            return redirect()->route('admin.applications.index');
        }

        return view('admin.login');
    }

    /**
     * Verify the admin password and start the session.
     */
    public function login(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string'],
        ]);

        if (hash_equals((string) config('recruitment.admin_password'), (string) $request->input('password'))) {
            $request->session()->regenerate();
            $request->session()->put('admin_authenticated', true);

            return redirect()->intended(route('admin.applications.index'));
        }

        return back()->withErrors(['password' => 'Incorrect password.']);
    }

    /**
     * End the admin session.
     */
    public function logout(Request $request)
    {
        $request->session()->forget('admin_authenticated');
        $request->session()->regenerate();

        return redirect()->route('admin.login');
    }

    /**
     * List all applications, newest first, with optional search.
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));

        $applications = Application::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where('full_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.index', [
            'applications' => $applications,
            'search'       => $search,
            'total'        => Application::count(),
        ]);
    }

    /**
     * Show a single application.
     */
    public function show(Application $application)
    {
        return view('admin.show', [
            'application' => $application,
        ]);
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
}
