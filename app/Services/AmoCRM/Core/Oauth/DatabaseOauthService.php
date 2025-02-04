<?php

declare(strict_types=1);

namespace App\Services\AmoCRM\Core\Oauth;

use AmoCRM\OAuth\OAuthServiceInterface;
use App\Models\AmoAccessToken;
use League\OAuth2\Client\Token\AccessTokenInterface;

class DatabaseOauthService implements OAuthServiceInterface
{
    public function saveOAuthToken(AccessTokenInterface $accessToken, string $baseDomain): void
    {
        AmoAccessToken::saveWithDomain($baseDomain, $accessToken);
    }
}
