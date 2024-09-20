<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Base\Subscription\SubscriptionStatus as SubscriptionStatus;
use App\Models\Subscription as SubscriptionModel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

class Subscription extends Repository
{
    /**
     * Класс модели репозитория.
     *
     * @return string
     */
    protected function getModelClassName() : string
    {
        return SubscriptionModel::class;
    }

    /**
     * В репозитории имеются записи подписок у пользователя.
     *
     * @param int $user_id
     * @return bool
     */
    public function hasByUser(int $user_id) : bool
    {
        return $this->query()
            ->where('user_id', $user_id)
            ->exists();
    }

    /**
     * Проверка имеется ли активная подписка у пользователя.
     *
     * @param int $user_id
     * @param bool $with_trial
     * @return bool
     */
    public function hasActiveByUser(int $user_id, bool $with_trial = true) : bool
    {
        $query = $this->query()
            ->where('user_id', $user_id);

        $query = match (true) {
            ! empty($with_trial) => $query->whereIn('status', [SubscriptionStatus::ACTIVE->value, SubscriptionStatus::TRIAL->value]),
            default => $query->where('status', SubscriptionStatus::ACTIVE->value)
        };

        return $query->exists();
    }

    /**
     * Получение новейшей активной подписки пользователя.
     *
     * @param int $user_id
     * @param bool $with_trial
     * @return \App\Models\Subscription|null
     */
    public function getLatestActiveByUser(int $user_id, bool $with_trial = true) : ?SubscriptionModel
    {
        $query = $this->query()
            ->selectRaw('*, COALESCE(expire_at, trial_ends_up) as `last_expire_at`')
            ->where('user_id', $user_id);

        $query = match (true) {
            ! empty($with_trial) => $query->whereIn('status', [SubscriptionStatus::ACTIVE->value, SubscriptionStatus::TRIAL->value]),
            default => $query->where('status', SubscriptionStatus::ACTIVE->value)
        };

        return $query
            ->orderByDesc('last_expire_at')
            ->first();
    }

    /**
     * Получение подписок с истекшим пробным периодом но в статусе пробной подписки.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getWithTrialStatusButExpiredTrial() : Collection
    {
        return $this->query()
            ->where([
                'status' => SubscriptionStatus::TRIAL->value,
                ['trial_ends_up', '<', Carbon::now()]
            ])
            ->get();
    }

    /**
     * Получение подписок с истекшим активным периодом но в статусе активной подписки.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getWithActiveStatusButExpired() : Collection
    {
        return $this->query()
            ->where([
                'status' => SubscriptionStatus::ACTIVE->value,
                ['expire_at', '<', Carbon::now()]
            ])
            ->get();
    }
}
