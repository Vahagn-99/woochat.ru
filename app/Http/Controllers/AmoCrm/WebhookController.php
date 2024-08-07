<?php

namespace App\Http\Controllers\AmoCrm;

use App\Events\AmoCrm\MessageReceived;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function __invoke(Request $request, string $scopeId): JsonResponse
    {
        $payload = $request->all();
        $payload['scope_id'] = $scopeId;

        logger()->info("IMessage from AmoCRM was received", $payload);

        event(new MessageReceived($payload));

        return response()->json();
    }
}
