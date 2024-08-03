<?php

use App\Http\Controllers\GreenApi\WebhookController;
use Illuminate\Support\Facades\Route;

Route::prefix('webhooks')->as('webhooks.')->group(function () {
    Route::post('greenapi', WebhookController::class)->name('greenapi');
});