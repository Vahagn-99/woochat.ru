<?php

declare(strict_types=1);

namespace App\Services\AmoCRM\Core\Oauth;

use AmoCRM\OAuth\OAuthConfigInterface;

readonly class OauthConfig implements OAuthConfigInterface
{
    public function __construct(
        private string $integrationId,
        private string $secretKey,
        private string $redirectUrl,
    ) {
    }

    public function getIntegrationId(): string
    {
        return $this->integrationId;
    }

    public function getSecretKey(): string
    {
        return $this->secretKey;
    }

    public function getRedirectDomain(): string
    {
        return $this->redirectUrl;
    }
}
