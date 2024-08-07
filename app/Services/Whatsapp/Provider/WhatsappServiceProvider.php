<?php

namespace App\Services\Whatsapp\Provider;

use App\Enums\InstanceStatus;
use App\Services\Whatsapp\ClientService\WhatsappApiService;
use App\Services\Whatsapp\ClientService\WhatsappApiServiceInterface;
use App\Services\Whatsapp\Instance\CreateInstanceApi;
use App\Services\Whatsapp\Instance\CreateInstanceApiInterface;
use App\Services\Whatsapp\Instance\GetInstanceStatusService;
use App\Services\Whatsapp\Instance\GetInstanceStatusServiceInterface;
use App\Services\Whatsapp\Instance\InstanceService;
use App\Services\Whatsapp\Instance\InstanceServiceInterface;
use App\Services\Whatsapp\Manager\WhatsappManager;
use App\Services\Whatsapp\Manager\WhatsappManagerInterface;
use App\Services\Whatsapp\Messaging\WhatsappMessagingInterface;
use App\Services\Whatsapp\Messaging\WhatsappMessaging;
use App\Services\Whatsapp\QRCode\QRCodeApi;
use App\Services\Whatsapp\QRCode\QRCodeApiInterface;
use App\Services\Whatsapp\QRCode\QRCodeService;
use App\Services\Whatsapp\QRCode\QRCodeServiceInterface;
use GraphQL\Type\Definition\PhpEnumType;
use GreenApi\RestApi\GreenApiClient;
use Illuminate\Support\ServiceProvider;
use Nuwave\Lighthouse\Schema\TypeRegistry;

class WhatsappServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(WhatsappMessagingInterface::class, WhatsappMessaging::class);
        $this->app->bind(WhatsappManagerInterface::class, WhatsappManager::class);
        $this->app->bind(WhatsappApiServiceInterface::class, WhatsappApiService::class);
        $this->app->bind(GreenApiClient::class, function () {
            return new GreenApiClient(
                'dummy-instance-id',
                'dummy-instance-token',
            );
        });
        $this->app->bind(CreateInstanceApiInterface::class, function () {
            return new CreateInstanceApi(config('whatsapp.partner.api_url'), config('whatsapp.partner.api_token'));
        });
        $this->app->bind(InstanceServiceInterface::class, InstanceService::class);
        $this->app->bind(QRCodeApiInterface::class, QRCodeApi::class);
        $this->app->bind(QRCodeServiceInterface::class, QRCodeService::class);
        $this->app->bind(GetInstanceStatusServiceInterface::class, GetInstanceStatusService::class);

        //facade
        $this->app->singleton('whatsapp', WhatsappManagerInterface::class);
    }

    public function boot(): void
    {
        $typeRegistry = app(TypeRegistry::class);
        $typeRegistry->register(new PhpEnumType(InstanceStatus::class));
    }
}
