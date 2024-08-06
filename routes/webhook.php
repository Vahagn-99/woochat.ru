<?php

use App\Http\Controllers\GreenApi\WebhookController as GreenApiWebhookController;
use App\Http\Controllers\AmoCrm\WebhookController as AmoCrmWebhookController;
use Illuminate\Support\Facades\Route;

Route::prefix('webhooks')->as('webhooks.')->group(function () {
    Route::any('greenapi', GreenApiWebhookController::class)->name('greenapi');
});
Route::any('amocrm/webhook/{scope_id}', AmoCrmWebhookController::class)->name('amocrm');
