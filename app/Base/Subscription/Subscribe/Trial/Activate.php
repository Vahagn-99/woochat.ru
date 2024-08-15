<?php

declare(strict_types=1);

namespace App\Base\Subscription\Subscribe\Trial;

use App\Base\Subscription\Subscribe\Action;
use App\Base\Subscription\Subscribe\OperationDto;
use App\Base\Subscription\Subscribe\ResultDto;
use App\Base\Subscription\Subscribe\ResultStatus;
use App\Enums\SubscriptionStatus as SubscriptionStatus;
use App\Models\Subscription as SubscriptionModel;
use Illuminate\Support\Carbon;

final class Activate extends Action
{
    /**
     * Выполнение операции и получение результата.
     *
     * @param \App\Base\Subscription\Subscribe\OperationDto $operationData
     * @return \App\Base\Subscription\Subscribe\ResultDto
     */
    protected function getResult(OperationDto $operationData) : ResultDto
    {
        $subscription = new SubscriptionModel();

        $subscription->user_id = $operationData->user->id;
        $subscription->subscription_plan_id = $operationData->subscription_plan->id;
        $subscription->status = SubscriptionStatus::TRIAL;
        $subscription->trial_ends_up = Carbon::now()->addDays($operationData->subscription_plan->trial_duration);

        $subscription->save();


        return ResultDto::from([
            'status' => ResultStatus::SUCCESS,
            'subscription' => $subscription,
        ]);
    }
}
