<?php

namespace App\Services\AmoCRM\Api\Contact;

use AmoCRM\Collections\ContactsCollection;
use AmoCRM\EntitiesServices\Contacts;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMMissedTokenException;
use AmoCRM\Filters\ContactsFilter;
use AmoCRM\Models\ContactModel;
use App\Services\AmoCRM\Core\Facades\Amo;

class ContactApi implements ContactApiInterface
{

    /**
     * @throws AmoCRMMissedTokenException
     */
    private function endpoint(): Contacts
    {
        return Amo::api()->contacts();
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
