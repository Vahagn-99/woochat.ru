<?php

namespace App\Http\Controllers\AmoCrm;

use App\Events\AmoCrm\MessageReceived;
use App\Http\Controllers\Controller;
use App\Models\AmoConnection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function __invoke(Request $request, string $scopeId): JsonResponse
    {
        if (! AmoConnection::whereScopeId($scopeId)->exists()) {
            logger()->warning("The $scopeId is not a valid AMO connection");

            return response()->json([
                'The connection with the specified scopes does not exist.',
            ]);
        }

        $payload = $request->all();
        $payload['scope_id'] = $scopeId;

        logger()->info("IMessage from AmoCRM was received", $payload);

        event(new MessageReceived($payload));

        return response()->json();
    }
}
