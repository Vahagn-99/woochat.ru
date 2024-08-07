<?php

namespace App\Base\Chat\Message;

use App\Contracts\Arrayable;
use JsonSerializable;

interface IMessage extends Arrayable, JsonSerializable
{
    public function getChatId(): string;

    public function getType(): string;
}