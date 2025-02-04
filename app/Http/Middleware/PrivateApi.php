<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PrivateApi
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->header('X-PAC') === config('services.private_api_key')) {
            return $next($request);
        }

        return response()->json(['error' => 'Պրիվատ կօդը չէս գրել արևս, Վահագն վիշապաքաղին ասա ասի․ '], 400);
    }
}
