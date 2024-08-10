<?php

namespace App\Services\Whatsapp\Instance;

use App\Services\Whatsapp\DTO\InstanceDTO;

interface InstanceServiceInterface
{
    /**
     * @param string $name
     * @return \App\Services\Whatsapp\Instance\CreatedInstanceDTO
     */
    public function create(string $name): CreatedInstanceDTO;

    /**
     * @return array<\App\Services\Whatsapp\Instance\CreatedInstanceDTO>
     */
    public function all(): array;

    /**
     * @return bool
     */
    public function reboot(): bool;

    /**
     * @return bool
     */
    public function logout(): bool;
}