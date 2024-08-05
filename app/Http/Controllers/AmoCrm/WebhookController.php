<?php

namespace App\Http\Controllers\AmoCrm;

use App\Events\AmoCrm\MessageReceived;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $payload = $request->all();

        logger()->info("Message from AmoCRM was received", $payload);

        event(new MessageReceived($payload));

        return response()->json();
    }
}
