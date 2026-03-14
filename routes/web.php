<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Redirigir la raíz al login
Route::redirect('/', '/login');

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->prefix('mobile')->name('mobile.')->group(function () {
    Route::get('/kit/{code}', [App\Http\Controllers\MobileLoanController::class, 'showKit'])->name('kit.show');
    Route::post('/loan', [App\Http\Controllers\MobileLoanController::class, 'store'])->name('loan.store');
});

Route::middleware(['auth'])->prefix('logs')->name('logs.')->group(function () {
    Route::get('/', [App\Http\Controllers\ActivityLogController::class, 'index'])->name('index');
});

require __DIR__ . '/auth.php';