<?php

namespace App\Services\AmoCRM\Core\Manager;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\OAuth\OAuthServiceInterface;
use App\Services\AmoCRM\Auth\AuthManagerInterface;

readonly class AmoManager implements AmoManagerInterface
{
    public function __construct(
        private AmoCRMApiClient $apiClient,
        private AuthManagerInterface $authManager,
        private OAuthServiceInterface $authService
    ) {
    }

    public function api(): AmoCRMApiClient
    {
        return $this->apiClient;
    }

    public function authenticator(): AuthManagerInterface
    {
        return $this->authManager;
    }

    public function oauth(): OAuthServiceInterface
    {
        return $this->authService;
    }
}