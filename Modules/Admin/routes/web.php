<?php

use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Controllers\UserController;
use Modules\Admin\Http\Controllers\RoleController;
use Modules\Admin\Http\Controllers\PermissionController;
use Modules\Admin\Http\Controllers\AreaController;

Route::middleware(['web', 'auth'])->prefix('admin')->name('admin.')->group(function () {
    // Usuarios
    Route::resource('users', UserController::class)->except(['show']);
    Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');

    // Roles
    Route::resource('roles', RoleController::class)->except(['show']);
    Route::get('roles/{role}', [RoleController::class, 'show'])->name('roles.show');

    // Permisos
    Route::resource('permissions', PermissionController::class)->except(['show']);
    Route::get('permissions/{permission}', [PermissionController::class, 'show'])->name('permissions.show');

    Route::resource('areas', AreaController::class)->except(['show']);
});