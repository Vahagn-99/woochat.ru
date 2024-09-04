<?php

namespace App\Listeners\Messengers\AmoChat;

use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Models\SourceModel;
use App\Events\Messengers\Whatsapp\InstanceDetached;
use App\Models\Settings;
use App\Models\User;
use App\Services\AmoCRM\Core\Facades\Amo;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DisconnectSource implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * @param \App\Events\Messengers\Whatsapp\InstanceDetached $event
     */
    public function handle(InstanceDetached $event): void
    {
        $whatsappInstance = $event->instance;
        $settings = $whatsappInstance->settings;
        $user = $event->user;

        $this->deleteSource($user, $settings);
    }

    /**
     * @param \App\Models\User $user
     * @param \App\Models\Settings $settings
     * @return boolean
     */
    private function deleteSource(User $user, Settings $settings): bool
    {
        $source = new SourceModel();
        $source->setId($settings->source_id);

        try {
            $api = Amo::domain($user->domain)->api()->sources();
            $api->deleteOne($source);
            do_log('amocrm/sources')->info("Источник {$source->getId()} от польз. {$user->id} успешно удалень!");
        } catch (AmoCRMApiException|AmoCRMoAuthApiException $e) {
            do_log('amocrm/sources')->error("Не удалось удалить источник {$source->getId()} от польз. {$user->id}", [
                'причина' => $e->getMessage(),
                'data' => $e->getLastRequestInfo(),
            ]);

            $this->release();

            return false;
        }

        return true;
    }
}
