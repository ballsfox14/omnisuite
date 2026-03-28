<?php

use Illuminate\Support\Facades\Route;
use Modules\Contracts\Http\Controllers\ContractController;
use Modules\Contracts\Http\Controllers\PackageController;
use Modules\Contracts\Http\Controllers\ZoneController;

// Rutas públicas (sin autenticación)
Route::prefix('public')->name('contracts.public.')->group(function () {
    Route::get('/sign/{token}', [ContractController::class, 'publicSignForm'])->name('sign');
    Route::post('/sign/{token}', [ContractController::class, 'publicSignStore'])->name('sign.store');
    Route::get('/signed/{token}', [ContractController::class, 'publicSigned'])->name('signed');
});

// Rutas internas (requieren autenticación)
Route::middleware(['web', 'auth'])->group(function () {
    // Contratos
    Route::prefix('contracts')->name('contracts.')->group(function () {
        Route::get('/', [ContractController::class, 'index'])->name('index');
        Route::get('/create', [ContractController::class, 'create'])->name('create');
        Route::post('/', [ContractController::class, 'store'])->name('store');
        Route::get('/{contract}', [ContractController::class, 'show'])->name('show');
        Route::get('/{contract}/edit', [ContractController::class, 'edit'])->name('edit');
        Route::put('/{contract}', [ContractController::class, 'update'])->name('update');
        Route::delete('/{contract}', [ContractController::class, 'destroy'])->name('destroy');
        Route::post('/{contract}/generate-link', [ContractController::class, 'generatePublicSigningLink'])->name('generate-link');
        Route::post('/get-price', [ContractController::class, 'getPrice'])->name('get-price');
    });

    // Paquetes
    Route::prefix('packages')->name('packages.')->group(function () {
        Route::get('/', [PackageController::class, 'index'])->name('index');
        Route::get('/create', [PackageController::class, 'create'])->name('create');
        Route::post('/', [PackageController::class, 'store'])->name('store');
        Route::get('/{package}', [PackageController::class, 'show'])->name('show');
        Route::get('/{package}/edit', [PackageController::class, 'edit'])->name('edit');
        Route::put('/{package}', [PackageController::class, 'update'])->name('update');
        Route::delete('/{package}', [PackageController::class, 'destroy'])->name('destroy');
    });

    // Zonas
    Route::prefix('zones')->name('zones.')->group(function () {
        Route::get('/', [ZoneController::class, 'index'])->name('index');
        Route::get('/create', [ZoneController::class, 'create'])->name('create');
        Route::post('/', [ZoneController::class, 'store'])->name('store');
        Route::get('/{zone}', [ZoneController::class, 'show'])->name('show');
        Route::get('/{zone}/edit', [ZoneController::class, 'edit'])->name('edit');
        Route::put('/{zone}', [ZoneController::class, 'update'])->name('update');
        Route::delete('/{zone}', [ZoneController::class, 'destroy'])->name('destroy');
    });
});