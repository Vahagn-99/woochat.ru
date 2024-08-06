<?php

namespace App\Services\AmoChat\Client;

use Exception;

interface ApiClientInterface
{
    /**
     * Execute HTTP request to AmoCRM API
     *
     * @param string $url
     * @param array $body
     * @param string $httpMethod
     * @return array
     * @throws Exception
     */
    public function request(
        string $url,
        array  $body,
        string $httpMethod = 'POST'
    ): array;
}