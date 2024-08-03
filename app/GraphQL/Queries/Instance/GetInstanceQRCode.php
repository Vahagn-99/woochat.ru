<?php declare(strict_types=1);

namespace App\GraphQL\Queries\Instance;

use App\Models\Instance;
use App\Services\GreenApi\DTO\InstanceDTO;
use App\Services\GreenApi\Facades\Greenapi;
use App\Services\GreenApi\QRCode\QRCodeManagerInterface;

final readonly class GetInstanceQRCode
{
    /** @param array{} $args */
    public function __invoke(null $_, array $args): array
    {
        $instance = Instance::query()->findOrFail($args['id']);
        Greenapi::for(new InstanceDTO($instance->id, $instance->token));
        /** @var QRCodeManagerInterface $qrCodeManager */
        $qrCodeManager = app(QRCodeManagerInterface::class);
        $response = $qrCodeManager->getQRCode();

        return $response->toArray();
    }
}
