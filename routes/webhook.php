<?php

use App\Http\Controllers\GreenApi\WebhookController as GreenApiWebhookController;
use App\Http\Controllers\AmoCrm\WebhookController as AmoCrmWebhookController;
use Illuminate\Support\Facades\Route;

Route::prefix('webhooks')->as('webhooks.')->group(function () {
    Route::post('greenapi', GreenApiWebhookController::class)->name('greenapi');
    Route::post('amocrm', AmoCrmWebhookController::class)->name('amocrm');
});