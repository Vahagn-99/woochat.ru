<?php declare(strict_types=1);

namespace App\GraphQL\Queries\Instance;

use App\Models\Instance;
use App\Services\Whatsapp\DTO\InstanceDTO;
use App\Services\Whatsapp\Facades\Whatsapp;
use App\Services\Whatsapp\QRCode\QRCodeServiceInterface;

final readonly class GetInstanceQRCode
{
    /** @param array{} $args */
    public function __invoke(null $_, array $args): array
    {
        $instance = Instance::query()->findOrFail($args['id']);
        Whatsapp::for($instance);
        Whatsapp::api()->getClient()->account->logout();
        $response = Whatsapp::qr()->getQRCode();

        return $response->toArray();
    }
}
