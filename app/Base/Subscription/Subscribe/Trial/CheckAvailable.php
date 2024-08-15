<?php

declare(strict_types=1);

namespace App\Base\Subscription\Subscribe\Trial;

use App\Base\Subscription\Subscribe\Action;
use App\Base\Subscription\Subscribe\OperationDto;
use App\Base\Subscription\Subscribe\ResultDto;
use App\Base\Subscription\Subscribe\ResultStatus;
use App\Repositories\Subscription as SubscriptionRepository;

final class CheckAvailable extends Action
{
    /**
     * CheckAvailable constructor.
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
    protected function getResult(OperationDto $operation_data) : ResultDto
    {
        if($this->subscriptionRepository->hasByUser($operation_data->user->id)) {
            return ResultDto::from([
                'status' => ResultStatus::FAIL,
                'error_message' => __('api.subscription.trial_can_only_activated_if_there_are_no_other_subscriptions'),
            ]);
        }

        if(empty($operation_data->subscription_plan->trial_duration)) {
            return ResultDto::from([
                'status' => ResultStatus::FAIL,
                'error_message' => __('api.subscription.plan_doesnt_have_trial', ['subscription_plan_name' => $operation_data->subscription_plan->name]),
            ]);
        }

        return ResultDto::from([
            'status' => ResultStatus::SUCCESS,
        ]);
    }
}
