<?php

use App\Http\Controllers\AmoChat\WebhookController as AmoCrmWebhookController;
use App\Http\Controllers\Whatsapp\WebhookController as WhatsappWebhookController;
use Illuminate\Support\Facades\Route;

Route::prefix('webhooks')->as('webhooks.')->group(function () {
    Route::any('whatsapp', WhatsappWebhookController::class)->name('whatsapp');
});
Route::any('amocrm/webhook/{scope_id}', AmoCrmWebhookController::class)->name('amocrm');
