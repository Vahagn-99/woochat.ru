<?php

namespace App\Services\GreenApi\Facades;

use App\Enums\InstanceStatus;
use App\Models\Instance;
use App\Services\GreenApi\ClientService\GreenApiServiceInterface;
use App\Services\GreenApi\DTO\InstanceDTO;
use App\Services\GreenApi\GreenManager;
use App\Services\GreenApi\GreenManagerInterface;
use App\Services\GreenApi\Instance\CreatedInstanceDTO;
use App\Services\GreenApi\Instance\InstanceServiceInterface;
use App\Services\GreenApi\Messaging\MessagingServiceInterface;
use App\Services\GreenApi\QRCode\QRCodeResponseDTO;
use App\Services\GreenApi\QRCode\QRCodeServiceInterface;
use GreenApi\RestApi\GreenApiClient;
use Illuminate\Support\Facades\Facade;
use Mockery;

/**
 * @method static GreenApiServiceInterface api()
 * @method static QRCodeServiceInterface qr()
 * @method static InstanceServiceInterface instance()
 * @method static InstanceStatus status()
 * @method static MessagingServiceInterface massaging()
 *
 * @see GreenManagerInterface
 */
class GreenApi extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'green-api';
    }

    public static function fromModel(Instance $instance): GreenManagerInterface
    {

        $client = new GreenApiClient(
            $instance->id,
            $instance->token
        );

        app()->instance(GreenApiClient::class, $client);

        return app(GreenManagerInterface::class);
    }

    public static function fromDTO(InstanceDTO $instance): GreenManagerInterface
    {

        $client = new GreenApiClient(
            $instance->id,
            $instance->token
        );

        app()->instance(GreenApiClient::class, $client);

        return app(GreenManagerInterface::class);
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
            ->andReturn(new QRCodeResponseDTO("qrCode", "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"));

        // fake client api service
        $fakeClientService = Mockery::mock(GreenApiServiceInterface::class);
        $fakeClientService->shouldReceive('getClient')
            ->andReturn($fakeGreenApiClient);

        $fakeManager = new GreenManager(
            $fakeClientService,
            $fakeQRCodeService,
            $fakeInstanceService,
        );

        app()->instance(GreenManagerInterface::class, $fakeManager);
    }
}