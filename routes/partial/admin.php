<?php

declare(strict_types=1);


use App\Http\Controllers\Admin\DriverManagerController;
use App\Http\Controllers\Admin\TeamManagerController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->name('admin.')->prefix('admin')->group(function (): void {
    Route::resource('drivers', DriverManagerController::class);
    Route::resource('teams', TeamManagerController::class);
});
