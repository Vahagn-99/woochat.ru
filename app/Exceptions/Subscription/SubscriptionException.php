<?php

namespace App\Exceptions\Subscription;

use App\Exceptions\ReportableException;
use App\Models\User;
use Exception;

class SubscriptionException extends Exception implements ReportableException
{
    public function __construct(string $message)
    {
        parent::__construct($message, 412);
    }

    /**
     * @throws \App\Exceptions\Subscription\SubscriptionException
     */
    public static function alreadyHasTrial(User $user): string
    {
        throw new self("Пользователь с доменом: $user->id уже имел или имеет в текущий момент пробную подписку.");
    }

    /**
     * @throws \App\Exceptions\Subscription\SubscriptionException
     */
    public static function hasActive(User $user): string
    {
        throw new self("Пользователь с доменом: $user->id уже имеет в текущий момент подписку.");
    }

    public function report(): bool
    {
        do_log("subscription")->error($this->getMessage());

        return false;
    }
}
