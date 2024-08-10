<?php declare(strict_types=1);

namespace App\GraphQL\Mutations\Instance;

use App\Models\WhatsappInstance;
use App\Services\Whatsapp\Facades\Whatsapp;
use App\Services\Whatsapp\Instance\InstanceServiceInterface;

final readonly class DeleteInstance
{
    /** @param array{} $args */
    public function __invoke(null $_, array $args): bool
    {
        $instance = WhatsappInstance::query()->find($args['id']);
        Whatsapp::for($instance)->api()->getClient()->account->logout();

        return $instance->delete();
    }
}
