<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations\WhatsappInstance;

use App\Enums\InstanceStatus;
use App\Events\Messengers\Whatsapp\NewInstanceOrdered;
use App\Events\Subscription\Trial as SubscriptionTrialEvent;
use App\Models\WhatsappInstance;
use App\Services\Whatsapp\Facades\Whatsapp;
use Illuminate\Support\Carbon;

final readonly class CreateInstance
{
    /** @param array{} $args
     *
     * @throws \App\Exceptions\Settings\NewInstanceException
     */
    public function __invoke(null $_, array $args): WhatsappInstance
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $user->ensureHasFreeInstanceSlot();

        $model = WhatsappInstance::whereFree()->first();

        $status = InstanceStatus::NOT_AUTHORIZED;
        $name = "Инстанс №".WhatsappInstance::query()->count();

        // if there is no free instance then create one
        if (! $model) {
            $instance = Whatsapp::instance()->create($name);
            $status = InstanceStatus::STARTING;
            $model = WhatsappInstance::query()->create([
                'id' => $instance->id,
                'user_id' => $user->id,
                'token' => $instance->token,
                'status' => $status,
            ]);
        }

        if ($status !== InstanceStatus::STARTING) {
            Whatsapp::for([
                'id' => $model->id,
                'token' => $model->token,
            ])->api()->clearQueue();
        }

        $model->user_id = $user->id;
        $model->save();

        NewInstanceOrdered::dispatchIf(WhatsappInstance::whereFree()->count() <= 1, $name);

        if (! $user->hasFlag('has_already_started_trial_subscription')) {
            SubscriptionTrialEvent::dispatch(
                auth()->user(),
                Carbon::now()->addDays(4)
            );

            $user->flag('has_already_started_trial_subscription');
        }

        return $model;
    }
}
