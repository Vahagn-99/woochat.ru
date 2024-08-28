<?php

declare(strict_types=1);

namespace App\Services\Whatsapp\Messaging\Adapters\AmoChat;

class VideoAdapter extends MediaAdapter
{
    protected function mediaType(): string
    {
        return 'video';
    }
}
