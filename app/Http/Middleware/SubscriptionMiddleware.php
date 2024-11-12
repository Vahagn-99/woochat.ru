<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SubscriptionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response|JsonResponse|RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        if(! $user && $request->has('account_id')) {
            $user = User::getByAmojoId($request->get('account_id'));
        }

        if (! $user || $user->activeSubscription) {
            return $next($request);
        }

        do_log('subscription')->warning("Пользавтель {$user->domain} пытался исползовать виджет без подписки.");

        return $request->expectsJson()
            ? response()->json('User Subscription required', 403)
            : redirect()->back();
    }
}
