<?php

use App\Http\Controllers\AmoChat\WebhookController as AmoChatWebhookController;
use App\Http\Controllers\Whatsapp\WebhookController as WhatsappWebhookController;
use Illuminate\Support\Facades\Route;

Route::middleware(['subscription'])->group(function () {
    Route::any('webhooks/whatsapp', WhatsappWebhookController::class)->name('webhooks.whatsapp');
    Route::any('amocrm/webhook/{scope_id}', AmoChatWebhookController::class)->name('amocrm');
});
