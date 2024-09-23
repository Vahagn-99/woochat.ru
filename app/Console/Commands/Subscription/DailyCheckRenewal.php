<?php

namespace App\Console\Commands\Subscription;

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
    protected $signature = 'subscription:daily_check_renewal';

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
        $users = UserModel::query()->withWhereHas('subscriptions')->get();

        foreach ($users as $user) {
            $subscription = $user->activeSubscription;

            if ($subscription->expired_at->isPast()) {
                $user->instances?->each(function (WhatsappInstance $instance) {
                    $instance->user_id = null;
                    $instance->status = InstanceStatus::NOT_AUTHORIZED;
                    $instance->phone = null;
                    $instance->settings->delete();
                    $instance->save();
                });

                $subscription->archive();
            }
        }

        do_log('crones/subscription-daily-renewal')->info("Подписки пользователей были проверены на истечение.");
    }
}
