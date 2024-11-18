<?php

namespace App\Services\Whatsapp\Instance;

use App\Jobs\CreateWhatsappInstance;
use App\Jobs\DeleteWhatsappInstance as DeleteWhatsappInstanceJob;
use App\Jobs\LogoutWhatsappInstance;
use App\Jobs\RebootWhatsappInstance as RebootWhatsappInstanceJob;
use App\Services\Whatsapp\DTO\InstanceDTO;
use Illuminate\Foundation\Bus\PendingDispatch;
use Illuminate\Support\Arr;

class InstanceService implements InstanceServiceInterface
{
    /**
     * @var bool
     */
    private bool $shouldQueue = false;

    /**
     * @var \App\Services\Whatsapp\DTO\InstanceDTO
     */
    private InstanceDTO $instance;

    /**
     * @param \App\Services\Whatsapp\Instance\InstanceApiInterface $api
     * @param array $config
     */
    public function __construct(private readonly InstanceApiInterface $api, private array $config = [])
    {
        $this->config = array_merge(config('whatsapp.instance', []), $this->config);

        if (! isset($this->config['webhookUrl'])
            && isset($this->config['webhookRouteName'])) {
            $this->config['webhookUrl'] = route($this->config['webhookRouteName']);
        }
    }

    /**
     * @param string $name
     * @return \App\Services\Whatsapp\Instance\CreatedInstanceDTO|\Illuminate\Foundation\Bus\PendingDispatch
     */
    public function create(string $name): CreatedInstanceDTO|PendingDispatch
    {
        $params = array_merge($this->config, ['name' => $name]);

        if ($this->shouldQueue) {
            return CreateWhatsappInstance::dispatch($params);
        }

        return $this->api->newInstance($params);
    }

    /**
     * @return array|\App\Services\Whatsapp\Instance\CreatedInstanceDTO[]
     */
    public function all(): array
    {
        return $this->api->allInstances();
    }

    /**
     * @return bool
     */
    public function reboot(): bool
    {
        if ($this->shouldQueue) {
            RebootWhatsappInstanceJob::dispatch($this->instance);

            return true;
        }

        $this->api->rebootInstance();

        return true;
    }

    /**
     * @return bool
     */
    public function logout(): bool
    {
        if ($this->shouldQueue) {
            LogoutWhatsappInstance::dispatch($this->instance);

            return true;
        }

        $this->api->logoutInstance();

        return true;
    }

    /**
     * @param array $usedIds
     * @return \App\Services\Whatsapp\Instance\CreatedInstanceDTO|false
     */
    public function getLastFree(array $usedIds = []): CreatedInstanceDTO|false
    {
        $allInstances = $this->api->allInstances();
        $freeInstances = Arr::where($allInstances, fn(CreatedInstanceDTO $item) => ! in_array($item->id, $usedIds));

        return current($freeInstances);
    }

    /**
     * @return bool
     */
    public function delete(): bool
    {
        if ($this->shouldQueue) {
            DeleteWhatsappInstanceJob::dispatch($this->instance);

            return true;
        }

        return $this->api->deleteInstance();
    }

    /**
     * @param \App\Services\Whatsapp\DTO\InstanceDTO $instance
     * @return $this
     */
    public function setInstance(InstanceDTO $instance): static
    {
        $this->instance = $instance;

        $this->api->setInstance($instance);

        return $this;
    }

    /**
     * @return $this
     */
    public function withoutQueue(): static
    {
        $this->shouldQueue = false;

        return $this;
    }

    /**
     * @return $this
     */
    public function withQueue(): static
    {
        $this->shouldQueue = true;

        return $this;
    }
}