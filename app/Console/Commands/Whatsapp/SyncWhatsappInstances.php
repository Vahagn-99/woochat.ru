<?php

namespace App\Console\Commands\Whatsapp;

use App\Enums\InstanceStatus;
use App\Models\WhatsappInstance;
use App\Services\Whatsapp\Facades\Whatsapp;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

class SyncWhatsappInstances extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:instances';

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
        $instances = Whatsapp::instance()->all();

        $syncedInstances = WhatsappInstance::query()->get();

        foreach ($instances as $instance) {
            $exists = WhatsappInstance::query()->where([
                'id' => $instance->id,
                'token' => $instance->token,
            ])->exists();

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
        });

        do_log('crones/sync_instances')->info("The instances was synced. date: ".now()->toDateTimeString());
    }
}
