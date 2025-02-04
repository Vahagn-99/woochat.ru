<?php

namespace App\Services\AmoChat\Messaging\Types;

use App\Base\Messaging\IMessage;
use App\Base\Messaging\Manageable;

class Contact implements IMessage
{
    use Manageable;

    public function __construct(
        public string $name,
        public string $phone,
        public string $text = '',
    )
    {
    }

    public function getType(): string
    {
        return 'contact';
    }

    public function toArray(): array
    {
        return array_filter([
            'type' => $this->getType(),
            'name' => $this->name,
            'phone' => $this->phone,
            'text' => $this->text,
        ]);
    }
}