<?php

namespace App\Services\AmoCRM\Auth;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use League\OAuth2\Client\Token\AccessTokenInterface;

class AmoCrmAuthManager implements AuthManagerInterface
{
    const AUTH_MODE_POST_MESSAGE_TYPE = 'post_message';

    public function __construct(
        private readonly AmoCRMApiClient $apiClient
    ) {

    }

    public function url(): string
    {
        return $this->apiClient->getOAuthClient()->getAuthorizeUrl([
            'mode' => self::AUTH_MODE_POST_MESSAGE_TYPE,
            'state' => config('app.name'),
        ]);
    }

    /**
     * @throws AmoCRMoAuthApiException
     */
    public function exchangeCodeWithAccessToken(string $code): AccessTokenInterface
    {
        $oauth = $this->apiClient->getOAuthClient();
        // if no access token but there is a code from redirect
        // we can get token from that code
        $accessToken = $oauth->getAccessTokenByCode($code);

        if ($accessToken->hasExpired()) {
            $accessToken = $oauth->getAccessTokenByRefreshToken($accessToken);
        }

        return $accessToken;
    }
}
