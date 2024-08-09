<?php

namespace App\Services\AmoCRM\Core;

use AmoCRM\Client\AmoCRMApiClient;
use App\Services\AmoCRM\Auth\AuthManagerInterface;
use App\Services\AmoCRM\Core\ApiClient\ApiClient;
use App\Services\AmoCRM\Core\ManageAccessToken\AccessTokenManagerInterface;
use League\OAuth2\Client\Token\AccessTokenInterface;

readonly class AmoManager implements AmoManagerInterface
{
    public function __construct(
        private ApiClient $apiClientManager,
        private AccessTokenManagerInterface $tokenManager,
        private AuthManagerInterface $authManager
    ) {
    }

    private function reConnect(string $domain): void
    {
        $this->apiClientManager->setAccountBaseDomain($domain);
        $this->apiClientManager->setAccessToken($this->tokenManager->getAccessToken($domain));
        $this->apiClientManager->onAccessTokenRefresh(function (AccessTokenInterface $accessToken) use ($domain) {
            $this->tokenManager->saveAccessToken($domain, $accessToken);
        });
    }

    public function api(string $domain): AmoCRMApiClient
    {
        $this->reConnect($domain);

        return $this->apiClientManager;
    }

    public function authenticator(): AuthManagerInterface
    {
        return $this->authManager;
    }

    public function tokenizer(): AccessTokenManagerInterface
    {
        return $this->tokenManager;
    }
}