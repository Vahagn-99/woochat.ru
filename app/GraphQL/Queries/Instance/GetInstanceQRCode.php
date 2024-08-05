<?php declare(strict_types=1);

namespace App\GraphQL\Queries\Instance;

use App\Models\Instance;
use App\Services\GreenApi\DTO\InstanceDTO;
use App\Services\GreenApi\Facades\GreenApi;
use App\Services\GreenApi\QRCode\QRCodeServiceInterface;

final readonly class GetInstanceQRCode
{
    /** @param array{} $args */
    public function __invoke(null $_, array $args): array
    {
        $instance = Instance::query()->findOrFail($args['id']);
        GreenApi::fromModel($instance);
        GreenApi::api()->getClient()->account->logout();
        $response = GreenApi::qr()->getQRCode();

        return $response->toArray();
    }
}
