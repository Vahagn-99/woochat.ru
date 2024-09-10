<?php

namespace App\Listeners\Messengers\AmoChat;

use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Models\SourceModel;
use App\Events\Messengers\Whatsapp\SettingsSaved;
use App\Models\Settings;
use App\Models\User;
use App\Services\AmoCRM\Core\Facades\Amo;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ConnectSource implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * @param \App\Events\Messengers\Whatsapp\SettingsSaved $event
     */
    public function handle(SettingsSaved $event): void
    {
        $whatsappInstance = $event->instance;
        $settings = $whatsappInstance->settings;
        $user = $whatsappInstance->user;

        $source = $this->updateOrCreateSource($user, $settings);
        $settings->source_id = $source->getId();
        $settings->save();
    }

    /**
     * @param \App\Models\User $user
     * @param \App\Models\Settings $settings
     * @return \AmoCRM\Models\SourceModel
     */
    private function updateOrCreateSource(
        User $user,
        Settings $settings
    ): SourceModel {

        $source = new SourceModel();
        $source->setName($settings->name);
        $source->setPipelineId($settings->pipeline_id);
        $source->setExternalId($settings->instance_id);

        try {
            $api = Amo::domain($user->domain)->api()->sources();

            if ($settings->source_id && $api->getOne($settings->source_id)) {
                $source->setId($settings->source_id);

                $source = $api->updateOne($source);
            } else {
                $source = $api->addOne($source);
            }
        } catch (AmoCRMApiException|AmoCRMoAuthApiException $e) {
            do_log('amochat/sources')->error($e->getMessage(), [
                'data' => $e->getLastRequestInfo(),
            ]);

            $this->release();
        }

        return $source;
    }
}
