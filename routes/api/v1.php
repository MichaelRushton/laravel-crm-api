<?php

use App\Http\Controllers\V1\AuthController;
use App\Http\Controllers\V1\CustomerController;
use App\Http\Controllers\V1\ResetPasswordController;
use App\Http\Controllers\V1\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('throttle:auth.store')->group(function () {

    Route::post('/auth', [AuthController::class, 'store'])->name('auth.store');
    Route::post('/reset-password', [ResetPasswordController::class, 'store'])->name('reset-password.store');

});

Route::middleware('auth:sanctum')->group(function () {

    Route::controller(AuthController::class)->name('auth.')->group(function () {
        Route::get('/auth', 'show')->name('show');
        Route::delete('/auth', 'destroy')->name('destroy');
    });

    Route::apiResource('users', UserController::class);
    Route::apiResource('customers', CustomerController::class);

});
