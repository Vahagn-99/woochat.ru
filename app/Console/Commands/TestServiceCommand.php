<?php

namespace App\Console\Commands;

use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Filters\ContactsFilter;
use App\Services\AmoCRM\Core\Facades\Amo;
use App\Services\AmoCRM\CustomFieldAdapter;
use Illuminate\Console\Command;

class TestServiceCommand extends Command
{
    protected $signature = 'test:service';

    protected $description = 'The command to test any service';

    /**
     * @throws \Exception
     */
    public function handle(CustomFieldAdapter $adapter): void
    {
        $email_custom_field = [
            '277639' => 'widget.dev@dicitech.com',
        ];

        try {
            $contact = Amo::admin()->api()->contacts()->get(
                (new ContactsFilter())->setCustomFieldsValues($email_custom_field)
            );
            dd($contact);
        } catch (AmoCRMApiException  $exception) {
            dd($exception->getDescription());
        }
    }
}
