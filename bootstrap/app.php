<?php

use App\Exceptions\InstanceCreationException;
use App\Services\GreenApi\Provider\GreenApiServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: [
            __DIR__ . '/../routes/web.php',
            __DIR__ . '/../routes/webhook.php'
        ],
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);
        $middleware->validateCsrfTokens(except: [
            'graphql'
        ]);

        $middleware->alias([
            'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
        ]);

        //
    })
    ->withProviders([
        GreenApiServiceProvider::class
    ])
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (InstanceCreationException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], $e->getCode());
        });
    })
    ->create();
