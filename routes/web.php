<?php

use App\Http\Controllers\Admin\ApplicationController as AdminApplicationController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\JobController as AdminJobController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\JobController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\Admin\TaskController as AdminTaskController;
use App\Http\Controllers\VerifyController;
use App\Http\Controllers\Admin\CertificateController;
use App\Http\Controllers\Admin\QuickAddController;
use App\Http\Controllers\Admin\TaskApplicantQuickAddController;
/*
|--------------------------------------------------------------------------
| Public careers / job portal
|--------------------------------------------------------------------------
*/
Route::get('/', [JobController::class, 'index'])->name('careers.index');
Route::get('/jobs/{job}', [JobController::class, 'show'])->name('careers.show');
Route::post('/jobs/{job}/apply', [ApplicationController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('careers.apply');
Route::get('/jobs/{job}/thank-you', [ApplicationController::class, 'success'])->name('careers.success');

/*
|--------------------------------------------------------------------------
| Admin dashboard
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('admin.login');
    Route::post('login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('admin.login.attempt');
    Route::post('logout', [AuthController::class, 'logout'])->name('admin.logout');

    Route::middleware('admin')->group(function () {
        // Jobs management
        Route::get('/', [AdminJobController::class, 'index'])->name('admin.jobs.index');
        Route::get('jobs/create', [AdminJobController::class, 'create'])->name('admin.jobs.create');
        Route::post('jobs', [AdminJobController::class, 'store'])->name('admin.jobs.store');
        Route::get('jobs/{job}/edit', [AdminJobController::class, 'edit'])->name('admin.jobs.edit');
        Route::put('jobs/{job}', [AdminJobController::class, 'update'])->name('admin.jobs.update');
        Route::patch('jobs/{job}/toggle', [AdminJobController::class, 'toggle'])->name('admin.jobs.toggle');
        Route::delete('jobs/{job}', [AdminJobController::class, 'destroy'])->name('admin.jobs.destroy');

        // Applications
        Route::get('applications', [AdminApplicationController::class, 'index'])->name('admin.applications.index');
        Route::get('applications/export', [AdminApplicationController::class, 'export'])->name('admin.applications.export');
        Route::post('applications/bulk', [AdminApplicationController::class, 'bulk'])->name('admin.applications.bulk');
        Route::get('applications/{application}', [AdminApplicationController::class, 'show'])->name('admin.applications.show');
        Route::get('applications/{application}/edit', [AdminApplicationController::class, 'edit'])->name('admin.applications.edit');
        Route::put('applications/{application}', [AdminApplicationController::class, 'update'])->name('admin.applications.update');
        Route::get('applications/{application}/cv', [AdminApplicationController::class, 'downloadCv'])->name('admin.applications.cv');
        Route::get('applications-quick-add', [QuickAddController::class, 'form'])->name('admin.applications.quickadd');
        Route::post('applications-quick-add', [QuickAddController::class, 'store'])->name('admin.applications.quickadd.store');
    });
});

// ---- Student Task Submission ----
Route::get('/submit-task', [TaskController::class, 'verifyForm'])->name('tasks.verify');
Route::post('/submit-task', [TaskController::class, 'verifySubmit'])->name('tasks.verify.submit');
Route::get('/submit-task/logout', [TaskController::class, 'logout'])->name('tasks.logout');
Route::get('/submit-task/tasks', [TaskController::class, 'index'])->name('tasks.index');
Route::post('/submit-task/tasks/{task}', [TaskController::class, 'submit'])->name('tasks.submit');

// ---- Admin Tasks (CRUD + submissions) + Certificates ----
Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/tasks', [AdminTaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/create', [AdminTaskController::class, 'create'])->name('tasks.create');
    Route::post('/tasks', [AdminTaskController::class, 'store'])->name('tasks.store');
    Route::get('/tasks/{task}/edit', [AdminTaskController::class, 'edit'])->name('tasks.edit');
    Route::put('/tasks/{task}', [AdminTaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [AdminTaskController::class, 'destroy'])->name('tasks.destroy');
    Route::get('/tasks/{task}/submissions', [AdminTaskController::class, 'submissions'])->name('tasks.submissions');
    Route::patch('/task-submissions/{submission}/status', [AdminTaskController::class, 'updateSubmissionStatus'])->name('tasks.submissions.updateStatus');



// Task Applicants (imported from Google Form)
    Route::get('/task-applicants', [\App\Http\Controllers\Admin\TaskApplicantController::class, 'index'])->name('task-applicants.index');
    Route::get('/task-applicants/import', [\App\Http\Controllers\Admin\TaskApplicantController::class, 'importForm'])->name('task-applicants.import');
    Route::post('/task-applicants/import', [\App\Http\Controllers\Admin\TaskApplicantController::class, 'importStore'])->name('task-applicants.import.store');
    Route::delete('/task-applicants/{taskApplicant}', [\App\Http\Controllers\Admin\TaskApplicantController::class, 'destroy'])->name('task-applicants.destroy');
Route::get('/task-applicants-quick-add', [TaskApplicantQuickAddController::class, 'form'])->name('taskApplicants.quickadd');
    Route::post('/task-applicants-quick-add', [TaskApplicantQuickAddController::class, 'store'])->name('taskApplicants.quickadd.store');    // Certificates
    Route::get('/certificates', [CertificateController::class, 'index'])->name('certificates.index');








    // Certificates
    Route::get('/certificates', [CertificateController::class, 'index'])->name('certificates.index');
    Route::get('/certificates/create', [CertificateController::class, 'create'])->name('certificates.create');
    Route::post('/certificates/issue-approved', [CertificateController::class, 'issueApproved'])->name('certificates.issueApproved');
    Route::get('/certificates/{certificate}/download', [CertificateController::class, 'download'])->name('certificates.download');
    Route::get('/certificates/{certificate}/view', [CertificateController::class, 'view'])->name('certificates.view');
    Route::get('/certificates/{certificate}/pdf', [CertificateController::class, 'pdf'])->name('certificates.pdf');
    Route::post('/certificates', [CertificateController::class, 'store'])->name('certificates.store');
    Route::get('/certificates/{certificate}/edit', [CertificateController::class, 'edit'])->name('certificates.edit');
    Route::put('/certificates/{certificate}', [CertificateController::class, 'update'])->name('certificates.update');
   
    Route::delete('/certificates/{certificate}', [CertificateController::class, 'destroy'])->name('certificates.destroy');
Route::get('/certificates/{certificate}/qr', [CertificateController::class, 'qrCode'])->name('certificates.qr');
    });


// ---- Certificate Verification (public) ----
Route::get('/verify', [VerifyController::class, 'form'])->name('verify.form');
Route::post('/verify', [VerifyController::class, 'check'])->name('verify.check');