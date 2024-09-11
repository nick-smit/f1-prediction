<?php

declare(strict_types=1);


use App\Http\Controllers\Admin\DriverManagerController;

\Illuminate\Support\Facades\Route::middleware(['auth', 'admin'])->name('admin.')->prefix('admin')->group(function (): void {
    \Illuminate\Support\Facades\Route::resource('drivers', DriverManagerController::class);
});
