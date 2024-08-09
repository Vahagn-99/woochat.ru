<?php

namespace App\Services\AmoCRM\Api\Contact;

use AmoCRM\Collections\ContactsCollection;
use AmoCRM\Filters\ContactsFilter;
use AmoCRM\Models\ContactModel;

interface ContactApiInterface
{

    public function get(ContactsFilter $filter = null, $with = []): ContactsCollection;

    public function getOne(int $id, $with = []): ContactModel;
}
