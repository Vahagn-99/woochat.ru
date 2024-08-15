<?php

namespace App\Services\AmoCRM\Entities\User;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\EntitiesServices\Users;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMMissedTokenException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Filters\BaseEntityFilter;

readonly class UserApi implements UserApiInterface
{
    public function __construct(private AmoCRMApiClient $apiClient)
    {
    }

    /**
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     * @throws AmoCRMMissedTokenException
     */
    public function getOne(int $id): array
    {
        return $this->endpoint()->getOne($id, ['group'])->toArray();
    }

    /**
     * @throws AmoCRMoAuthApiException
     * @throws AmoCRMApiException
     * @throws AmoCRMMissedTokenException
     */
    public function get(BaseEntityFilter|null $filter = null, array $with = ['group']): array
    {
        return $this->endpoint()->get($filter, $with)->toArray();
    }

    /**
     * @throws AmoCRMMissedTokenException
     */
    private function endpoint(): Users
    {
        return $this->apiClient->users();
    }
}