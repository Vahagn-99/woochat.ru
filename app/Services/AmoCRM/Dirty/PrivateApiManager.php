<?php

declare(strict_types=1);

namespace App\Services\AmoCRM\Dirty;

use App\Services\AmoCRM\Dirty\Filters\Filter;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use phpDocumentor\Reflection\Exception;

class PrivateApiManager implements PrivateApiInterface
{
    /**
     * @var array
     */
    private array $cookies = [];

    /**
     * @var array
     */
    private mixed $config;

    /**
     *
     */
    public function __construct()
    {
        $this->config = config('amocrm-dct.private-auth', []);
    }

    /**
     * @param \App\Services\AmoCRM\Dirty\Filters\Filter|null $filter
     * @return array|null
     *
     * @throws \Illuminate\Http\Client\ConnectionException
     */
    public function contacts(?Filter $filter = null): ?array
    {
        $url = "/contacts/list";

        if ($filter) {
            $url .= "?query={$filter->value}";
        }

        return $this->buildRequest()->get($url)->json();
    }

    /**
     * @return \Illuminate\Http\Client\PendingRequest
     * @throws \phpDocumentor\Reflection\Exception
     */
    private function buildRequest(): PendingRequest
    {
        $baseUrl = 'https://'.$this->config['subdomain'].'.amocrm.ru/private/api/v2/json';

        // Extract cookies from the response and prepare them for the next request
        foreach ($this->auth()->cookies()->toArray() as $cookie) {
            $this->cookies[$cookie['Name']] = $cookie['Value'];
        }

        return Http::baseUrl($baseUrl)->withCookies($this->cookies, $this->config['subdomain'].'.amocrm.ru')->accept(
            'application/json'
        );
    }

    /**
     * @return \Illuminate\Http\Client\Response
     * @throws \phpDocumentor\Reflection\Exception
     */
    private function auth(): Response
    {
        $response = Http::post('https://'.$this->config['subdomain'].'.amocrm.ru/oauth2/authorize', [
            'temporary_auth' => 'N',
            'username' => $this->config['username'],
            'password' => $this->config['password'],
            'csrf_token' => $this->config['csrf_token'],
        ]);

        if ($response->clientError()) {
            throw new Exception("Не удалес овторизоватся: {$response->reason()}");
        }

        return $response;
    }
}
