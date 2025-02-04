<?php

namespace App\Services\Whatsapp\Instance;

use App\Enums\InstanceStatus;

interface GetInstanceStatusServiceInterface
{
    public function get(): InstanceStatus;
}