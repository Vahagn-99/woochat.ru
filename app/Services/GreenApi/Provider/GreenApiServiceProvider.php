<?php

namespace App\Services\GreenApi\Provider;

use App\Enums\InstanceStatus;
use App\Services\GreenApi\Instance\CreateInstanceApi;
use App\Services\GreenApi\Instance\CreateInstanceApiInterface;
use App\Services\GreenApi\Instance\InstanceManager;
use App\Services\GreenApi\Instance\InstanceManagerInterface;
use App\Services\GreenApi\QRCode\QRCodeApi;
use App\Services\GreenApi\QRCode\QRCodeApiInterface;
use App\Services\GreenApi\QRCode\QRCodeManager;
use App\Services\GreenApi\QRCode\QRCodeManagerInterface;
use GraphQL\Type\Definition\PhpEnumType;
use GreenApi\RestApi\GreenApiClient;
use Illuminate\Support\ServiceProvider;
use Nuwave\Lighthouse\Schema\TypeRegistry;

class GreenApiServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(GreenApiClient::class, function () {
            return new GreenApiClient(
                'dummy-instance-id',
                'dummy-instance-token',
            );
        });
        $this->app->bind('greenapi', GreenApiClient::class);
        $this->app->bind(CreateInstanceApiInterface::class, function () {
            return new CreateInstanceApi(config('greenapi.partner.api_url'), config('greenapi.partner.api_token'));
        });
        $this->app->bind(InstanceManagerInterface::class, InstanceManager::class);
        $this->app->bind(QRCodeApiInterface::class, QRCodeApi::class);
        $this->app->bind(QRCodeManagerInterface::class, QRCodeManager::class);
    }

    public function boot(): void
    {
        $typeRegistry = app(TypeRegistry::class);
        $typeRegistry->register(new PhpEnumType(InstanceStatus::class));
    }
}
