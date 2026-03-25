<?php

use Illuminate\Support\Facades\Route;
use Modules\Attendance\Http\Controllers\AttendanceController;

// Rutas que requieren solo visualización (historial)
Route::middleware(['web', 'auth', 'can:ver asistencia'])->prefix('attendance')->name('attendance.')->group(function () {
    Route::get('/', [AttendanceController::class, 'index'])->name('index');
    Route::get('/admin', [AttendanceController::class, 'adminBalance'])->name('admin.balance');
});

// Rutas que requieren acción (marcar asistencia)
Route::middleware(['web', 'auth', 'can:marcar asistencia'])->prefix('attendance')->name('attendance.')->group(function () {
    Route::get('/marcar', [AttendanceController::class, 'markForm'])->name('mark.form');
    Route::post('/check-in', [AttendanceController::class, 'markCheckIn'])->name('mark.check-in');
    Route::post('/break-start', [AttendanceController::class, 'markBreakStart'])->name('mark.break-start');
    Route::post('/break-end', [AttendanceController::class, 'markBreakEnd'])->name('mark.break-end');
    Route::post('/check-out', [AttendanceController::class, 'markCheckOut'])->name('mark.check-out');
    Route::post('/close-week', [AttendanceController::class, 'closeWeek'])->name('mark.close-week');
    Route::post('/pause-start', [AttendanceController::class, 'markPauseStart'])->name('mark.pause-start');
    Route::post('/pause-end', [AttendanceController::class, 'markPauseEnd'])->name('mark.pause-end');
});