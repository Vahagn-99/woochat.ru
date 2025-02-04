<?php

namespace App\Services\AmoCRM\Core\Oauth;

interface OauthStatusInterface
{
    public function status(): bool;
}