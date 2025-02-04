<?php

namespace App\Services\Whatsapp\Instance;

use App\Services\Whatsapp\DTO\InstanceDTO;

interface InstanceApiInterface
{
    /**
     * @param array $params
     * @return \App\Services\Whatsapp\Instance\CreatedInstanceDTO
     */
    public function newInstance(array $params): CreatedInstanceDTO;

    /**
     * @return bool
     */
    public function deleteInstance(): bool;

    /**
     * @return array<\App\Services\Whatsapp\Instance\CreatedInstanceDTO>
     */
    public function allInstances(): array;

    public function rebootInstance(): bool;

    public function logoutInstance(): bool;

    public function setInstance(InstanceDTO $instance): static;
}