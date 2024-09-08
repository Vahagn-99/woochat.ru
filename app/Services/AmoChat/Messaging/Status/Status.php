<?php

declare(strict_types=1);

namespace App\Services\AmoChat\Messaging\Status;

use App\Base\Messaging\IMessageStatus;

readonly class Status implements IMessageStatus
{
    public function __construct(
        private string $messageId,
        private string|int $status,
    ) {
    }

    public function toArray(): array
    {
        return [
            'msgid' => $this->messageId,
            'delivery_status' => $this->status,
        ];
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getId(): string
    {
        return $this->messageId;
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
