<?php

namespace App\Console\Commands\Whatsapp;

use App\Enums\InstanceStatus;
use App\Models\User as UserModel;
use App\Models\WhatsappInstance;
use Illuminate\Console\Command;

class BlockUnsubscribed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whatsapp:block-unsubscribed-instances';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Заблокировать инстансы без подписки';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        /** @var \Illuminate\Database\Eloquent\Collection<\App\Models\User> $users */
        $users = UserModel::query()->get();

        foreach ($users as $user) {
            $subscription = $user->active_subscription;

            if (! isset($subscription) || $subscription->expired_at->isPast()) {
                $user->whatsapp_instances?->each(function (WhatsappInstance $instance) use ($user, $subscription) {
                    $instance->status = InstanceStatus::BLOCKED;
                    $instance->blocked_at = now();

                    $instance->save();

                    do_log('crones/block-unsubscription-daily')->notice("Инстанс {$instance->id} заблокирован так-как у пользователя ID:{$user->id} подписка истекла в {$subscription->expired_at->toDateTimeString()}.");
                });

                $subscription?->archive();
            }
        }

        do_log('crones/block-unsubscription-daily')->info("Инстансы пользователей проверены на заблокирование.");
    }
}
