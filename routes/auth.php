<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\VerifyResetCodeController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Register
    |--------------------------------------------------------------------------
    */

    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);


    /*
    |--------------------------------------------------------------------------
    | Login
    |--------------------------------------------------------------------------
    */

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);


    /*
    |--------------------------------------------------------------------------
    | Password Reset - OTP
    |--------------------------------------------------------------------------
    */

    // Page "Mot de passe oublié ?"
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    // Envoyer le code à 6 chiffres
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    // Page de vérification du code
    Route::get('verify-reset-code', [VerifyResetCodeController::class, 'create'])
        ->name('password.verify');

    // Vérifier le code
    Route::post('verify-reset-code', [VerifyResetCodeController::class, 'store'])
        ->name('password.verify.store');

    // Page nouveau mot de passe
    Route::get('reset-password', [NewPasswordController::class, 'create'])
        ->name('password.reset.form');

    // Enregistrer le nouveau mot de passe
    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});


/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Email Verification
    |--------------------------------------------------------------------------
    */

    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post(
        'email/verification-notification',
        [EmailVerificationNotificationController::class, 'store']
    )
        ->middleware('throttle:6,1')
        ->name('verification.send');


    /*
    |--------------------------------------------------------------------------
    | Confirm Password
    |--------------------------------------------------------------------------
    */

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);


    /*
    |--------------------------------------------------------------------------
    | Change Password when logged in
    |--------------------------------------------------------------------------
    */

    Route::put('password', [PasswordController::class, 'update'])
        ->name('password.update');


    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});