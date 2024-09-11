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
            //elseif (! $exists->status->isAuthorized() && $exists->created_at->lessThan(now()->subHours(12))) {
            //    $exists->phone = null;
            //    $exists->settings()->delete();
            //
            //    if ($exists->user_id) {
            //        do_log('crones/instances')->warning("Инстанс {$exists->id} откреплень от клиента {$exists->user_id}");
            //        $exists->user_id = null;
            //    }
            //
            //    $exists->save();
            //}
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
