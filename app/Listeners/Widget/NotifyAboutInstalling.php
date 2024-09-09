<?php

namespace App\Listeners\Widget;

use AmoCRM\Collections\Leads\LeadsCollection;
use AmoCRM\Collections\LinksCollection;
use AmoCRM\Collections\TagsCollection;
use AmoCRM\Enum\Tags\TagColorsEnum;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMMissedTokenException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Exceptions\InvalidArgumentException;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Models\ContactModel;
use AmoCRM\Models\CustomFieldsValues\DateCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\MultitextCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\NumericCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\SelectCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\DateCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\MultitextCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\NumericCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\SelectCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueModels\DateCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\MultitextCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\NumericCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\SelectCustomFieldValueModel;
use AmoCRM\Models\LeadModel;
use AmoCRM\Models\TagModel;
use App\DTO\AmoAccountInfoDTO;
use App\DTO\dct\AmoDctDTO;
use App\DTO\dct\TariffDTO;
use App\Enums\InfoType;
use App\Events\Widget\WidgetInstalled;
use App\Models\User;
use App\Services\AmoCRM\Core\Facades\Amo;
use App\Services\AmoCRM\CustomFieldAdapter;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class NotifyAboutInstalling implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct(private readonly CustomFieldAdapter $customFieldAdapter)
    {
    }

    /**
     * Get the name of the listener's queue.
     */
    public function viaQueue(): string
    {
        return 'installation';
    }

    /**
     * @throws \AmoCRM\Exceptions\AmoCRMoAuthApiException
     * @throws \AmoCRM\Exceptions\AmoCRMApiException
     * @throws \AmoCRM\Exceptions\AmoCRMMissedTokenException
     */
    public function handle(WidgetInstalled $event): void
    {
        $user = $event->user;
        $data = $event->amoAccountInfoDTO;
        $config = AmoDctDTO::make();

        Amo::main();

        $this->notify($user, $data, $config);
    }

    /**
     * @throws \AmoCRM\Exceptions\AmoCRMApiException
     * @throws \AmoCRM\Exceptions\AmoCRMoAuthApiException
     * @throws \AmoCRM\Exceptions\AmoCRMMissedTokenException
     */
    public function notify(User $user, AmoAccountInfoDTO $data, AmoDctDTO $config): void
    {
        $mappedCustomFields = $this->mapCustomFields($user, $data, $config);

        $contactCf = $mappedCustomFields['contacts'];
        $leadsCf = $mappedCustomFields['leads'];

        $lead = $this->createLead($user, $leadsCf, $config);
        $contact = $this->addContact($user, $lead, $contactCf, $data, $config);

        $this->attachLeadToContact($lead, $contact);

        $user->info()->delete();
        $user->info()->create([
            'type' => InfoType::AMOCRM,
            'data' => [
                'contact_id' => $contact->getId(),
                'lead_id' => $lead->getId(),
                'data' => $data->toArray(),
            ],
        ]);
    }

    private function mapCustomFields(User $user, AmoAccountInfoDTO $data, AmoDctDTO $config): array
    {
        $customFields = [];

        $userTariff = current(Arr::where($config->tariffs, function (TariffDTO $item) use ($data) {
            return Str::contains($item->name, $data->tariff, true);
        }));

        if ($user->phone) {
            $customFields['contacts'][] = [
                'custom_field_value_model' => new MultitextCustomFieldValueModel(),
                'custom_field_values_model' => new MultitextCustomFieldValuesModel(),
                'custom_field_value_collection' => new MultitextCustomFieldValueCollection(),
                'custom_field_code' => "PHONE",
                'custom_field_enum' => "WORKDD",
                'value' => $user->phone,
            ];
        }

        if ($user->email) {
            $customFields['contacts'][] = [
                'custom_field_value_model' => new MultitextCustomFieldValueModel(),
                'custom_field_values_model' => new MultitextCustomFieldValuesModel(),
                'custom_field_value_collection' => new MultitextCustomFieldValueCollection(),
                'custom_field_code' => "EMAIL",
                'custom_field_enum' => "WORK",
                'value' => $user->email,
            ];
        }

        if ($userTariff) {
            $customFields ['contacts'][] = [
                'id' => $config->contact_cf_id,
                'custom_field_value_model' => new SelectCustomFieldValueModel(),
                'custom_field_values_model' => new SelectCustomFieldValuesModel(),
                'custom_field_value_collection' => new SelectCustomFieldValueCollection(),
                'enum_id' => $userTariff->id,
            ];
        }

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

        if ($data->paid_from) {
            $customFields['contacts'][] = [
                'id' => $config->paid_from_cf_id,
                'value' => $data->paid_from,
                'custom_field_value_model' => new DateCustomFieldValueModel(),
                'custom_field_values_model' => new DateCustomFieldValuesModel(),
                'custom_field_value_collection' => new DateCustomFieldValueCollection(),
            ];
        }

        if ($data->paid_till) {
            $customFields['leads'][] = [
                'id' => $config->leads_cf_id,
                'value' => $data->paid_till,
                'custom_field_value_model' => new DateCustomFieldValueModel(),
                'custom_field_values_model' => new DateCustomFieldValuesModel(),
                'custom_field_value_collection' => new DateCustomFieldValueCollection(),
            ];
        }

        return $customFields;
    }

    /**
     * @throws \AmoCRM\Exceptions\InvalidArgumentException
     * @throws \AmoCRM\Exceptions\AmoCRMMissedTokenException
     */
    private function createLead(User $user, array $leadsCf, AmoDctDTO $config): LeadModel
    {
        $model = new LeadModel();
        $model->setStatusId($config->status_id);
        $model->setPipelineId($config->pipeline_id);
        $model->setResponsibleUserId($config->responsible_user_id);
        $model->setName(config('app_name').' ( '." $user->domain, $user->id ".')')->setPrice(0)->setPipelineId($config->pipeline_id)->setStatusId($config->status_id)->setResponsibleUserId($config->responsible_user_id);
        $model->setCustomFieldsValues($this->customFieldAdapter->adapt($leadsCf));
        $model->setTags($this->addTag());

        try {
            $model = Amo::main()->api()->leads()->addOne($model);
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

        $tag = new TagModel();
        $tag->setName("woochat")->setColor(TagColorsEnum::AERO_BLUE);

        try {
            $tag = $api->addOne($tag);
        } catch (AmoCRMMissedTokenException|AmoCRMApiException $e) {
            do_log('amo_notifications/installing/tags')->error($e->getMessage(), [
                'description' => $e->getDescription(),
                'info' => $e->getLastRequestInfo(),
            ]);
            $this->release($e);
        }

        return $collection->add($tag);
    }

    /**
     * @throws \AmoCRM\Exceptions\AmoCRMApiException
     * @throws \AmoCRM\Exceptions\AmoCRMoAuthApiException
     * @throws \AmoCRM\Exceptions\AmoCRMMissedTokenException
     */
    private function addContact(
        User $user,
        LeadModel $lead,
        array $contactCf,
        AmoAccountInfoDTO $data,
        AmoDctDTO $config,
    ): ContactModel {
        $leads = new LeadsCollection;
        $leads->add($lead);

        $contact = new ContactModel();
        $contact->setLeads($leads);
        $contact->setName($data->name);
        $contact->setAccountId($user->id);
        $contact->setResponsibleUserId($config->responsible_user_id);

        $cfs = $this->customFieldAdapter->adapt($contactCf);

        $contact->setCustomFieldsValues($cfs);

        $api = Amo::api()->contacts();

        return $api->addOne($contact);
    }

    /**
     * @throws \AmoCRM\Exceptions\AmoCRMMissedTokenException
     */
    private function attachLeadToContact(LeadModel $lead, ContactModel $contact): void
    {
        $api = Amo::api()->contacts();

        $collectionOfLink = new LinksCollection();
        $collectionOfLink->add($lead);

        try {
            $api->link($contact, $collectionOfLink);
        } catch (AmoCRMoAuthApiException|InvalidArgumentException|AmoCRMApiException $e) {
            do_log('amo_notifications/installing/link-contact-lead')->error($e->getMessage(), [
                'description' => $e->getDescription(),
                'info' => $e->getLastRequestInfo(),
            ]);
            $this->release($e);
        }
    }
}
