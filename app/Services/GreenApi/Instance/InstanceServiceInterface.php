<?php

namespace App\Services\GreenApi\Instance;

interface InstanceServiceInterface
{
    public function create(string $name): CreatedInstanceDTO;

}