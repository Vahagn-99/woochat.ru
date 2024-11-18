<?php

namespace App\Console\Commands\Whatsapp;

use App\Enums\InstanceStatus;
use App\Models\WhatsappInstance;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class RenewBlocked extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whatsapp:renew-blocked-instances';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check daily user blocked instances';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        /** @var \Illuminate\Database\Eloquent\Collection<\App\Models\WhatsappInstance> $instances */
        $instances = WhatsappInstance::whereBlocked()
            ->where('blocked_at', '<=', Carbon::now()->addHours(6))
            ->get();

        foreach ($instances as $instance) {
            $instance->status = InstanceStatus::NOT_AUTHORIZED;
            $instance->save();
        }

        do_log('crones/blocked-instances-daily-renewal')->info("Заблокированные инстансы были проверены на предмет восстановление.");
    }
}
