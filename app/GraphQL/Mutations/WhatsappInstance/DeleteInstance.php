<?php declare(strict_types=1);

namespace App\GraphQL\Mutations\WhatsappInstance;

use App\Events\Messengers\Whatsapp\InstanceDetached;
use App\Models\WhatsappInstance;
use App\Services\Whatsapp\Facades\Whatsapp;

final readonly class DeleteInstance
{
    /** @param array{} $args */
    public function __invoke(null $_, array $args): bool
    {
        $instance = WhatsappInstance::with('settings')->find($args['id']);
        Whatsapp::for($instance)->api()->getClient()->account->logout();

        $instance->user_id = null;
        $instance->save();

        InstanceDetached::dispatchIf((bool) $instance->settings, $instance, auth()->user());

        return true;
    }
}
