<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('auth/register', [\App\Http\Controllers\RegistrationController::class, 'register']);
    Route::post('auth/login', [\App\Http\Controllers\AuthController::class, 'loginApi']);
});

