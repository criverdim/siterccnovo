<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('auth/register', [\App\Http\Controllers\RegistrationController::class, 'register']);
    Route::post('auth/login', [\App\Http\Controllers\AuthController::class, 'loginApi']);

    Route::middleware(['auth', 'admin.access'])->group(function () {
        Route::prefix('admin')->group(function () {
            Route::get('users', [\App\Http\Controllers\AdminUserController::class, 'index']);
            Route::get('users/{id}', [\App\Http\Controllers\AdminUserController::class, 'show']);
            Route::put('users/{id}', [\App\Http\Controllers\AdminUserController::class, 'update']);
            Route::post('users/{id}/send-message', [\App\Http\Controllers\AdminUserController::class, 'sendMessage']);
            Route::post('users/{id}/upload-photo', [\App\Http\Controllers\AdminUserController::class, 'uploadPhoto']);
            Route::post('users/bulk-update-status', [\App\Http\Controllers\AdminUserController::class, 'bulkUpdateStatus']);
        });
    });
});
