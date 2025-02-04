<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FullSubscriptionRequest;
use App\Services\Subscription\Full as SubscriptionFullService;
use Illuminate\Http\JsonResponse;

class SubscriptionController extends Controller
{
    public function __construct(
        private readonly SubscriptionFullService $subscription_full_service,
    ) {
    }

    public function __invoke(FullSubscriptionRequest $request): JsonResponse
    {
        $subscription = $this->subscription_full_service->subscribe($request->toDto());

        do_log("subscription")->info(
            "Подписка для {$subscription->domain} проделна до {$subscription->expired_at}"
        );

        return response()->json(['data' => $subscription->toArray()]);
    }
}
