<?php

namespace App\Services\Whatsapp\Instance;

interface InstanceApiInterface
{
    /**
     * @param array $params
     * @return \App\Services\Whatsapp\Instance\CreatedInstanceDTO
     */
    public function newInstance(array $params): CreatedInstanceDTO;

    /**
     * @return array<\App\Services\Whatsapp\Instance\CreatedInstanceDTO>
     */
    public function allInstances(): array;

    public function rebootInstance(): bool;

    public function logoutInstance(): bool;
}