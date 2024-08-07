<?php

namespace App\Services\Whatsapp\Instance;

use App\Enums\InstanceStatus;
use GreenApi\RestApi\GreenApiClient;

class GetInstanceStatusService implements GetInstanceStatusServiceInterface
{
    public function __construct(private readonly GreenApiClient $client)
    {
    }

    public function get(): InstanceStatus
    {
        return InstanceStatus::tryFrom($this->client->account->getStateInstance()?->data?->stateInstance ?? 'starting');
    }
}