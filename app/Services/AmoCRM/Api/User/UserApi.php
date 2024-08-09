<?php

namespace App\Services\AmoCRM\Api\User;

use AmoCRM\EntitiesServices\Users;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMMissedTokenException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Filters\BaseEntityFilter;
use App\Services\AmoCRM\Core\Facades\Amo;

class UserApi implements UserApiInterface
{
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
        return Amo::api()->users();
    }
}