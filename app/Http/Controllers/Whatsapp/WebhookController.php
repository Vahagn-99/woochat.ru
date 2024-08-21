<?php

namespace App\Http\Controllers\Whatsapp;

use App\Exceptions\Whatsapp\UnsupportedWebhookType;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class WebhookController extends Controller
{
    /**
     * @throws \App\Exceptions\Whatsapp\UnsupportedWebhookType
     */
    public function __invoke(Request $request): JsonResponse
    {
        $webhookType = $request->input('typeWebhook');
        $payload = $request->all();

        do_log('webhooks/whatsapp')->info("webhook with type [$webhookType] from Whatsapp was received", $payload);

        $this->callWebhookEventByType($webhookType, $payload);

        return response()->json();
    }

    /**
     * @throws \App\Exceptions\Whatsapp\UnsupportedWebhookType
     */
    private function callWebhookEventByType(mixed $webhookType, array $payload): void
    {
        $webhookEvent = Arr::get(config('whatsapp.webhooks'), $webhookType);

        if (! $webhookEvent) {
            throw UnsupportedWebhookType::type($webhookType);
        }

        event(new $webhookEvent($payload, 'whatsapp'));
    }
}
