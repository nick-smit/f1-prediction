<?php

declare(strict_types=1);


use App\Http\Controllers\Admin\ContractManagementController;
use App\Http\Controllers\Admin\DriverManagerController;
use App\Http\Controllers\Admin\TeamManagerController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->name('admin.')->prefix('admin')->group(function (): void {
    Route::resource('contracts', ContractManagementController::class)->except(['index', 'show', 'destroy']);
    Route::resource('drivers', DriverManagerController::class)->except(['show', 'destroy']);
    Route::resource('teams', TeamManagerController::class)->except(['show', 'destroy']);
});
