<?php

declare(strict_types=1);

namespace App\Services\AmoCRM\Core\Oauth;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMMissedTokenException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\OAuth\OAuthServiceInterface;
use App\Models\AmoAccessToken;
use League\OAuth2\Client\Token\AccessTokenInterface;

class OauthService implements OAuthServiceInterface, OauthStatusInterface
{
    public function __construct(private readonly AmoCRMApiClient $amoClient)
    {
    }

    public function saveOAuthToken(AccessTokenInterface $accessToken, string $baseDomain): void
    {
        AmoAccessToken::saveWithDomain($baseDomain, $accessToken);
    }

    public function status(): bool
    {
        try {
            $this->amoClient->account()->getCurrent();

            return true;
        } catch (AmoCRMMissedTokenException|AmoCRMApiException|AmoCRMoAuthApiException  $exception) {
            do_log('amocrm/oauth')->warning("У аккаунта нет валидний токен овтризации: ошибка из апи ".$exception->getMessage());

            return false;
        }
    }
}
