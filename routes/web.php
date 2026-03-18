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

Route::prefix('reports')->name('reports.')->group(function () {
    Route::get('/', [App\Http\Controllers\ReportController::class, 'index'])->name('index');

    // Excel
    Route::get('/tools/excel', [App\Http\Controllers\ReportController::class, 'exportToolsExcel'])->name('tools.excel');
    Route::get('/kits/excel', [App\Http\Controllers\ReportController::class, 'exportKitsExcel'])->name('kits.excel');
    Route::get('/loans/excel', [App\Http\Controllers\ReportController::class, 'exportLoansExcel'])->name('loans.excel');

    // PDF
    Route::get('/tools/pdf', [App\Http\Controllers\ReportController::class, 'exportToolsPdf'])->name('tools.pdf');
    Route::get('/kits/pdf', [App\Http\Controllers\ReportController::class, 'exportKitsPdf'])->name('kits.pdf');
    Route::get('/loans/pdf', [App\Http\Controllers\ReportController::class, 'exportLoansPdf'])->name('loans.pdf');
});

Route::get('/inventory/tools/pdf-codes', [App\Http\Controllers\ToolPdfController::class, 'exportCodes'])->name('inventory.tools.pdf-codes');

require __DIR__ . '/auth.php';