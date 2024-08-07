<?php

namespace App\Http\Controllers\Whatsapp;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class WebhookController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $webhookType = $request->input('typeWebhook');
        $payload = $request->all();

        logger()->info("$webhookType was received", $payload);

        $this->dispatchAppropriateEvent($webhookType, $payload);

        return response()->json();
    }

    private function dispatchAppropriateEvent(mixed $webhookType, array $payload): void
    {
        // Get all event files
        $events = File::allFiles(app_path('Events/Whatsapp/Webhooks'));
        foreach ($events as $event) {
            // Convert webhook type and event name to the same format
            $webhookTypeFormatted = $this->convertToTheSameFormat($webhookType);
            $eventNameFormatted = $this->convertToTheSameFormat($event->getBasename('.php'));

            if ($eventNameFormatted === $webhookTypeFormatted) {
                // Determine the full class name with namespace
                $eventClass = 'App\\Events\\Whatsapp\\Webhooks\\' . $event->getBasename('.php');

                // Check if the class exists before attempting to dispatch
                if (class_exists($eventClass)) {
                    event(new $eventClass($payload));
                } else {
                    logger()->error("Class $eventClass not found.");
                }
            }
        }
    }

    private function convertToTheSameFormat(string $text): string
    {
        return Str::snake(Str::lower($text));
    }
}
