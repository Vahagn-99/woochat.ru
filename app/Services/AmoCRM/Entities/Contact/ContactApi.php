<?php

namespace App\Services\AmoCRM\Entities\Contact;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Collections\ContactsCollection;
use AmoCRM\EntitiesServices\Contacts;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMMissedTokenException;
use AmoCRM\Filters\ContactsFilter;
use AmoCRM\Models\ContactModel;

readonly class ContactApi implements ContactApiInterface
{
    public function __construct(private AmoCRMApiClient $apiClient)
    {
    }

    /**
     * @throws AmoCRMMissedTokenException
     */
    private function endpoint(): Contacts
    {
        return $this->apiClient->contacts();
    }

    public function get(ContactsFilter $filter = null, $with = []): ContactsCollection
    {
        try {
            return $this->endpoint()->get($filter, $with);
        } catch (AmoCRMApiException $e) {
            dd([
                'error' => $e->getMessage(),
                'info' => $e->getLastRequestInfo(),
                'desc' => $e->getDescription()
            ]);
        }
    }

    public function getOne(int $id, $with = []): ContactModel
    {
        try {
            return $this->endpoint()->getOne($id, $with);
        } catch (AmoCRMApiException $e) {
            dd([
                'error' => $e->getMessage(),
                'info' => $e->getLastRequestInfo(),
                'desc' => $e->getDescription()
            ]);
        }
    }
}
