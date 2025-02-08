<?php

namespace App\Http\Controllers\Whatsapp;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

class WebhookController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $webhookType = $request->input('typeWebhook');
        $payload = $request->all();

        // Проверка на дубликаты вебхуков
        $webhookKey = 'webhook_' . ($payload['idMessage'] ?? $payload['instanceData']['idInstance'] . '_' . $payload['timestamp']);

        if (Cache::has($webhookKey)) {
            do_log('webhooks/whatsapp')->info("Дубликат вебхука проигнорирован", [
                'type' => $webhookType,
                'key' => $webhookKey,
                'payload' => $payload
            ]);
            return response()->json(['status' => 'duplicate']);
        }

        Cache::put($webhookKey, true, now()->addHour());

        do_log('webhooks/whatsapp')->info("Вебхук по типу [$webhookType] был получен и передан на обработку.", [
            'type' => $webhookType,
            'key' => $webhookKey,
            'payload' => $payload
        ]);

        $this->callWebhookEventByType($webhookType, $payload);

        return response()->json(['status' => 'success']);
    }

    private function callWebhookEventByType(mixed $webhookType, array $payload): void
    {
        $webhookEvent = Arr::get(config('whatsapp.webhooks'), $webhookType);

        if (! $webhookEvent) {
            do_log('webhooks/whatsapp')->warning("Вебхук по типу [$webhookType] не поддерживается.", $payload);

            return;
        }

        event(new $webhookEvent($payload, 'whatsapp'));
    }
}
