<?php

namespace App\Services\AmoCRM\Entities\Lead;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\EntitiesServices\Leads;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMMissedTokenException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Filters\LeadsFilter;

readonly class LeadApi implements LeadApiInterface
{
    public function __construct(private AmoCRMApiClient $apiClient)
    {
    }

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
        return $this->apiClient->leads();
    }
}