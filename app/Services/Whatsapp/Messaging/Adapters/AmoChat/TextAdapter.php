<?php

declare(strict_types=1);

namespace App\Services\Whatsapp\Messaging\Adapters\AmoChat;

use App\Base\Messaging\Adapter;
use App\Base\Messaging\IMessage;
use App\Services\AmoChat\Messaging\Types\Text;

class TextAdapter implements Adapter
{
    public function adapt(array $data): IMessage
    {
        // Обработка обычного текстового сообщения
        if (isset($data['textMessageData'])) {
            return new Text(text: $data['textMessageData']['textMessage']);
        }

        // Обработка расширенного текстового сообщения
        if (isset($data['extendedTextMessageData'])) {
            return new Text(text: $data['extendedTextMessageData']['text']);
        }

        // Если сообщение пришло напрямую в messageData
        if (isset($data['text'])) {
            return new Text(text: $data['text']);
        }

        // Логируем неизвестный формат сообщения
        do_log("messaging/error")->warning("Неизвестный формат текстового сообщения", [
            'payload' => $data
        ]);

        // Возвращаем пустое сообщение если формат неизвестен
        return new Text(text: '');
    }
}
