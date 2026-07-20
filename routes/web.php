<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WorkflowController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    // Documents
    Route::resource('documents', DocumentController::class);

    Route::patch('documents/{document}/disable', [DocumentController::class, 'disable'])
        ->name('documents.disable');

    Route::patch('documents/{document}/publish', [DocumentController::class, 'publish'])
        ->name('documents.publish');

    Route::get('documents/{document}/voir', [DocumentController::class, 'voir'])
        ->name('documents.voir');

    // Workflow
    Route::get('/workflow', [WorkflowController::class, 'index'])
        ->name('workflow.index');

    Route::post('documents/{document}/submit', [WorkflowController::class, 'submit'])
        ->name('workflow.submit');

    Route::post('workflow/steps/{step}/approve', [WorkflowController::class, 'approve'])
        ->name('workflow.approve');

    Route::post('workflow/steps/{step}/reject', [WorkflowController::class, 'reject'])
        ->name('workflow.reject');

    // Notifications
    Route::get('/notifications', function () {
        $notifications = auth()->user()->notifications()->paginate(20);
        auth()->user()->unreadNotifications->markAsRead();

        return view('notifications.index', compact('notifications'));
    })->name('notifications.index');

    Route::post('/notifications/mark-read', function () {
        auth()->user()->unreadNotifications->markAsRead();

        return back();
    })->name('notifications.mark-read');

    /*
    |--------------------------------------------------------------------------
    | Audit
    | Administrateur + Responsable
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:administrateur|responsable'])
        ->prefix('admin')
        ->group(function () {

            Route::get('/audit', [AuditController::class, 'index'])
                ->name('audit.index');
        });

    /*
    |--------------------------------------------------------------------------
    | Gestion des utilisateurs
    | Administrateur uniquement
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:administrateur'])
        ->prefix('admin')
        ->group(function () {

            Route::resource('users', UserController::class);

            Route::patch('users/{user}/disable', [UserController::class, 'disable'])
                ->name('users.disable');

            Route::patch('users/{user}/role', [UserController::class, 'updateRole'])
                ->name('users.role');
        });
});

require __DIR__.'/auth.php';