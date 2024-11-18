<?php

namespace App\Services\Whatsapp\Instance;

use App\Services\Whatsapp\DTO\InstanceDTO;
use Illuminate\Foundation\Bus\PendingDispatch;

interface InstanceServiceInterface
{
    /**
     * @param string $name
     * @return \App\Services\Whatsapp\Instance\CreatedInstanceDTO|\Illuminate\Foundation\Bus\PendingDispatch
     */
    public function create(string $name): CreatedInstanceDTO|PendingDispatch;

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
    public function delete(): bool;

    /**
     * @return bool
     */
    public function reboot(): bool;

    /**
     * @return bool
     */
    public function logout(): bool;

    /**
     * @return \App\Services\Whatsapp\Instance\InstanceServiceInterface
     */
    public function withoutQueue(): static;

    /**
     * @return \App\Services\Whatsapp\Instance\InstanceServiceInterface
     */
    public function withQueue(): static;

    public function setInstance(InstanceDTO $instance): static;
}