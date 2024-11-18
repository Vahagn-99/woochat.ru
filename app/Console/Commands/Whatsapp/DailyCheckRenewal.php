<?php

namespace App\Console\Commands\Whatsapp;

use App\Enums\InstanceStatus;
use App\Models\User as UserModel;
use App\Models\WhatsappInstance;
use Illuminate\Console\Command;

class DailyCheckRenewal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:daily-check-renewal';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check daily user subscription renewal';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        /** @var \Illuminate\Database\Eloquent\Collection<\App\Models\User> $users */
        $users = UserModel::query()->get();

        foreach ($users as $user) {
            $subscription = $user->activeSubscription;

            if (! isset($subscription) || $subscription->expired_at->isPast()) {
                $user->whatsappInstances?->each(function (WhatsappInstance $instance) {
                    $instance->status = InstanceStatus::BLOCKED;
                    $instance->blocked_at = now();

                    $instance->save();
                });

                $subscription?->archive();
            }
        }

        do_log('crones/subscription-daily-renewal')->info("Подписки пользователей были проверены на истечение.");
    }
}
