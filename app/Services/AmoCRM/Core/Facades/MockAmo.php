<?php

namespace App\Services\AmoCRM\Core\Facades;

use AmoCRM\OAuth\AmoCRMOAuth;
use App\Services\AmoCRM\Auth\AmoCrmAuthManager;
use App\Services\AmoCRM\Core\ApiClient\ApiClient;
use App\Services\AmoCRM\Core\ManageAccessToken\AccessTokenManagerInterface;
use App\Services\AmoCRM\Core\Manager\AmoManager;
use App\Services\AmoCRM\Core\Manager\AmoManagerInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\TestResponse;
use League\OAuth2\Client\Token\AccessToken;
use Mockery;
use PHPUnit\Framework\Assert;

class MockAmo
{
    public static function mockApiManager(): void
    {
        $mockToken = Mockery::mock(AccessToken::class);
        $mockToken->shouldReceive('hasExpired')
            ->withNoArgs()
            ->andReturn(false);
        $mockToken->shouldReceive('jsonSerialize')
            ->andReturn([
                'token_type' => 'Bearer',
                'access_token' => 'test_access_token',
                'refresh_token' => 'test_refresh_token',
                'expires' => 3600,
            ]);

        // mock token manager
        $mockTokenManager = Mockery::mock(AccessTokenManagerInterface::class);
        $mockTokenManager->shouldReceive('saveAccessToken')->with($mockToken);
        $mockTokenManager->shouldReceive('getAccessToken')->andReturn($mockToken);

        // mock oauth manager
        $mockAmoOath = Mockery::mock(AmoCRMOAuth::class);
        $mockAmoOath->shouldReceive('setBaseDomain');
        $mockAmoOath->shouldReceive('getAuthorizeUrl')
            ->andReturn('https://test.amocrm.ru/oauth/authorize');
        $mockAmoOath->shouldReceive('getAccessTokenByCode')
            ->with('test code')
            ->andReturn($mockToken);

        // mock api client
        $mockedApiClient = Mockery::mock(ApiClient::class)->makePartial();
        $mockedApiClient->shouldReceive('getOAuthClient')
            ->andReturn($mockAmoOath);

        $mockedAuthManager = new AmoCrmAuthManager($mockedApiClient);

        $mockedAmoManager = new AmoManager(
            $mockedApiClient,
            $mockTokenManager,
            $mockedAuthManager
        );

        app()->instance(AmoManagerInterface::class, $mockedAmoManager);
    }

    public static function assertAccessTokenSaved(): void
    {
        $disk = Storage::disk('testing');
        $path = $disk->path('access_token.json');
        $actualJson = $disk->get('access_token.json');
        $expectedJson = json_encode([
            'token_type' => 'Bearer',
            'expires_in' => 3600,
            'refresh_token' => 'test_refresh_token',
            'access_token' => 'test_access_token',
        ], JSON_UNESCAPED_SLASHES);

        Assert::assertFileExists($path, 'access token saved successfully');
        Assert::assertJsonStringEqualsJsonString($expectedJson, $actualJson);
    }

    public static function assertRedirectedAuthScreen(TestResponse $response): void
    {
        $response->assertRedirect("https://test.amocrm.ru/oauth/authorize");
    }
}