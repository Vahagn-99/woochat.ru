<?php

use App\Http\Controllers\AmoCRM\AuthController;
use App\Http\Controllers\AmoCRM\WidgetDeleteController;
use App\Http\Controllers\AmoCRM\WidgetInstallController;
use App\Http\Controllers\AmoCRM\WidgetStatusController;
use Illuminate\Support\Facades\Route;

Route::middleware('json')->prefix('amocrm')->as('amocrm.')->group(function () {
    Route::post('widget/install', WidgetInstallController::class)->name('widget.install');
    Route::any('/widget/delete', WidgetDeleteController::class)->name('widget.delete');
    Route::get('widget/status', WidgetStatusController::class)->name('widget.status');
    Route::any('/auth', [AuthController::class, 'auth'])->name('auth');
    Route::any('/auth/callback', [AuthController::class, 'callback'])->name('auth.callback');
});

