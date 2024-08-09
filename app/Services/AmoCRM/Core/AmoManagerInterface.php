<?php

namespace App\Services\AmoCRM\Core;

use AmoCRM\Client\AmoCRMApiClient;
use App\Services\AmoCRM\Auth\AuthManagerInterface;
use App\Services\AmoCRM\Core\ManageAccessToken\AccessTokenManagerInterface;

interface AmoManagerInterface
{
    public function api(string $domain): AmoCRMApiClient;

    public function authenticator(): AuthManagerInterface;

    public function tokenizer(): AccessTokenManagerInterface;
}