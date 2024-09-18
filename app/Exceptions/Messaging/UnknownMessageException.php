<?php

namespace App\Exceptions\Messaging;

use App\Exceptions\ReportableException;
use Exception;

class UnknownMessageException extends Exception implements ReportableException
{
    private string $id;

    private string $provider;

    private array $payload;

    public function __construct(string $id, string $provider, array $payload = [])
    {
        $this->id = $id;
        $this->provider = $provider;
        $this->payload = $payload;

        parent::__construct("Собшение '{$id}' от '$provider' не найдено", 404);
    }

    public function report(): bool
    {
        do_log("messaging")->error("Собшение '{$this->id}' от '$this->provider' не найдено.", $this->payload);

        return false;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }
}
