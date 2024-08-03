<?php

use App\Http\Controllers\GreenApi\InstanceWebhookController;
use Illuminate\Support\Facades\Route;

Route::prefix('webhooks')->group(function () {
    Route::post('greenapi/instances', InstanceWebhookController::class);
});