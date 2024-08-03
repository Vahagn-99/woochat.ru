<?php

namespace App\Services\GreenApi\Instance;

interface InstanceManagerInterface
{
    public function create(string $name): CreatedInstanceDTO;
}