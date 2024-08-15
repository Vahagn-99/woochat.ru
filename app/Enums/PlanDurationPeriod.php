<?php

namespace App\Enums;

enum PlanDurationPeriod: string
{
    case DAYS = 'days'; // День
    case MONTHS = 'months'; // Месяц
    case YEARS = 'years'; // Год
}
