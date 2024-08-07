<?php

namespace App\Services\Whatsapp\Facades;

use App\Base\Chat\Message\EventType;
use App\Base\Chat\Message\MessageId;
use App\Base\Chat\Message\MessagingInterface;
use App\Base\Chat\Message\Response;
use App\Enums\InstanceStatus;
use App\Models\Instance;
use App\Services\Whatsapp\ClientService\WhatsappApiServiceInterface;
use App\Services\Whatsapp\DTO\InstanceDTO;
use App\Services\Whatsapp\Instance\CreatedInstanceDTO;
use App\Services\Whatsapp\Instance\GetInstanceStatusServiceInterface;
use App\Services\Whatsapp\Instance\InstanceServiceInterface;
use App\Services\Whatsapp\Manager\WhatsappManager;
use App\Services\Whatsapp\Manager\WhatsappManagerInterface;
use App\Services\Whatsapp\Messaging\WhatsappMessagingInterface;
use App\Services\Whatsapp\QRCode\QRCodeResponseDTO;
use App\Services\Whatsapp\QRCode\QRCodeServiceInterface;
use GreenApi\RestApi\GreenApiClient;
use Illuminate\Support\Facades\Facade;
use Mockery;

/**
 * @method static WhatsappApiServiceInterface api()
 * @method static QRCodeServiceInterface qr()
 * @method static InstanceServiceInterface instance()
 * @method static InstanceStatus status()
 * @method static MessagingInterface massaging()
 *
 * @see WhatsappManagerInterface
 */
class Whatsapp extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'whatsapp';
    }

    public static function for(Instance|InstanceDTO|array $instance): WhatsappManagerInterface
    {
        $instance = is_array($instance) ? InstanceDTO::fromArray($instance) : $instance;
        $instance = $instance instanceof Instance ? $instance->toDto() : $instance;

        $client = new GreenApiClient(
            $instance->id,
            $instance->token
        );

        app()->instance(GreenApiClient::class, $client);

        return app(WhatsappManagerInterface::class);
    }

    public static function fake(string $id = 'test-id', string $token = 'test-token', string $host = 'https://test.com'): void
    {
        $fakeGreenApiClient = new GreenApiClient($id, $token, $host);
        app()->instance(GreenApiClient::class, $fakeGreenApiClient);

        // fake instance service
        $fakeInstanceService = Mockery::mock(InstanceServiceInterface::class);
        $fakeInstanceService->shouldReceive('create')
            ->andReturn(new CreatedInstanceDTO($id, $token, 'whatsapp'));

        // fake qr code service
        $fakeQRCodeService = Mockery::mock(QRCodeServiceInterface::class);
        $fakeQRCodeService->shouldReceive('getQRCode')
            ->andReturn(new QRCodeResponseDTO("qrCode", "test-qr-base64-code"));

        // fake client api service
        $fakeClientService = Mockery::mock(WhatsappApiServiceInterface::class);
        $fakeClientService->shouldReceive('getClient')
            ->andReturn($fakeGreenApiClient);

        // fake messaging service
        $fakeMessagingService = Mockery::mock(WhatsappMessagingInterface::class);
        $fakeMessagingService->shouldReceive('send')
            ->andReturn(new Response(new EventType('new message'), new MessageId('test-id')));

        // fake messaging service
        $fakeInstanceStatus = Mockery::mock(GetInstanceStatusServiceInterface::class);
        $fakeInstanceStatus->shouldReceive('get')
            ->andReturn(InstanceStatus::AUTHORIZED);

        $fakeManager = new WhatsappManager(
            $fakeClientService,
            $fakeQRCodeService,
            $fakeInstanceService,
            $fakeInstanceStatus,
            $fakeMessagingService
        );

        app()->instance(WhatsappManagerInterface::class, $fakeManager);
    }
}