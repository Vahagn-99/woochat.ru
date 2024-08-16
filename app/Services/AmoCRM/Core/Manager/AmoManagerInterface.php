<?php

namespace App\Services\AmoCRM\Core\Manager;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\OAuth\OAuthServiceInterface;
use App\Services\AmoCRM\Auth\AuthManagerInterface;

interface AmoManagerInterface
{
    public function api(): AmoCRMApiClient;

    public function authenticator(): AuthManagerInterface;

    public function oauth(): OAuthServiceInterface;

    public function domain(string $domain): static;
}