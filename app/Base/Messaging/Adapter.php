<?php

namespace App\Base\Messaging;

interface Adapter
{
    public function adapt(array $data): IMessage;
}