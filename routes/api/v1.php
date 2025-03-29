<?php

use App\Http\Controllers\V1\AuthController;
use App\Http\Controllers\V1\CustomerController;
use App\Http\Controllers\V1\CustomerNoteController;
use App\Http\Controllers\V1\EnquiryController;
use App\Http\Controllers\V1\ProductCategoryController;
use App\Http\Controllers\V1\ProductController;
use App\Http\Controllers\V1\UserController;
use Illuminate\Support\Facades\Route;

Route::post('auth', [AuthController::class, 'store'])->name('auth.store');

Route::middleware('auth:sanctum')->group(function () {

    Route::get('auth', [AuthController::class, 'show'])->name('auth.show');

    Route::apiResource('users', UserController::class);

    Route::apiResource('products', ProductController::class);

    Route::apiResource('product-categories', ProductCategoryController::class);

    Route::apiResource('customers', CustomerController::class);
    Route::get('/customers/{customer}/enquiries', [CustomerController::class, 'enquiries'])
        ->whereNumber('customer')->name('customers.enquiries');
    Route::apiResource('customers.enquiries', EnquiryController::class)
        ->except('index');
    Route::apiResource('customers.notes', CustomerNoteController::class);

    Route::apiResource('enquiries', EnquiryController::class)
        ->only('index');

    Route::delete('auth', [AuthController::class, 'destroy'])->name('auth.destroy');

});
