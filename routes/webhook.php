<?php

use App\Http\Controllers\GreenApi\InstanceWebhookController;
use Illuminate\Support\Facades\Route;

Route::prefix('webhooks')->as('webhooks.')->group(function () {
    Route::post('greenapi/instances', InstanceWebhookController::class)->name('greenapi.new-instance');
});