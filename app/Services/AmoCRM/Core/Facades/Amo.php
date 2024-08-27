<?php

namespace App\Services\AmoCRM\Core\Facades;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\OAuth\OAuthServiceInterface;
use App\Services\AmoCRM\Auth\AuthManagerInterface;
use App\Services\AmoCRM\Core\Manager\AmoManager;
use App\Services\AmoCRM\Core\Manager\AmoManagerInterface;
use App\Services\AmoCRM\Core\Oauth\OauthStatusInterface;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\TestResponse;
use League\OAuth2\Client\Token\AccessToken;

/**
 * @method static AmoManagerInterface domain(string $domain)
 * @method static AuthManagerInterface authenticator()
 * @method static AmoCRMApiClient api()
 * @method static OAuthServiceInterface oauth()
 * @method static OauthStatusInterface instance()
 *
 * @see AmoManagerInterface
 * @mixin AuthManagerInterface
 */
class Amo extends Facade
{
    public static function main(): AmoManagerInterface
    {
        /** @var AmoCRMApiClient $client */
        $client = app("dct-amo-client");

        $client->setAccountBaseDomain(config('amocrm-dct.widget.domain'));

        $file = json_decode(Storage::disk("dct")->get('/amocrm/access_token.json'), true);

        if ($file) {
            $accessToken = new AccessToken($file);
            $client->setAccessToken($accessToken);
        }

        app()->instance(AmoCRMApiClient::class, $client);

        /** @var AmoManager */
        return app('amocrm');
    }

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