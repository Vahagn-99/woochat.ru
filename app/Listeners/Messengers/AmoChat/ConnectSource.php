<?php

namespace App\Listeners\Messengers\AmoChat;

use AmoCRM\Collections\Sources\SourceServicesCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Filters\SourcesFilter;
use AmoCRM\Models\SourceModel;
use App\Events\Messengers\Whatsapp\SettingsSaved;
use App\Models\Settings;
use App\Models\User;
use App\Services\AmoCRM\Core\Facades\Amo;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Throwable;

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

        $new = new SourceModel();
        $new->setName($settings->name);
        $new->setPipelineId($settings->pipeline_id);
        $new->setExternalId($settings->instance_id);
        $new->setServices(SourceServicesCollection::fromArray([
            [
                "type" => "whatsapp",
                "params" => [
                    "waba" => false,
                    "is_supports_list_message" => false
                ],
                "pages" => [
                    [
                        "id" => $settings->instance->id,
                        "name" => "Woochat[".$settings->name."]"." ".$settings->instance->clearPhone(),
                        "link" => $settings->instance->clearPhone()
                    ]
                ]
            ]
        ]));

        try {
            $api = Amo::domain($user->domain)->api()->sources();

            try {
                $source = $api->get((new SourcesFilter())->setExternalIds([(string) $settings->instance_id]))->first();

                $new->setId($source->getId());

                $source = $api->updateOne($new);
            } catch (Throwable) {
                $source = $api->addOne($new);
            }

            $settings->source_id = $source->getId();
        } catch (AmoCRMApiException|AmoCRMoAuthApiException $e) {
            do_log('amochat_sources')->error($e->getMessage(), [
                'data' => $e->getLastRequestInfo(),
            ]);

            $this->release();
        }

        return $source;
    }
}
