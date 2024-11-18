<?php

namespace App\Console\Commands\Whatsapp;

use App\Enums\InstanceStatus;
use App\Models\WhatsappInstance;
use App\Services\Whatsapp\Facades\Whatsapp;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

class Sync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whatsapp:sync-instances';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync whatsapp instances';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        // api
        $instances = Whatsapp::instance()->all();

        // DB
        $syncedInstances = WhatsappInstance::query()->get();

        foreach ($instances as $instance) {
            /** @var WhatsappInstance $exists */
            $exists = WhatsappInstance::query()->where([
                'id' => $instance->id,
                'token' => $instance->token,
            ])->first();

            if (! $exists) {
                WhatsappInstance::query()->create([
                    'id' => $instance->id,
                    'token' => $instance->token,
                    'status' => InstanceStatus::NOT_AUTHORIZED,
                ]);
            }
        }

        $deleteForgottenInstances = $syncedInstances->filter(function (WhatsappInstance $instance) use ($instances) {
            return ! in_array($instance->id, Arr::map($instances, fn($item) => $item->id));
        });

        $deleteForgottenInstances->each(function (WhatsappInstance $instance) {
            $instance->delete();
            do_log('crones/sync-instances')->info("Экземпляр {$instance->id} был удалён по причине того, что не использовался долгое время.");
        });

        do_log('crones/sync-instances')->info("Все экземпляры были синхронизированы.");
    }
}
