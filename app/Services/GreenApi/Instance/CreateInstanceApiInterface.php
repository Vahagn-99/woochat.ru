<?php

namespace App\Services\GreenApi\Instance;

interface CreateInstanceApiInterface
{
    public function newInstance(array $params): CreatedInstanceDTO;
}