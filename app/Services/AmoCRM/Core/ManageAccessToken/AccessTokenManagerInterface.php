<?php

namespace App\Services\AmoCRM\Core\ManageAccessToken;

use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Token\AccessTokenInterface;

interface AccessTokenManagerInterface
{
    public function getAccessToken(string $domain): AccessToken;

    public function saveAccessToken(string $domain, AccessTokenInterface $token): void;
}