<?php

namespace App\Services\AmoCRM\Entities\Lead;

use AmoCRM\Filters\LeadsFilter;

interface LeadApiInterface
{
    public function get(?LeadsFilter $filter = null, array $with = []): array;

    public function getOne(int $id, array $with = []): array;
}