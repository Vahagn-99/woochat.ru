<?php

use App\Http\Controllers\AmoCrm\WidgetInstallController;
use App\Http\Controllers\AmoCrm\WidgetStatusController;
use Illuminate\Support\Facades\Route;

Route::prefix('amocrm')->as('amocrm.')->group(function () {
    Route::post('widget/install', WidgetInstallController::class);
    Route::get('widget/status', WidgetStatusController::class);
});
