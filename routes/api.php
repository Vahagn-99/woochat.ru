<?php

use App\Http\Controllers\Api\GenerateAccessTokenController;
use App\Http\Middleware\AmoCRMAuthMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware(['api'])->group(function () {
    Route::middleware([AmoCRMAuthMiddleware::class])->group(function () {
        Route::get('user/{user}/generate-access-token', GenerateAccessTokenController::class);
    });
});
