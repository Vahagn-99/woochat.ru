<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\SubscriptionPlan as SubscriptionPlanModel;

class SubscriptionPlan extends Repository
{
    /**
     * Класс модели репозитория.
     *
     * @return string
     */
    protected function getModelClassName() : string
    {
        return SubscriptionPlanModel::class;
    }
}
