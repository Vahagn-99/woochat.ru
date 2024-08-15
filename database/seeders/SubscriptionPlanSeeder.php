<?php

namespace Database\Seeders;

use App\Enums\PlanDurationPeriod;
use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table((new SubscriptionPlan())->getTable())
            ->insertOrIgnore([
                [
                    'name' => 'Ежемесячная подписка',
                    'price' => 290,
                    'duration' => 1,
                    'duration_period' => PlanDurationPeriod::MONTHS->value,
                    'trial_duration' => 0,
                ],
                [
                    'name' => 'Трехмесячная подписка',
                    'price' => 780,
                    'duration' => 3,
                    'duration_period' => PlanDurationPeriod::MONTHS->value,
                    'trial_duration' => 0,
                ],
                [
                    'name' => 'Годовая подписка',
                    'price' => 2290,
                    'duration' => 1,
                    'duration_period' => PlanDurationPeriod::YEARS->value,
                    'trial_duration' => 0,
                ],
            ]);
    }
}
