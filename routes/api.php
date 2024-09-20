<?php

use App\Http\Controllers\Api\{
    GenerateAccessTokenController,
    SubscriptionController,
    UserWithSubscriptionController
};
use App\Http\Middleware\{
    PrivateApi,
    SignatureAmoCRM
};
use Illuminate\Support\Facades\Route;

Route::middleware(['api'])->group(function () {
    Route::middleware([SignatureAmoCRM::class])->group(function () {
        Route::get('user/{user}/generate-access-token', GenerateAccessTokenController::class);
    });

    Route::middleware([PrivateApi::class])->group(function () {
        Route::get('users/subscriptions', UserWithSubscriptionController::class);
    });
    Route::middleware([PrivateApi::class])->group(function () {
        Route::post('users/subscriptions', SubscriptionController::class);
    });
});
