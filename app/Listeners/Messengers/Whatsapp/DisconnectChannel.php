<?php

namespace App\Listeners\Messengers\Whatsapp;

use AmoCRM\Models\SourceModel;
use App\Events\Messaging\UserDeleted;
use App\Services\AmoCRM\Core\Facades\Amo;
use App\Services\Whatsapp\Facades\Whatsapp;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DisconnectChannel implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * @throws \AmoCRM\Exceptions\AmoCRMMissedTokenException
     */
    public function handle(UserDeleted $event): void
    {
        $instances = $event->user->whatsapp_instances;
        $amoApi = Amo::domain($event->user->id)->api()->sources();

        foreach ($instances as $instance) {
            try {
                Whatsapp::for($instance)->instance()->logout();

                $settings = $instance->settings;
                if ($settings->source_id) {
                    $amoApi->deleteOne((new SourceModel())->setId($settings->source_id));
                }

                $instance->delete();

                do_log('whatsapp/instance')->info("Инстнанс {$instance->id} успешно удалень.");
            } catch (Exception $e) {
                do_log('whatsapp/instance')->error("Не удалось отключить инстнанс {$instances->id} .", [
                    'причина' => $e->getMessage(),
                ]);
                continue;
            }
        }
    }
}
