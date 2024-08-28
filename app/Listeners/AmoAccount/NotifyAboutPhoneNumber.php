<?php

namespace App\Listeners\AmoAccount;

use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMMissedTokenException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Models\ContactModel;
use AmoCRM\Models\CustomFieldsValues\MultitextCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\MultitextCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueModels\MultitextCustomFieldValueModel;
use App\Events\AmoCRM\PhoneNumberReceived;
use App\Services\AmoCRM\Core\Facades\Amo;
use App\Services\AmoCRM\CustomFieldAdapter;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyAboutPhoneNumber implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct(private readonly CustomFieldAdapter $customFieldAdapter)
    {
    }

    public function handle(PhoneNumberReceived $event): void
    {
        $domain = $event->user;

        $info = $domain->info;
        $data = $info->data;

        try {

            if (! $info) {
                throw new Exception("The user doesn't have a amocrm information");
            }

            $api = Amo::main()->api()->contacts();

            $contact = new ContactModel();

            $contact->setId($data['contact_id']);

            $cfs = $this->customFieldAdapter->adapt([
                [
                    'custom_field_value_model' => new MultitextCustomFieldValueModel(),
                    'custom_field_values_model' => new MultitextCustomFieldValuesModel(),
                    'custom_field_value_collection' => new MultitextCustomFieldValueCollection(),
                    'custom_field_code' => "PHONE",
                    'custom_field_enum' => "WORKDD",
                    'value' => $domain->phone,
                ],
            ]);
            $contact->setCustomFieldsValues($cfs);

            $api->updateOne($contact);
        } catch (AmoCRMoAuthApiException|AmoCRMApiException|AmoCRMMissedTokenException|Exception $e) {
            $this->release($e);
            do_log('amocrm/oauth'.now()->toDateTimeString())->error($e->getMessage());
        }
    }
}
