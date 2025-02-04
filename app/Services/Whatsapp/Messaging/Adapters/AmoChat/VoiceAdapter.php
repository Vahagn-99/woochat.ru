<?php

declare(strict_types=1);

namespace App\Services\Whatsapp\Messaging\Adapters\AmoChat;

class VoiceAdapter extends MediaAdapter
{
    protected function mediaType(): string
    {
        return 'voice';
    }
}
