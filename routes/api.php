<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api'])->group(function () {
    Route::middleware(['guest'])->group(function () {
        Route::post('register', RegisterController::class);
        Route::post('login', LoginController::class);
    });
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('logout', LogoutController::class);
    });
});
