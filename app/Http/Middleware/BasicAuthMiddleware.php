<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BasicAuthMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $requestBasicAuth = response()->make('Invalid credentials.', 401, ['WWW-Authenticate' => 'Basic']);
        if (!($request->header('PHP_AUTH_USER') && $request->header('PHP_AUTH_PW'))) {
            return $requestBasicAuth;
        }

        $username = $request->header('PHP_AUTH_USER');
        $password = $request->header('PHP_AUTH_PW');

        if (!($username === config('auth.basic.username') && $password === config('auth.basic.password'))) {
            return $requestBasicAuth;
        }

        return $next($request);
    }
}
