<?php

namespace App\Console\Commands\Subscription;

use App\Enums\InstanceStatus;
use App\Models\User as UserModel;
use App\Models\WhatsappInstance;
use App\Services\Whatsapp\Facades\Whatsapp;
use Illuminate\Console\Command;

class DailyCheckBLockedInstances extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'instance:daily_check_blocked';

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
        $instances = WhatsappInstance::whereBlocked()->get();

        foreach ($instances as $instance) {
            if ($instance->blocked_at->isBefore(now()->subDays(3))) {
                $instance->status = InstanceStatus::NOT_AUTHORIZED;
                $instance->save();
            }
        }

        do_log('crones/blocked-instances-daily-renewal')->info("Заблокированные инстансы были проверены на предмет восстановление.");
    }
}
