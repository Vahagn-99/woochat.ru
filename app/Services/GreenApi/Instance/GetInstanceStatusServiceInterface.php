<?php

namespace App\Services\GreenApi\Instance;

use App\Enums\InstanceStatus;

interface GetInstanceStatusServiceInterface
{
    public function get(): InstanceStatus;
}