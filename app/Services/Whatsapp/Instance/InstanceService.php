<?php

namespace App\Services\Whatsapp\Instance;

use App\Services\Whatsapp\DTO\InstanceDTO;
use Illuminate\Support\Arr;

class InstanceService implements InstanceServiceInterface
{
    public function __construct(private readonly InstanceApiInterface $api, private array $config = [])
    {
        $this->config = array_merge(config('whatsapp.instance', []), $this->config);

        if (! isset($this->config['webhookUrl']) && isset($this->config['webhookRouteName'])) {
            $this->config['webhookUrl'] = route($this->config['webhookRouteName']);
        }
    }

    public function create(string $name): CreatedInstanceDTO
    {
        $params = array_merge($this->config, ['name' => $name]);

        return $this->api->newInstance($params);
    }

    public function all(): array
    {
        return $this->api->allInstances();
    }

    public function reboot(): bool
    {
        $this->api->rebootInstance();

        return true;
    }

    public function logout(): bool
    {
        $this->api->logoutInstance();

        return true;
    }

    public function getLastFree(array $usedIds = []): CreatedInstanceDTO|false
    {
        $allInstances = $this->api->allInstances();
        $freeInstances = Arr::where($allInstances, fn(CreatedInstanceDTO $item) => ! in_array($item->id, $usedIds));

        return current($freeInstances);
    }

    public function delete(): bool
    {
        return $this->api->deleteInstance();
    }

    public function setInstance(InstanceDTO $instance): static
    {
        $this->api->setInstance($instance);

        return $this;
    }
}