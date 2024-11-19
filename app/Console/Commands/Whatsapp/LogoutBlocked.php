<?php

namespace App\Console\Commands\Whatsapp;

use App\Enums\InstanceStatus;
use App\Models\WhatsappInstance;
use App\Services\Whatsapp\Instance\InstanceApiInterface;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class LogoutBlocked extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whatsapp:logout-blocked-instances';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Заголинть инстанс если оно было блокировна боле 6 часов';

    /**
     * Execute the console command.
     */
    public function handle(InstanceApiInterface $api): void
    {
        /** @var \Illuminate\Database\Eloquent\Collection<\App\Models\WhatsappInstance> $instances */
        $instances = WhatsappInstance::whereBlocked()
            ->where('blocked_at', '<=', Carbon::now()->addHours(6))
            ->get();

        foreach ($instances as $instance) {
            try {
                $api->setInstance($instance->transformToDto());

                $api->logoutInstance();
            } catch (Exception) {
                do_log('crones/logout-blocked-instances')->info("Заблокированный инстанс {$instance->id} не получилось залогинить.");
            }

            $instance->status = InstanceStatus::NOT_AUTHORIZED;
            $instance->save();
        }

        do_log('crones/logout-blocked-instances')->info("Заблокированные инстансы были проверены на предмет восстановление.");
    }
}
