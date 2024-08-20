<?php

namespace App\Listeners\AmoChat;

use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Models\SourceModel;
use App\Events\Whatsapp\InstanceSettingsSaved;
use App\Models\Settings;
use App\Models\User;
use App\Models\WhatsappInstance;
use App\Services\AmoCRM\Core\Facades\Amo;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SyncMessagingSource implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * @param \App\Events\Whatsapp\InstanceSettingsSaved $event
     */
    public function handle(InstanceSettingsSaved $event): void
    {
        $whatsappInstance = $event->instance;
        $settings = $whatsappInstance->settings;
        $user = $whatsappInstance->user;

        $source = $this->updateOrCreateSource($user, $whatsappInstance, $settings);
        $settings->source_id = $source->getId();
        $settings->save();
    }

    /**
     * @param \App\Models\User $user
     * @param \App\Models\WhatsappInstance $whatsappInstance
     * @param \App\Models\Settings $settings
     * @return \AmoCRM\Models\SourceModel
     */
    private function updateOrCreateSource(
        User $user,
        WhatsappInstance $whatsappInstance,
        Settings $settings
    ): SourceModel {

        $source = new SourceModel();
        $source->setName($whatsappInstance->name);
        $source->setPipelineId($settings->pipeline_id);
        $source->setExternalId($settings->id);

        try {
            $api = Amo::domain($user->domain)->api()->sources();

            if ($settings->source_id) {
                $source->setId($settings->source_id);

                $source = $api->updateOne($source);
            } else {
                $source = $api->addOne($source);
            }
        } catch (AmoCRMApiException|AmoCRMoAuthApiException $e) {
            do_log('amocrm/sources')->error($e->getMessage(), [
                'data' => $e->getLastRequestInfo(),
            ]);

            $this->release($e);
        }

        return $source;
    }
}
