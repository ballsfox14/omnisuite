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

// Rutas móviles
Route::middleware(['auth'])->prefix('mobile')->name('mobile.')->group(function () {
    Route::get('/kit/{code}', [App\Http\Controllers\MobileLoanController::class, 'showKit'])->name('kit.show');
    Route::post('/loan', [App\Http\Controllers\MobileLoanController::class, 'store'])->name('loan.store');
});

// Historial de actividades (logs)
Route::middleware(['auth', 'can:ver logs'])->prefix('logs')->name('logs.')->group(function () {
    Route::get('/', [App\Http\Controllers\ActivityLogController::class, 'index'])->name('index');
});

// Reportes
Route::middleware(['auth', 'can:ver reportes'])->prefix('reports')->name('reports.')->group(function () {
    Route::get('/', [App\Http\Controllers\ReportController::class, 'index'])->name('index');

    // Excel
    Route::get('/tools/excel', [App\Http\Controllers\ReportController::class, 'exportToolsExcel'])->name('tools.excel');
    Route::get('/kits/excel', [App\Http\Controllers\ReportController::class, 'exportKitsExcel'])->name('kits.excel');
    Route::get('/loans/excel', [App\Http\Controllers\ReportController::class, 'exportLoansExcel'])->name('loans.excel');

    // PDF
    Route::get('/tools/pdf', [App\Http\Controllers\ReportController::class, 'exportToolsPdf'])->name('tools.pdf');
    Route::get('/kits/pdf', [App\Http\Controllers\ReportController::class, 'exportKitsPdf'])->name('kits.pdf');
    Route::get('/loans/pdf', [App\Http\Controllers\ReportController::class, 'exportLoansPdf'])->name('loans.pdf');

    // Asistencia
    Route::get('/attendance', [App\Http\Controllers\ReportController::class, 'attendanceForm'])->name('attendance.form');
    Route::get('/attendance/excel', [App\Http\Controllers\ReportController::class, 'exportAttendanceExcel'])->name('attendance.excel');
    Route::get('/attendance/pdf', [App\Http\Controllers\ReportController::class, 'exportAttendancePdf'])->name('attendance.pdf');

    // Búsqueda AJAX
    Route::post('/attendance/search', [App\Http\Controllers\ReportController::class, 'searchAttendance'])->name('attendance.search');

    // Exportaciones con múltiples períodos
    Route::post('/attendance/multi-excel', [App\Http\Controllers\ReportController::class, 'exportAttendanceMultiPeriodExcel'])->name('attendance.multi.excel');
    Route::post('/attendance/multi-pdf', [App\Http\Controllers\ReportController::class, 'exportAttendanceMultiPeriodPdf'])->name('attendance.multi.pdf');
});

// PDF de códigos de herramientas
Route::get('/inventory/tools/pdf-codes', [App\Http\Controllers\ToolPdfController::class, 'exportCodes'])->name('inventory.tools.pdf-codes');

require __DIR__ . '/auth.php';