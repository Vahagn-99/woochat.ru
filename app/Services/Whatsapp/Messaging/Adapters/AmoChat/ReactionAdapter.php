<?php

declare(strict_types=1);

namespace App\Services\Whatsapp\Messaging\Adapters\AmoChat;

use App\Base\Messaging\Adapter;
use App\Base\Messaging\IMessage;
use App\Services\AmoChat\Messaging\Types\Text;

class ReactionAdapter implements Adapter
{
    public function adapt(array $data): IMessage
    {
        $payload = $data['extendedTextMessageData'];

        return new Text(text: $payload['text']);
    }
}
