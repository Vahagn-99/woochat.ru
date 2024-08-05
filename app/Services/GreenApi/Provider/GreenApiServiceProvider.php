<?php

namespace App\Services\GreenApi\Provider;

use App\Enums\InstanceStatus;
use App\Services\GreenApi\ClientService\GreenApiService;
use App\Services\GreenApi\ClientService\GreenApiServiceInterface;
use App\Services\GreenApi\GreenManager;
use App\Services\GreenApi\GreenManagerInterface;
use App\Services\GreenApi\Instance\CreateInstanceApi;
use App\Services\GreenApi\Instance\CreateInstanceApiInterface;
use App\Services\GreenApi\Instance\GetInstanceStatusService;
use App\Services\GreenApi\Instance\GetInstanceStatusServiceInterface;
use App\Services\GreenApi\Instance\InstanceService;
use App\Services\GreenApi\Instance\InstanceServiceInterface;
use App\Services\GreenApi\Messaging\MessagingService;
use App\Services\GreenApi\Messaging\MessagingServiceInterface;
use App\Services\GreenApi\Messaging\Send\SendMessageService;
use App\Services\GreenApi\Messaging\Send\SendMessageServiceInterface;
use App\Services\GreenApi\QRCode\QRCodeApi;
use App\Services\GreenApi\QRCode\QRCodeApiInterface;
use App\Services\GreenApi\QRCode\QRCodeService;
use App\Services\GreenApi\QRCode\QRCodeServiceInterface;
use GraphQL\Type\Definition\PhpEnumType;
use GreenApi\RestApi\GreenApiClient;
use Illuminate\Support\ServiceProvider;
use Nuwave\Lighthouse\Schema\TypeRegistry;

class GreenApiServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(SendMessageServiceInterface::class, SendMessageService::class);
        $this->app->bind(MessagingServiceInterface::class, MessagingService::class);
        $this->app->bind(GreenManagerInterface::class, GreenManager::class);
        $this->app->bind(GreenApiServiceInterface::class, GreenApiService::class);
        $this->app->bind(GreenApiClient::class, function () {
            return new GreenApiClient(
                'dummy-instance-id',
                'dummy-instance-token',
            );
        });
        $this->app->bind(CreateInstanceApiInterface::class, function () {
            return new CreateInstanceApi(config('greenapi.partner.api_url'), config('greenapi.partner.api_token'));
        });
        $this->app->bind(InstanceServiceInterface::class, InstanceService::class);
        $this->app->bind(QRCodeApiInterface::class, QRCodeApi::class);
        $this->app->bind(QRCodeServiceInterface::class, QRCodeService::class);
        $this->app->bind(GetInstanceStatusServiceInterface::class, GetInstanceStatusService::class);


        //facade
        $this->app->singleton('green-api', GreenManagerInterface::class);
    }

    public function boot(): void
    {
        $typeRegistry = app(TypeRegistry::class);
        $typeRegistry->register(new PhpEnumType(InstanceStatus::class));
    }
}
