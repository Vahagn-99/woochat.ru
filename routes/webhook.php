<?php

use App\Http\Controllers\GreenApi\WebhookController;
use App\Http\Middleware\SetJsonHeaderInGreenApiWebhook;
use Illuminate\Support\Facades\Route;

Route::prefix('webhooks')->as('webhooks.')->group(function () {
    Route::middleware(SetJsonHeaderInGreenApiWebhook::class)->post('greenapi', WebhookController::class)->name('greenapi');
});