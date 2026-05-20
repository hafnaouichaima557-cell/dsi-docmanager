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

    // Profil — diag 4
    Route::get('/profile', [ProfileController::class, 'edit'])
         ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])
         ->name('profile.update');

    // Documents — diag 1, 2, 3, 5
    Route::resource('documents', DocumentController::class);
    Route::patch('documents/{document}/disable', [DocumentController::class, 'disable'])
         ->name('documents.disable');
    Route::patch('documents/{document}/publish', [DocumentController::class, 'publish'])
         ->name('documents.publish');

    // Workflow — diag 2, 5
    Route::post('documents/{document}/submit', [WorkflowController::class, 'submit'])
         ->name('workflow.submit');
    Route::post('workflow/steps/{step}/approve', [WorkflowController::class, 'approve'])
         ->name('workflow.approve');
    Route::post('workflow/steps/{step}/reject', [WorkflowController::class, 'reject'])
         ->name('workflow.reject');
    Route::post('documents/{document}/publish', [WorkflowController::class, 'publish'])
         ->name('workflow.publish');

    // Admin — diag 6, 7, 8, 9
    Route::middleware(['role:administrateur'])->prefix('admin')->group(function () {
        Route::resource('users', UserController::class);
        Route::patch('users/{user}/disable', [UserController::class, 'disable'])
             ->name('users.disable');
        Route::patch('users/{user}/role', [UserController::class, 'updateRole'])
             ->name('users.role');
        Route::get('audit', [AuditController::class, 'index'])
             ->name('audit.index');
    });
});

require __DIR__.'/auth.php';