<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::middleware('guest')->group(function (): void {
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);

    Route::get('/login')->name('login');
});

Route::middleware('auth')->group(static function (): void {
    Route::get('verify-email', [EmailVerificationController::class, 'show'])
        ->name('verification.show');
    Route::get('verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('verify-email/verification-notification', [EmailVerificationController::class, 'send'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
});
