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
use App\Events\Widget\WidgetInstalled;
use App\Models\User;
use App\Services\AmoCRM\Core\Facades\Amo;
use App\Services\AmoCRM\CustomFieldAdapter;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class NotifyAboutInstalling implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct(private readonly CustomFieldAdapter $custom_field_adapter)
    {
    }

    /**
     * Get the name of the listener's queue.
     */
    public function viaQueue(): string
    {
        return 'installation';
    }

    public function handle(WidgetInstalled $event): void
    {
        $user = $event->user;

        $data = $event->amo_account_info_DTO;

        $config = AmoDctDTO::make();

        Amo::main();

        $lead_id = $user->hasNotificationInfo() ? $user->info->data['lead_id'] : null;
        $contact_id = $user->hasNotificationInfo() ? $user->info->data['contact_id'] : null;

        $mapped_notification_fields = $this->mapNotificationFields($user, $data, $config);

        try {
            $lead = $this->createOrUpdateLead($user, $mapped_notification_fields['leads'], $config, $lead_id);
            $contact = $this->createOrUpdateContact($user, $lead, $mapped_notification_fields['contacts'], $data, $config, $contact_id);
            $this->notify($lead, $contact);

            $user->info()->delete();
            $user->info()->create([
                'data' => [
                    'contact_id' => $contact->getId(),
                    'lead_id' => $lead->getId(),
                    'data' => $data->toArray(),
                ],
            ]);
        } catch (AmoCRMMissedTokenException|AmoCRMoAuthApiException|AmoCRMApiException $e) {

            do_log('widget/installing')->error("Админ не получиль уведемления по уставновке виджета для домена {$user->domain}.", [
                'reason' => $e->getMessage(),
                'title' => $e->getTitle(),
                'description' => $e->getDescription(),
                'code' => $e->getCode(),
                'last_request_info' => $e->getLastRequestInfo(),
                'line' => $e->getLine(),
            ]);

            $this->release($e);
        }
    }

    private function mapNotificationFields(User $user, AmoAccountInfoDTO $data, AmoDctDTO $config): array
    {
        $custom_fields = [
            'leads' => [],
            'contacts' => [],
        ];

        $user_tariff = current(Arr::where($config->tariffs, function (TariffDTO $item) use ($data) {
            return Str::contains($item->name, $data->tariff, true);
        }));

        if ($user->phone) {
            $custom_fields['contacts'][] = [
                'custom_field_value_model' => new MultitextCustomFieldValueModel(),
                'custom_field_values_model' => new MultitextCustomFieldValuesModel(),
                'custom_field_value_collection' => new MultitextCustomFieldValueCollection(),
                'custom_field_code' => "PHONE",
                'custom_field_enum' => "WORKDD",
                'value' => $user->phone,
            ];
        }

        if ($user->email) {
            $custom_fields['contacts'][] = [
                'custom_field_value_model' => new MultitextCustomFieldValueModel(),
                'custom_field_values_model' => new MultitextCustomFieldValuesModel(),
                'custom_field_value_collection' => new MultitextCustomFieldValueCollection(),
                'custom_field_code' => "EMAIL",
                'custom_field_enum' => "WORK",
                'value' => $user->email,
            ];
        }

        if ($user_tariff) {
            $custom_fields ['contacts'][] = [
                'id' => $config->contact_cf_id,
                'custom_field_value_model' => new SelectCustomFieldValueModel(),
                'custom_field_values_model' => new SelectCustomFieldValuesModel(),
                'custom_field_value_collection' => new SelectCustomFieldValueCollection(),
                'enum_id' => $user_tariff->id,
            ];
        }

        $custom_fields['contacts'][] = [
            'id' => $config->amocrm_id_cf_id,
            'value' => $user->id,
            'custom_field_value_model' => new NumericCustomFieldValueModel(),
            'custom_field_values_model' => new NumericCustomFieldValuesModel(),
            'custom_field_value_collection' => new NumericCustomFieldValueCollection(),
        ];

        $custom_fields['contacts'][] = [
            'id' => $config->user_count_cf_id,
            'value' => (string) $data->users_count,
            'custom_field_value_model' => new NumericCustomFieldValueModel(),
            'custom_field_values_model' => new NumericCustomFieldValuesModel(),
            'custom_field_value_collection' => new NumericCustomFieldValueCollection(),
        ];

        if ($data->paid_from) {
            $custom_fields['contacts'][] = [
                'id' => $config->paid_from_cf_id,
                'value' => $data->paid_from,
                'custom_field_value_model' => new DateCustomFieldValueModel(),
                'custom_field_values_model' => new DateCustomFieldValuesModel(),
                'custom_field_value_collection' => new DateCustomFieldValueCollection(),
            ];
        }

        if ($data->paid_till) {
            $custom_fields['leads'][] = [
                'id' => $config->leads_cf_id,
                'value' => $data->paid_till,
                'custom_field_value_model' => new DateCustomFieldValueModel(),
                'custom_field_values_model' => new DateCustomFieldValuesModel(),
                'custom_field_value_collection' => new DateCustomFieldValueCollection(),
            ];
        }

        return $custom_fields;
    }

    /**
     * @throws \AmoCRM\Exceptions\AmoCRMApiException
     * @throws \AmoCRM\Exceptions\AmoCRMoAuthApiException
     * @throws \AmoCRM\Exceptions\AmoCRMMissedTokenException
     */
    private function createOrUpdateLead(
        User $user,
        array $custom_fields,
        AmoDctDTO $config,
        ?int $lead_id = null): LeadModel
    {
        $model = new LeadModel();

        $model->setStatusId($config->status_id);
        $model->setPipelineId($config->pipeline_id);
        $model->setResponsibleUserId($config->responsible_user_id);
        $model->setName(config('app_name').' ( '." $user->domain, $user->id ".')')->setPrice(0)->setPipelineId($config->pipeline_id)->setStatusId($config->status_id)->setResponsibleUserId($config->responsible_user_id);
        if (! empty($custom_fields)) {
            $model->setCustomFieldsValues($this->custom_field_adapter->adapt($custom_fields));
        }
        $model->setTags($this->addTag());

        $leadApi = Amo::main()->api()->leads();

        if ($lead_id) {
            try {
                $leadApi->getOne($lead_id);

                $model->setId($lead_id);

                return $leadApi->updateOne($model);
            } catch (Exception) {
                return $leadApi->addOne($model);
            }
        }

        return $leadApi->addOne($model);
    }

    /**
     * @throws InvalidArgumentException
     * @throws AmoCRMMissedTokenException
     * @throws AmoCRMoAuthApiException
     * @throws AmoCRMApiException
     */
    private function addTag(): TagsCollection
    {
        $api = Amo::api()->tags(EntityTypesInterface::LEADS);
        $collection = new TagsCollection();

        $tag = new TagModel();
        $tag->setName("woochat")->setColor(TagColorsEnum::AERO_BLUE);

        $tag = $api->addOne($tag);

        return $collection->add($tag);
    }

    /**
     * @throws \AmoCRM\Exceptions\AmoCRMApiException
     * @throws \AmoCRM\Exceptions\AmoCRMoAuthApiException
     * @throws \AmoCRM\Exceptions\AmoCRMMissedTokenException
     */
    private function createOrUpdateContact(
        User $user,
        LeadModel $lead,
        array $custom_fields,
        AmoAccountInfoDTO $data,
        AmoDctDTO $config,
        ?int $contact_id = null): ContactModel
    {
        $leads = new LeadsCollection;
        $leads->add($lead);

        $model = new ContactModel();
        $model->setLeads($leads);
        $model->setName($data->name);
        $model->setAccountId($user->id);
        $model->setResponsibleUserId($config->responsible_user_id);

        $cfs = $this->custom_field_adapter->adapt($custom_fields);

        $model->setCustomFieldsValues($cfs);

        $contact_api = Amo::api()->contacts();

        if ($contact_id) {
            try {
                $contact_api->getOne($contact_id);

                $model->setId($contact_id);

                return $contact_api->updateOne($model);
            } catch (Exception) {
                return $contact_api->addOne($model);
            }
        }

        return $contact_api->addOne($model);
    }

    /**
     * @throws \AmoCRM\Exceptions\AmoCRMApiException
     * @throws \AmoCRM\Exceptions\AmoCRMoAuthApiException
     * @throws \AmoCRM\Exceptions\AmoCRMMissedTokenException
     */
    private function notify(LeadModel $lead, ContactModel $contact): void
    {
        $api = Amo::api()->contacts();

        $collectionOfLink = new LinksCollection();
        $collectionOfLink->add($lead);

        $api->link($contact, $collectionOfLink);
    }
}
