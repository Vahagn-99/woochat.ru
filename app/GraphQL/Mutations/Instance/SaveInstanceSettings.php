<?php declare(strict_types=1);

namespace App\GraphQL\Mutations\Instance;

use App\Models\WhatsappInstance;

final readonly class SaveInstanceSettings
{
    /** @param array{} $args */
    public function __invoke(null $_, array $args): WhatsappInstance
    {
        /** @var WhatsappInstance $instance */
        $instance = WhatsappInstance::query()->find($args['input']['instance_id']);
        $instance->settings()->firstOrCreate([
            'instance_id' => $args['input']['instance_id'],
            'pipeline_id' => $args['input']['pipeline_id'],
            'status_id' => $args['input']['status_id'],
        ]);

        return $instance;
    }
}
