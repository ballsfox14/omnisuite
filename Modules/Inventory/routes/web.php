<?php

use Illuminate\Support\Facades\Route;
use Modules\Inventory\Http\Controllers\ToolController;
use Modules\Inventory\Http\Controllers\KitController;
use Modules\Inventory\Http\Controllers\LoanController;

Route::middleware(['web', 'auth'])->prefix('inventory')->name('inventory.')->group(function () {
    // Tools
    Route::resource('tools', ToolController::class)->except(['show']);
    Route::get('tools/{tool}', [ToolController::class, 'show'])->name('tools.show');

    // Kits
    Route::resource('kits', KitController::class)->except(['show']);
    Route::get('kits/{kit}', [KitController::class, 'show'])->name('kits.show');

    Route::resource('loans', LoanController::class)->except(['show']);
    Route::get('loans/{loan}', [LoanController::class, 'show'])->name('loans.show');
    Route::resource('loans', LoanController::class)->except(['edit', 'update', 'destroy']);
    Route::post('loans/{loan}/return', [LoanController::class, 'return'])->name('loans.return');
});