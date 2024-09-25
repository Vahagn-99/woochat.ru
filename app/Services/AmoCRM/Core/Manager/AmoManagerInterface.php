<?php

namespace App\Services\AmoCRM\Core\Manager;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\OAuth\OAuthServiceInterface;
use App\Services\AmoCRM\Auth\AuthManagerInterface;
use App\Services\AmoCRM\Core\Oauth\OauthStatusInterface;
use App\Services\AmoCRM\Dirty\PrivateApiInterface;

interface AmoManagerInterface
{
    public function api(): AmoCRMApiClient;

    public function authenticator(): AuthManagerInterface;

    public function oauth(): OAuthServiceInterface;

    public function domain(string $domain): static;

    public function instance(): OauthStatusInterface;

    public function privateApi(): PrivateApiInterface;
}