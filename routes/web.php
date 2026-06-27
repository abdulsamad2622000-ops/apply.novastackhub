<?php

use App\Http\Controllers\Admin\ApplicationController as AdminApplicationController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\JobController as AdminJobController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\JobController;
use Illuminate\Support\Facades\Route;

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
    });
});
