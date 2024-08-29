<?php declare(strict_types=1);

namespace App\GraphQL\Mutations\WhatsappInstance;

use App\Models\WhatsappInstance;
use App\Services\Whatsapp\Facades\Whatsapp;

final readonly class DeleteInstance
{
    /** @param array{} $args */
    public function __invoke(null $_, array $args): bool
    {
        $instance = WhatsappInstance::query()->find($args['id']);
        Whatsapp::for($instance)->api()->getClient()->account->logout();

        $instance->user_id = null;

        return true;
    }
}
