<?php

declare(strict_types=1);

namespace App\Services\Whatsapp\Messaging\Adapters\AmoChat;

class FileAdapter extends MediaAdapter
{
    protected function mediaType(): string
    {
        return 'file';
    }
}
