<?php

use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMMissedTokenException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use App\Exceptions\AmoChat\AmoChatConnectionException;
use App\Exceptions\AmoChat\GivenScopeNotFoundException;
use App\Exceptions\AmoChat\UserNotFoundException;
use App\Exceptions\Whatsapp\InstanceCreationException;
use App\Exceptions\Whatsapp\UnsupportedWebhookType;
use App\Services\AmoChat\Providers\AmoChatServiceProvider;
use App\Services\AmoCRM\Core\Providers\AmoCRMServiceProvider;
use App\Services\Whatsapp\Provider\WhatsappServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))->withRouting(web: [
    __DIR__.'/../routes/web.php',
    __DIR__.'/../routes/webhook.php',
], api: [
    __DIR__.'/../routes/api.php',
    __DIR__.'/../routes/amocrm.php',
], commands: __DIR__.'/../routes/console.php', health: '/up',)->withMiddleware(function (Middleware $middleware) {
    $middleware->api(prepend: [
        \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
    ]);
    $middleware->validateCsrfTokens(except: [
        'graphql',
        'webhooks/*',
        'amocrm/*',
    ]);

    $middleware->alias([
        'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
        'json' => \App\Http\Middleware\SetJsonHeaderInGreenApiWebhook::class,
    ]);
    //
})->withProviders([
    WhatsappServiceProvider::class,
    AmoChatServiceProvider::class,
    AmoCRMServiceProvider::class,
])->withExceptions(function (Exceptions $exceptions) {
    $exceptions->render(function (InstanceCreationException $e) {
        return response()->json([
            'message' => $e->getMessage(),
        ], $e->getCode());
    });
    $exceptions->render(function (AmoChatConnectionException $e) {
        return response()->json([
            'message' => $e->getMessage(),
        ], $e->getCode());
    });

    $exceptions->render(function (UserNotFoundException $e) {
        return response()->json([
            'message' => $e->getMessage(),
        ], $e->getCode());
    });

    $exceptions->render(function (AmoCRMMissedTokenException|AmoCRMoAuthApiException|AmoCRMApiException $e) {
        return response()->json([
            'message' => $e->getMessage(),
        ], $e->getCode());
    });

    $exceptions->report(function (UnsupportedWebhookType $e) {

        do_log("whatsapp/webhooks")->warning($e->getMessage());

        return false;
    });

    $exceptions->report(function (GivenScopeNotFoundException $e) {

        do_log("amocrm/scopes")->warning($e->getMessage());

        return false;
    });

    $exceptions->render(function (GivenScopeNotFoundException $e) {
        return response()->json([
            'message' => $e->getMessage(),
        ], $e->getCode());
    });
})->create();
