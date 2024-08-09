<?php

namespace App\Services\AmoCRM\Core\Facades;

use AmoCRM\EntitiesServices\BaseEntity;
use AmoCRM\EntitiesServices\Contacts;
use AmoCRM\EntitiesServices\Leads;
use AmoCRM\EntitiesServices\Users;
use App\Services\AmoCRM\Auth\AuthManagerInterface;
use App\Services\AmoCRM\Core\AmoManager;
use App\Services\AmoCRM\Core\ApiClient\ApiClient;
use App\Services\AmoCRM\Core\ManageAccessToken\AccessTokenManagerInterface;
use Exception;
use Illuminate\Support\Facades\Facade;
use Illuminate\Testing\TestResponse;

/**
 * @method static ApiClient reConnect(string $domain)
 * @method static ApiClient api(string $domain)
 * @method static AuthManagerInterface authenticator()
 * @method static AccessTokenManagerInterface tokenizer()
 *
 * @see AmoManager
 * @mixin AuthManagerInterface
 */
class Amo extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'amocrm';
    }

    public static function fake(): void
    {
        // mock access token
        MockAmo::mockApiManager();
    }

    public static function assertAccessTokenSaved(): void
    {
        MockAmo::assertAccessTokenSaved();
    }

    public static function assertRedirectedAuthScreen(TestResponse $response): void
    {
        MockAmo::assertRedirectedAuthScreen($response);
    }
}