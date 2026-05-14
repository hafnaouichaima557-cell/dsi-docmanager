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

    Route::get('/dashboard', [DashboardController::class, 'index'])
         ->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])
         ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])
         ->name('profile.update');

    Route::resource('documents', DocumentController::class);
    Route::patch('documents/{document}/disable', [DocumentController::class, 'disable'])
         ->name('documents.disable');
    Route::patch('documents/{document}/publish', [DocumentController::class, 'publish'])
         ->name('documents.publish');

    Route::post('documents/{document}/submit', [WorkflowController::class, 'submit'])
         ->name('workflow.submit');
    Route::post('workflow/steps/{step}/approve', [WorkflowController::class, 'approve'])
         ->name('workflow.approve');
    Route::post('workflow/steps/{step}/reject', [WorkflowController::class, 'reject'])
         ->name('workflow.reject');

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

require DIR.'/auth.php';

