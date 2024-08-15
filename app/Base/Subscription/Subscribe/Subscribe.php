<?php

declare(strict_types=1);

namespace App\Base\Subscription\Subscribe;

use App\Base\Subscription\Subscribe\Full\Activate as FullActivate;
use App\Base\Subscription\Subscribe\Full\MakePayment as FullMakePayment;
use App\Base\Subscription\Subscribe\Trial\Activate as TrialActivate;
use App\Base\Subscription\Subscribe\Trial\CheckAvailable as TrialCheckAvailable;

final class Subscribe
{
    /**
     * Пробная подписка на сервис.
     *
     * @param \App\Base\Subscription\Subscribe\OperationDto $operation
     * @return \App\Base\Subscription\Subscribe\ResultDto
     */
    public static function trial(OperationDto $operation) : ResultDto
    {
        $check_available = app(TrialCheckAvailable::class);
        $activate = app(TrialActivate::class);

        return $check_available
            ->setNext($activate)
            ->execute($operation);
    }

    /**
     * Полная подписка на сервис.
     *
     * @param \App\Base\Subscription\Subscribe\OperationDto $operation
     * @return \App\Base\Subscription\Subscribe\ResultDto
     */
    public static function full(OperationDto $operation) : ResultDto
    {
        if(! empty($operation->is_paid)) {
            return app(FullActivate::class)
                ->execute($operation);
        }

        return app(FullMakePayment::class)
            ->execute($operation);
    }
}
