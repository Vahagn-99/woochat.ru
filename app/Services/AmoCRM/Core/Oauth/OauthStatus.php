<?php

namespace App\Services\AmoCRM\Core\Oauth;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMMissedTokenException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;

readonly class OauthStatus implements OauthStatusInterface
{
    public function __construct(private AmoCRMApiClient $amoClient)
    {
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