<?php

declare(strict_types=1);

namespace App\Base\Messaging;

use App\Exceptions\Messaging\AdapterNotDefinedException;
use App\Exceptions\Messaging\ProviderNotConfiguredException;
use App\Exceptions\Messaging\UnknownMessageTypeException;

class Factory
{
    /**
     * @var array<string>
     */
    private array $config;

    /**
     * @var mixed|string
     */
    private string $type;

    private string $from;

    private string $to;

    public function __construct(array $config = [])
    {
        $this->config = array_merge(config('massaging'), $config);
    }

    /**
     * @throws \App\Exceptions\Messaging\UnknownMessageTypeException
     * @throws \App\Exceptions\Messaging\ProviderNotConfiguredException
     */
    public function from(string $fromProvider, string $localType): Factory
    {
        $this->ensureProviderConfigured($fromProvider);

        $this->from = $fromProvider;

        $schema = $this->config['providers'][$fromProvider]['schema'];
        foreach ($schema as $item) {
            if ($localType === $item['local_type']) {
                $this->type = $item['type'];

                return $this;
            }
        }

        throw  UnknownMessageTypeException::localType($localType, $fromProvider);
    }

    /**
     * @throws \App\Exceptions\Messaging\ProviderNotConfiguredException
     * @throws \App\Exceptions\Messaging\AdapterNotDefinedException
     */
    public function to(string $toProvider, array $params): IMessage
    {
        $this->ensureProviderConfigured($toProvider);

        $this->to = $toProvider;

        $adapter = $this->makeAdapter();

        return $adapter->adapt($params);
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
