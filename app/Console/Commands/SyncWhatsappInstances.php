<?php

namespace App\Console\Commands;

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
            WhatsappInstance::query()->updateOrCreate([
                'id' => $instance->id,
                'token' => $instance->token,
            ]);
        }
    }
}
