<?php

namespace App\Services\Whatsapp\Instance;

interface InstanceServiceInterface
{
    public function create(string $name): CreatedInstanceDTO;

}