<?php

use App\Http\Controllers\Whatsapp\WebhookController as WhatsappWebhookController;
use App\Http\Controllers\AmoCrm\WebhookController as AmoCrmWebhookController;
use Illuminate\Support\Facades\Route;

Route::prefix('webhooks')->as('webhooks.')->group(function () {
    Route::any('whatsapp', WhatsappWebhookController::class)->name('whatsapp');
});
Route::any('amocrm/webhook/{scope_id}', AmoCrmWebhookController::class)->name('amocrm');
