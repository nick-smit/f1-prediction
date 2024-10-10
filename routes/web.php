<?php

declare(strict_types=1);

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PredictionController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

require __DIR__.'/partial/admin.php';
require __DIR__.'/partial/auth.php';

Route::prefix('/prediction')->middleware('auth')->name('prediction.')->group(function (): void {
    Route::get('/', [PredictionController::class, 'index'])->name('index');
    Route::post('/{raceSession}', [PredictionController::class, 'store'])->name('store');
});
