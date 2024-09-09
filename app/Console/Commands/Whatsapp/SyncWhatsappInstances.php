<?php

namespace App\Console\Commands\Whatsapp;

use App\Enums\InstanceStatus;
use App\Models\WhatsappInstance;
use App\Services\Whatsapp\Facades\Whatsapp;
use Illuminate\Console\Command;

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

        do_log('crones/sync_instances')->info("The instances was synced. date: ".now()->toDateTimeString());
    }
}
