<?php

declare(strict_types=1);

namespace App\Services\AmoChat\Messaging\Adapters\Whatsapp;

use App\Base\Messaging\Adapter;
use App\Base\Messaging\IMessage;
use App\Services\Whatsapp\Messaging\Types\File;
use Illuminate\Support\Arr;

class FileAdapter implements Adapter
{
    public function adapt(array $data): IMessage
    {
        $chatId = Arr::get(Arr::get($data, 'conversation'), 'client_id') ?? Arr::get(Arr::get($data, 'receiver'), 'phone').'@c.us';
        $payload = $data['message'];

        return new File(
            chatId: $chatId,
            urlFile: $payload['media'],
            fileName: $payload['file_name'] ,
            caption: $payload['text'],
        );
    }
}
