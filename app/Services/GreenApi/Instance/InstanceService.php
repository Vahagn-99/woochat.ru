<?php

namespace App\Services\GreenApi\Instance;

class InstanceService implements InstanceServiceInterface
{
    public function __construct(private readonly CreateInstanceApiInterface $api, private array $config = [])
    {
        $this->config = array_merge(config('greenapi.instance') ?? [], $this->config);

        if (isset($this->config['webhookUrl'])) {
            $this->config['webhookUrl'] = route($this->config['webhookUrl']);
        }

    }

    public function create(string $name): CreatedInstanceDTO
    {
        $params = array_merge($this->config, ['name' => $name]);
        return $this->api->newInstance($params);
    }
}