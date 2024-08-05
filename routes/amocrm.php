<?php

use App\Http\Controllers\AmoCrm\InstallController;
use App\Http\Controllers\AmoCrm\StatusController;
use Illuminate\Support\Facades\Route;

Route::prefix('amocrm')->as('amocrm.')->group(function () {
    Route::post('install', InstallController::class);
    Route::post('status', StatusController::class);
});
