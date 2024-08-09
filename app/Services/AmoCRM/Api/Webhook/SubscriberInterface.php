<?php

namespace App\Services\AmoCRM\Api\Webhook;

interface SubscriberInterface
{
    public function subscribe(string $destination, array $actions): void;

    public function refresh(string $destination, array $actions): void;
}
