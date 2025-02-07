<?php

declare(strict_types=1);

namespace App\Services\AmoChat\Messaging\Adapters\Whatsapp;

use App\Base\Messaging\Adapter;
use App\Base\Messaging\IMessage;
use App\Services\Whatsapp\Messaging\Types\Text;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class TextAdapter implements Adapter
{
    public function adapt(array $data): IMessage
    {
        $chatId = null;

        if (isset($data['conversation']['client_id'])) {
            $chatId = Str::replaceStart('+', '', $data['conversation']['client_id']);
        }
        elseif (isset($data['receiver']['phone'])) {
            $chatId = Str::replaceStart('8', '7', $data['receiver']['phone']);
            $chatId .= "@c.us";
        }

        $payload = $data['message'];

        return new Text(chatId: $chatId, message: $payload['text'] ?? $payload['textMessage']);
    }
}
