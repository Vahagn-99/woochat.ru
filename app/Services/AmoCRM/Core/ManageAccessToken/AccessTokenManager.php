<?php

namespace App\Services\AmoCRM\Core\ManageAccessToken;

use App\Models\AmoAccessToken;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Token\AccessTokenInterface;

readonly class AccessTokenManager implements AccessTokenManagerInterface
{
    public function getAccessToken(string $domain): AccessToken
    {
        $token = AmoAccessToken::findByDomain($domain);

        return $token->getAccessToken();
    }

    public function saveAccessToken(string $domain, AccessTokenInterface $token): void
    {
        AmoAccessToken::saveWithDomain($domain, $token);
    }
}