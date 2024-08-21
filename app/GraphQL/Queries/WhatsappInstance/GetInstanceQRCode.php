<?php declare(strict_types=1);

namespace App\GraphQL\Queries\WhatsappInstance;

use App\Models\WhatsappInstance;
use App\Services\Whatsapp\Facades\Whatsapp;

final readonly class GetInstanceQRCode
{
    /** @param array{} $args */
    public function __invoke(null $_, array $args): array
    {
        $instance = WhatsappInstance::query()->findOrFail($args['id']);

        Whatsapp::for($instance);

        if ($instance->status->isAuthorized()) {
            Whatsapp::instance()->logout();
            sleep(30);
        }

        return Whatsapp::qr()->getQRCode()->toArray();
    }
}
