<?php

namespace App\Services\Whatsapp\Instance;

interface CreateInstanceApiInterface
{
    public function newInstance(array $params): CreatedInstanceDTO;
}