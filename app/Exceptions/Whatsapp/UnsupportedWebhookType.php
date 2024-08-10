<?php

namespace App\Exceptions\Whatsapp;

use Exception;

class UnsupportedWebhookType extends Exception
{
    private string $webhookType;

    public static function type(mixed $webhookType): UnsupportedWebhookType
    {
        $instance = new self("The webhook type `{$webhookType}` is not supported.");
        $instance->webhookType = $webhookType;

        return $instance;
    }

    /**
     * Get the exception's context information.
     *
     * @return array<string, mixed>
     */
    public function context(): array
    {
        return ['webhookType' => $this->webhookType];
    }
}
