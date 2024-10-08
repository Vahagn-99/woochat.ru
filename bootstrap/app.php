<?php

use App\Console\Scheduler;
use App\Http\Middleware\BasicAuthMiddleware;
use App\Http\Middleware\SubscriptionMiddleware;
use App\Services\AmoChat\Providers\AmoChatServiceProvider;
use App\Services\AmoCRM\Core\Providers\AmoCRMServiceProvider;
use App\Services\Whatsapp\Provider\WhatsappServiceProvider;
use Illuminate\Broadcasting\BroadcastServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))->withRouting(web: [
    __DIR__.'/../routes/web.php',
    __DIR__.'/../routes/webhook.php',
],
    api: [
        __DIR__.'/../routes/api.php',
        __DIR__.'/../routes/amocrm.php',
    ],
    commands: __DIR__.'/../routes/console.php',
    channels: __DIR__.'/../routes/channels.php',
    health: '/up',)->withMiddleware(function (
    Middleware $middleware
) {
    $middleware->api(prepend: [
        \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
    ]);
    $middleware->validateCsrfTokens(except: [
        'graphql',
        'webhooks/*',
        'amocrm/*',
        'broadcasting/auth',
    ]);

    $middleware->alias([
        'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
        'json' => \App\Http\Middleware\SetJsonHeaderInGreenApiWebhook::class,
    ]);
    //
})->withProviders([
    BroadcastServiceProvider::class,
    WhatsappServiceProvider::class,
    AmoChatServiceProvider::class,
    AmoCRMServiceProvider::class,
    Scheduler::class,
])->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'auth.basic' => BasicAuthMiddleware::class,
        'subscription' => SubscriptionMiddleware::class,
    ]);
})->withExceptions(function (Exceptions $exceptions) {
})->create();
