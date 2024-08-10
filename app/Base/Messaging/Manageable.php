<?php

namespace App\Base\Messaging;

use App\Exceptions\Messaging\MessageDontHasTypeException;

trait Manageable
{
    public function getChatId(): string
    {
        return $this->chatId;
    }

    /**
     * @throws MessageDontHasTypeException
     */
    public function getType(): string
    {
        if (!self::TYPE) throw new MessageDontHasTypeException("The message type is not defined in [" . get_class($this) . ']');

        return self::TYPE;
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}