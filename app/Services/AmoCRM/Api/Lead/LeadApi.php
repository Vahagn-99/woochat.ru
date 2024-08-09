<?php

namespace App\Services\AmoCRM\Api\Lead;

use AmoCRM\EntitiesServices\Leads;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMMissedTokenException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Filters\LeadsFilter;
use App\Services\AmoCRM\Core\Facades\Amo;

class LeadApi implements LeadApiInterface
{
    /**
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     * @throws AmoCRMMissedTokenException
     */
    public function get(?LeadsFilter $filter = null, array $with = []): array
    {
        return $this->endpoint()->get($filter, $with)->toArray();
    }

    /**
     * @throws AmoCRMoAuthApiException
     * @throws AmoCRMApiException
     * @throws AmoCRMMissedTokenException
     */
    public function getOne(int $id, array $with = []): array
    {
        return $this->endpoint()->getOne($id, $with)->toArray();
    }

    /**
     * @throws AmoCRMMissedTokenException
     */
    private function endpoint(): Leads
    {
        return Amo::api()->leads();
    }
}