<?php

namespace App\Listeners\AmoChat;

use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMMissedTokenException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Models\SourceModel;
use App\Events\Whatsapp\InstanceSettingsSaved;
use App\Services\AmoCRM\Core\Facades\Amo;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

/**
 *
 */
class UpdateAmoChatSource implements ShouldQueue
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
        $amoInstance = $user->amoInstance;

        $source = $this->updateOrCreate($user, $settings);

        $settings->source_id = $source->getId();
        $settings->save();
    }

    /**
     * @param $user
     * @param $settings
     * @return \AmoCRM\Models\SourceModel
     */
    private function updateOrCreate($user, $settings,): SourceModel
    {
        try {
            $api = Amo::domain($user->domain)->api()->sources();
        } catch (AmoCRMMissedTokenException $e) {
            do_log('amocrm/sources')->error($e->getMessage(), [
                'data' => $e->getLastRequestInfo(),
            ]);

            $this->release($e);
        }

        $source = new SourceModel();
        $source->setPipelineId($settings->pipeline_id);
        $source->setName($settings->name);

        try {
            if ($settings->source_id) {
                $source->setId($settings->source_id);

                $api->updateOne($source);
            } else {
                $api->addOne($source);
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
