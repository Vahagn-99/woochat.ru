<?php

namespace App\Services\Whatsapp\Instance;

use App\Exceptions\Whatsapp\InstanceCreationException;
use App\Services\Whatsapp\Facades\Whatsapp;
use GuzzleHttp\Client;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class InstanceApi implements InstanceApiInterface
{
    protected static string $host = 'https://api.green-api.com';

    public function __construct(
        private readonly string $partnerApiUrl,
        private readonly string $partnerToken,
    ) {
    }

    private function buildRequest(): PendingRequest
    {
        $client = new Client([
            'base_uri' => self::$host,
        ]);

        return Http::acceptJson()->setClient($client);
    }

    /**
     * @throws InstanceCreationException
     */
    public function newInstance(array $params): CreatedInstanceDTO
    {
        $endpoint = $this->setParams("/partner/createInstance");

        try {

            $response = $this->buildRequest()->post($endpoint, $params)->json();

            return new CreatedInstanceDTO($response['idInstance'], $response['apiTokenInstance'], Arr::get($params, 'name'));
        } catch (ConnectionException $e) {
            throw new  InstanceCreationException($e->getMessage());
        }
    }

    /**
     * @throws \App\Exceptions\Whatsapp\InstanceCreationException
     */
    public function allInstances(): array
    {
        $endpoint = $this->setParams("/partner/getInstances");

        try {

            $response = $this->buildRequest()->post($endpoint)->json();

            $data = Arr::where($response, fn(array $item) => ! $item['deleted'] && ! $item['isExpired']);
            return Arr::map($data, fn(array $item
            ) => new CreatedInstanceDTO($item['idInstance'], $item['apiTokenInstance'], $item['name']));
        } catch (ConnectionException $e) {
            throw new  InstanceCreationException($e->getMessage());
        }
    }

    public function rebootInstance(): bool
    {
        Whatsapp::api()->getClient()->account->reboot();

        return true;
    }

    public function logoutInstance(): bool
    {
        Whatsapp::api()->getClient()->account->logout();

        return true;
    }

    private function setParams(string $url): string
    {
        return $this->partnerApiUrl."/".trim($url, '/')."/".$this->partnerToken;
    }
}