<?php

use App\Http\Controllers\V1\ResetPasswordController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect('/up'))->name('login');

Route::middleware('guest')->group(function () {

    Route::controller(ResetPasswordController::class)->group(function () {
        Route::get('/reset-password/{token}', 'edit')->name('password.reset');
        Route::post('/reset-password', 'update')->name('password.update');
    });

});
