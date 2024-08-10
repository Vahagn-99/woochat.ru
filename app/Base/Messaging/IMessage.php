<?php

namespace App\Base\Messaging;

use App\Contracts\Arrayable;
use JsonSerializable;

interface IMessage extends Arrayable, JsonSerializable
{
    public function getChatId(): string;

    public function getType(): string;
}