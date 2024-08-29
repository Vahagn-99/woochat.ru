<?php

namespace App\Services\Whatsapp\Instance;

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
     * @param array $usedIds
     * @return \App\Services\Whatsapp\Instance\CreatedInstanceDTO|false
     */
    public function getLastFree(array $usedIds = []): CreatedInstanceDTO|false;

    /**
     * @return bool
     */
    public function reboot(): bool;

    /**
     * @return bool
     */
    public function logout(): bool;
}