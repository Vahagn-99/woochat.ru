<?php

namespace App\Services\AmoCRM\Auth;


use League\OAuth2\Client\Token\AccessTokenInterface;

interface AuthManagerInterface
{
    const AUTH_MODE_POST_MESSAGE_TYPE = 'post_message';

    public function url(): string;

    public function exchangeCodeWithAccessToken(string $domain, string $code):  AccessTokenInterface;
}
