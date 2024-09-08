<?php

declare(strict_types=1);

namespace App\Base\Messaging;

use App\Exceptions\Messaging\AdapterNotDefinedException;
use App\Exceptions\Messaging\ProviderNotConfiguredException;
use App\Exceptions\Messaging\UnknownMessageStatusException;
use App\Exceptions\Messaging\UnknownMessageTypeException;

class Factory
{
    /**
     * @var array<string>
     */
    private array $config;

    private string $type;

    private string $from;

    private string $to;

    public function __construct(array $config = [])
    {
        $this->config = array_merge(config('massaging'), $config);
    }

    /**
     * @param array $config
     * @return \App\Base\Messaging\Factory
     */
    public static function make(array $config = []): static
    {
        return new static($config);
    }

    /**
     * @throws \App\Exceptions\Messaging\ProviderNotConfiguredException
     */
    public function from(string $fromProvider): Factory
    {
        $this->ensureProviderConfigured($fromProvider);

        $this->from = $fromProvider;

        return $this;
    }

    /**
     * @throws \App\Exceptions\Messaging\ProviderNotConfiguredException
     */
    public function to(string $toProvider): Factory
    {
        $this->ensureProviderConfigured($toProvider);

        $this->to = $toProvider;

        return $this;
    }

    /**
     * @throws \App\Exceptions\Messaging\UnknownMessageTypeException
     */
    public function type(string $localType): Factory
    {
        $schema = $this->config['providers'][$this->from]['schema'];
        foreach ($schema as $item) {
            if ($localType === $item['local_type']) {
                $this->type = $item['type'];

                return $this;
            }
        }

        throw  UnknownMessageTypeException::localType($localType, $this->from);
    }

    /**
     * @throws \App\Exceptions\Messaging\AdapterNotDefinedException
     */
    public function getAdaptedMessage(array $params = []): IMessage
    {
        $adapter = $this->makeAdapter();

        return $adapter->adapt($params);
    }

    /**
     * @throws \App\Exceptions\Messaging\UnknownMessageStatusException
     */
    public function getAdaptedStatus(string $status): mixed
    {
        $statuses = $this->config['providers'][$this->from]['delivery_status'];
        $adaptedStatuses = $this->config['providers'][$this->to]['delivery_status'];

        $name = null;

        foreach ($statuses as $key => $localName) {
            if ($status == $localName) {
                $name = $key;
            }
        }

        if (! isset($name)) {
            throw UnknownMessageStatusException::status($status, $this->from);
        }

        if (! isset($adaptedStatuses[$name])) {
            throw UnknownMessageStatusException::status($status, $this->to);
        }

        return $adaptedStatuses[$name];
    }

    /**
     * @throws \App\Exceptions\Messaging\AdapterNotDefinedException
     */
    private function makeAdapter(): Adapter
    {
        if (! isset($this->config['providers'][$this->from]['adapters'][$this->to][$this->type])) {
            throw new AdapterNotDefinedException($this->from, $this->to, $this->type);
        }

        return app($this->config['providers'][$this->from]['adapters'][$this->to][$this->type]);
    }

    /**
     * @throws \App\Exceptions\Messaging\ProviderNotConfiguredException
     */
    private function ensureProviderConfigured(string $provider): void
    {
        if (! isset($this->config['providers'][$provider])) {
            throw new ProviderNotConfiguredException($provider);
        }
    }
}
