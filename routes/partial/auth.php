<?php

declare(strict_types=1);


use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\RegisterController;

\Illuminate\Support\Facades\Route::middleware('guest')->group(function (): void {
    \Illuminate\Support\Facades\Route::get('/register', [RegisterController::class, 'create'])->name('register');
    \Illuminate\Support\Facades\Route::post('/register', [RegisterController::class, 'store']);

    \Illuminate\Support\Facades\Route::get('/login', [AuthenticationController::class, 'show'])->name('login');
    \Illuminate\Support\Facades\Route::post('/login', [AuthenticationController::class, 'login']);

    \Illuminate\Support\Facades\Route::get('/forgot-password', [ForgotPasswordController::class, 'requestNewPasswordForm'])->name('forgot-password');
    \Illuminate\Support\Facades\Route::post('/forgot-password', [ForgotPasswordController::class, 'resetLink']);

    \Illuminate\Support\Facades\Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'resetPasswordForm'])->name('password.reset');
    \Illuminate\Support\Facades\Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.store');
});

\Illuminate\Support\Facades\Route::middleware('auth')->group(static function (): void {
    \Illuminate\Support\Facades\Route::get('verify-email', [EmailVerificationController::class, 'show'])
        ->name('verification.show');
    \Illuminate\Support\Facades\Route::get('verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    \Illuminate\Support\Facades\Route::post('verify-email/verification-notification', [EmailVerificationController::class, 'send'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    \Illuminate\Support\Facades\Route::get('/logout', [AuthenticationController::class, 'logout'])->name('logout');
    \Illuminate\Support\Facades\Route::post('/logout', [AuthenticationController::class, 'logout'])->name('logout');
});
