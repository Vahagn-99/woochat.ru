<?php

namespace App\Base\Chat\Message;

class Response
{
    public function __construct(
        public EventType  $event_type,
        public MessageId  $id,
        public ?MessageId $ref_id = null
    )
    {
    }
}