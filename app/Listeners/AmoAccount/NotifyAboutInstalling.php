<?php

namespace App\Listeners\AmoAccount;

use AmoCRM\Collections\Leads\LeadsCollection;
use AmoCRM\Collections\TagsCollection;
use AmoCRM\Enum\Tags\TagColorsEnum;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMMissedTokenException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Filters\TagsFilter;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Models\ContactModel;
use AmoCRM\Models\CustomFieldsValues\DateCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\NumericCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\SelectCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\DateCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\NumericCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\SelectCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueModels\DateCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\NumericCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\SelectCustomFieldValueModel;
use AmoCRM\Models\LeadModel;
use AmoCRM\Models\TagModel;
use App\DTO\AmoAccountInfoDTO;
use App\DTO\dct\AmoDctDTO;
use App\DTO\dct\TariffDTO;
use App\Enums\InfoType;
use App\Events\AmoCRM\WidgetInstalled;
use App\Models\User as Subdomain;
use App\Services\AmoCRM\Core\Facades\Amo;
use App\Services\AmoCRM\CustomFieldAdapter;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Arr;

class NotifyAboutInstalling implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct(private readonly CustomFieldAdapter $customFieldAdapter)
    {
    }

    /**
     * @throws \AmoCRM\Exceptions\AmoCRMoAuthApiException
     * @throws \AmoCRM\Exceptions\AmoCRMApiException
     * @throws \AmoCRM\Exceptions\AmoCRMMissedTokenException
     */
    public function handle(WidgetInstalled $event): void
    {
        $domain = $event->user;
        $data = $event->amoAccountInfoDTO;
        $config = AmoDctDTO::make();

        Amo::main();

        $this->notify($domain, $data, $config);
    }

    /**
     * @throws \AmoCRM\Exceptions\AmoCRMApiException
     * @throws \AmoCRM\Exceptions\AmoCRMoAuthApiException
     * @throws \AmoCRM\Exceptions\AmoCRMMissedTokenException
     */
    public function notify(Subdomain $user, AmoAccountInfoDTO $data, AmoDctDTO $config): void
    {
        $mappedCustomFields = $this->mapCustomFields($user, $data, $config);

        $contactCf = $mappedCustomFields['contacts'];
        $leadsCf = $mappedCustomFields['leads'];

        $lead = $this->createLead($user, $leadsCf, $config);
        $contact = $this->addUser($user, $lead, $contactCf, $data);

        $user->info()->delete();
        $user->info()->create([
            'type' => InfoType::AMOCRM,
            'payload' => [
                'contact_id' => $contact->getId(),
                'lead_id' => $lead->getId(),
                'data' => $data->toArray(),
            ],
        ]);
    }

    private function mapCustomFields(Subdomain $user, AmoAccountInfoDTO $data, AmoDctDTO $config): array
    {
        $customFields = [];

        $userTariff = current(Arr::where($config->tariffs, function (TariffDTO $item) use ($data) {
            return in_array($item->name, explode(' ', $data->tariff));
        }));

        $customFields ['contacts'][] = [
            'id' => $config->contact_cf_id,
            'custom_field_value_model' => new SelectCustomFieldValueModel(),
            'custom_field_values_model' => new SelectCustomFieldValuesModel(),
            'custom_field_value_collection' => new SelectCustomFieldValueCollection(),
            'enum_id' => key($userTariff),
        ];

        $customFields['contacts'][] = [
            'id' => $config->amocrm_id_cf_id,
            'value' => $user->id,
            'custom_field_value_model' => new NumericCustomFieldValueModel(),
            'custom_field_values_model' => new NumericCustomFieldValuesModel(),
            'custom_field_value_collection' => new NumericCustomFieldValueCollection(),
        ];

        $customFields['contacts'][] = [
            'id' => $config->user_count_cf_id,
            'value' => (string) $data->users_count,
            'custom_field_value_model' => new NumericCustomFieldValueModel(),
            'custom_field_values_model' => new NumericCustomFieldValuesModel(),
            'custom_field_value_collection' => new NumericCustomFieldValueCollection(),
        ];

        $customFields['contacts'][] = [
            'id' => $config->paid_till_cf_id,
            'value' => $data->paid_till,
            'custom_field_value_model' => new DateCustomFieldValueModel(),
            'custom_field_values_model' => new DateCustomFieldValuesModel(),
            'custom_field_value_collection' => new DateCustomFieldValueCollection(),
        ];

        $customFields['leads'][] = [
            'id' => $config->leads_cf_id,
            'value' => $data->paid_till,
            'custom_field_value_model' => new DateCustomFieldValueModel(),
            'custom_field_values_model' => new DateCustomFieldValuesModel(),
            'custom_field_value_collection' => new DateCustomFieldValueCollection(),
        ];

        return $customFields;
    }

    private function createLead(Subdomain $user, array $leadsCf, AmoDctDTO $config): LeadModel
    {

        $model = new LeadModel();

        $model->setName(config('app_name').' ( '." $user->domain, $user->id ".')')->setPrice(0)->setPipelineId($config->pipeline_id)->setStatusId($config->status_id)->setResponsibleUserId($config->responsible_user_id);
        //$model->setTags($this->addTag());
        $cfs = $this->customFieldAdapter->adapt($leadsCf);
        $model->setCustomFieldsValues($cfs);

        try {
            return Amo::api()->leads()->addOne($model);
        } catch (AmoCRMMissedTokenException|AmoCRMoAuthApiException|AmoCRMApiException $e) {
            do_log('amo_notifications/installing')->error("Can't notify {$user->id}", [
                'description' => $e->getDescription(),
                'code' => $e->getCode(),
            ]);
            $this->release($e);
        }

        return $model;
    }

    /**
     * @throws \AmoCRM\Exceptions\InvalidArgumentException
     * @throws \AmoCRM\Exceptions\AmoCRMMissedTokenException
     */
    private function addTag(): TagsCollection
    {
        $api = Amo::api()->tags(EntityTypesInterface::LEADS);
        $collection = new TagsCollection();

        try {
            $tag = new TagModel();
            $tag->setName("woochat")->setColor(TagColorsEnum::AERO_BLUE);

            $filter = new TagsFilter;
            $filter->setName("woochat");
            $collection = $api->get($filter);

            if (! $collection->count()) {
                $tag = $api->addOne($tag);
                $collection->add($tag);
            }
        } catch (AmoCRMMissedTokenException|AmoCRMApiException $e) {
            do_log('amo_notifications/installing/tags')->error($e->getMessage(), [
                'description' => $e->getDescription(),
                'info' => $e->getLastRequestInfo(),
            ]);
            //$this->release($e);
        }

        return $collection;
    }

    /**
     * @throws \AmoCRM\Exceptions\AmoCRMApiException
     * @throws \AmoCRM\Exceptions\AmoCRMoAuthApiException
     * @throws \AmoCRM\Exceptions\AmoCRMMissedTokenException
     */
    private function addUser(
        Subdomain $user,
        LeadModel $lead,
        array $contactCf,
        AmoAccountInfoDTO $data
    ): ContactModel {

        $contact = new ContactModel();
        $contact->setLeads(LeadsCollection::make([$lead]));
        $contact->setName($data->name);
        $contact->setAccountId($user->id);

        $cfs = $this->customFieldAdapter->adapt($contactCf);

        $contact->setCustomFieldsValues($cfs);

        $api = Amo::api()->contacts();

        return $api->addOne($contact);
    }
}
