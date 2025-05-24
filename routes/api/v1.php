<?php

use App\Http\Controllers\V1\AuthController;
use App\Http\Controllers\V1\CustomerController;
use App\Http\Controllers\V1\CustomerNoteController;
use App\Http\Controllers\V1\UserController;
use Illuminate\Support\Facades\Route;

Route::post('auth', [AuthController::class, 'store'])->name('auth.store')
    ->middleware('throttle:auth.store');

Route::middleware('auth:sanctum')->group(function () {

    Route::get('auth', [AuthController::class, 'show'])->name('auth.show');

    Route::apiResource('users', UserController::class);

    Route::apiResource('customers', CustomerController::class);
    Route::apiResource('customers.notes', CustomerNoteController::class);

    Route::delete('auth', [AuthController::class, 'destroy'])->name('auth.destroy');

});
