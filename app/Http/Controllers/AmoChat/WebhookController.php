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
            throw new GivenScopeNotFoundException("Scope '{$scopeId}' not found", 404);
        }

        $payload = $request->all();
        $payload['scope_id'] = $scopeId;

        do_log('amochat/webhooks')->info("message from AmoCRM was received", $payload);

        MessageReceived::dispatch($payload, 'amochat');

        return response()->json();
    }
}