<?php

namespace App\Http\Controllers\AmoChat;

use App\Events\Messaging\MessageReceived;
use App\Exceptions\AmoChat\GivenScopeNotFoundException;
use App\Http\Controllers\Controller;
use App\Models\AmoInstance;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    /**
     * @throws \App\Exceptions\AmoChat\GivenScopeNotFoundException
     */
    public function __invoke(Request $request, string $scopeId): JsonResponse
    {
        if (! AmoInstance::whereScopeId($scopeId)->exists()) {
            throw new GivenScopeNotFoundException("scopeId '{$scopeId}' не найдень", 404);
        }

        $payload = $request->all();
        $payload['scope_id'] = $scopeId;

        MessageReceived::dispatch($payload, 'amochat');

        do_log('webhooks/amochat')->info("Сообщение из amo было передано на обработку.", $payload);

        return response()->json();
    }
}
