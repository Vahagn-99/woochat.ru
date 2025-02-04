<?php

declare(strict_types=1);

namespace App\Services\AmoCRM\Core\Oauth;

use AmoCRM\OAuth\OAuthServiceInterface;
use App\Models\AmoAccessToken;
use Illuminate\Support\Facades\Storage;
use League\OAuth2\Client\Token\AccessTokenInterface;

class FileOauthService implements OAuthServiceInterface
{
    public function saveOAuthToken(AccessTokenInterface $accessToken, string $baseDomain): void
    {
        Storage::disk('dct')->put('/amocrm/access_token.json', json_encode($accessToken, JSON_PRETTY_PRINT));
    }
}
