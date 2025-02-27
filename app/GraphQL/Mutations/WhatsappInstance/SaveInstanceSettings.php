<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations\WhatsappInstance;

use App\Events\Messengers\Whatsapp\SettingsSaved;
use App\Models\WhatsappInstance;

final readonly class SaveInstanceSettings
{
    /** @param array{} $args */
    public function __invoke(null $_, array $args): WhatsappInstance
    {
        /** @var WhatsappInstance $instance */
        $instance = WhatsappInstance::query()->find($args['input']['instance_id']);

        $settings = $instance->settings()->updateOrCreate([
            'instance_id' => $args['input']['instance_id'],
        ], [
            'pipeline_id' => $args['input']['pipeline_id'],
            'name' => $args['input']['name'],
        ]);

        SettingsSaved::dispatch($instance, $settings);

        return $instance;
    }
}
