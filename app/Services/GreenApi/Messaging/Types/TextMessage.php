<?php

namespace App\Services\GreenApi\Messaging\Types;


use App\Services\GreenApi\Messaging\MessageInterface;
use Illuminate\Support\Arr;

class TextMessage implements MessageInterface
{
    use HasContent;

    public function __construct(
        private readonly string  $chatId,
        private readonly string  $message,
        private readonly ?string $quotedMessageId = null,
    )
    {
    }


    public function getChatId(): string
    {
        return $this->chatId;
    }

    public function getType(): string
    {
        return 'sendMessage';
    }
}