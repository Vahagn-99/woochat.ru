<?php

declare(strict_types=1);

namespace App\Base\Subscription\Subscribe\Full;

use App\Enums\SubscriptionStatus;
use Illuminate\Support\Facades\DB;
use App\Base\Subscription\Subscribe\{ResultStatus};
use App\Base\Subscription\Subscribe\Action;
use App\Base\Subscription\Subscribe\OperationDto;
use App\Base\Subscription\Subscribe\ResultDto;
use App\Models\Subscription as SubscriptionModel;
use App\Repositories\Subscription as SubscriptionRepository;
use Illuminate\Support\Carbon;
use Throwable;

final class Activate extends Action
{
    /**
     * Activate constructor.
     *
     * @param \App\Repositories\Subscription $subscriptionRepository
     */
    public function __construct(
        private readonly SubscriptionRepository $subscriptionRepository,
    ) {
        //
    }

    /**
     * Выполнение операции и получение результата.
     *
     * @param \App\Base\Subscription\Subscribe\OperationDto $operation_data
     * @return \App\Base\Subscription\Subscribe\ResultDto
     */
    protected function getResult(OperationDto $operation_data): ResultDto
    {
        DB::beginTransaction();

        try {

            $active_subscription = $this->subscriptionRepository->getLatestActiveByUser($operation_data->user->id);

            $subscription_start = Carbon::now();

            if (! empty($active_subscription)) {
                $subscription_start = $active_subscription->expire_at ?? $active_subscription->trial_ends_up;
            }

            $subscription = new SubscriptionModel();

            $subscription->user_id = $operation_data->user->id;
            $subscription->subscription_plan_id = $operation_data->subscription_plan->id;
            $subscription->status = SubscriptionStatus::ACTIVE;
            $subscription->expire_at = $subscription_start->add($operation_data->subscription_plan->duration, $operation_data->subscription_plan->duration_period->value)->endOfDay();

            $subscription->save();

            DB::commit();
        } catch (Throwable $e) {

            DB::rollBack();

            do_log('error_subscriptions')->error('Произошла ошибка при активации подписки.', [
                'user_id' => $operation_data->user->id,
                'subscription_plan' => $operation_data->subscription_plan->id,
            ]);

            return ResultDto::from([
                'status' => ResultStatus::FAIL,
                'error_message' => __('api.internal_error'),
            ]);
        }

        return ResultDto::from([
            'status' => ResultStatus::SUCCESS,
            'subscription' => $subscription,
        ]);
    }
}
