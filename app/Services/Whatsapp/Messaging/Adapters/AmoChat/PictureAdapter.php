<?php

declare(strict_types=1);

namespace App\Services\Whatsapp\Messaging\Adapters\AmoChat;

class PictureAdapter extends MediaAdapter
{
    protected function mediaType(): string
    {
        return 'picture';
    }
}
