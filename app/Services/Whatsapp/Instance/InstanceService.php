<?php

namespace App\Services\Whatsapp\Instance;

class InstanceService implements InstanceServiceInterface
{
    public function __construct(private readonly CreateInstanceApiInterface $api, private array $config = [])
    {
        $this->config = array_merge(config('whatsapp.instance') ?? [], $this->config);

        if (!isset($this->config['webhookUrl']) && isset($this->config['webhookRouteName'])) {
            $this->config['webhookUrl'] = route($this->config['webhookRouteName']);
        }

    }

    public function create(string $name): CreatedInstanceDTO
    {
        $params = array_merge($this->config, ['name' => $name]);
        return $this->api->newInstance($params);
    }
}