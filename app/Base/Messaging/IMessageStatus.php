<?php

namespace App\Base\Messaging;

use App\Contracts\Arrayable;
use JsonSerializable;

interface IMessageStatus extends Arrayable, JsonSerializable
{
    public function getStatus(): mixed;
    public function getId(): string;
}