<?php

namespace App\Services\AmoCRM\Api\Webhook;

use AmoCRM\EntitiesServices\Webhooks;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMMissedTokenException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Filters\WebhooksFilter;
use AmoCRM\Models\WebhookModel;
use App\Services\AmoCRM\Core\Facades\Amo;

class WebhookSubscriberApi implements SubscriberInterface
{
    public function subscribe(string $destination, array $actions): void
    {
        $amoWebhook = new  WebhookModel;
        $amoWebhook->setDestination($destination);
        $amoWebhook->setSettings($actions);

        try {
            //Подпишемся на веб хук добавления сделки
            $this->endpoint()->subscribe($amoWebhook);
        } catch (AmoCRMApiException $e) {
            dd($e->setLastRequestInfo());
        }
    }

    /**
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    public function refresh(string $destination, array $actions): void
    {
        $amoWebhook = new  WebhookModel;
        $amoWebhook->setDestination($destination);
        $filter = new WebhooksFilter();
        $filter->setDestination($destination);
        //Подпишемся на веб хук добавления сделки
        $exists = $this->endpoint()->get($filter);

        if ($exists) {
            $this->endpoint()->unsubscribe($exists->current());
        }

        $amoWebhook->setSettings($actions);
        $this->endpoint()->subscribe($amoWebhook);
    }

    /**
     * @throws AmoCRMoAuthApiException
     */
    protected function endpoint(): Webhooks
    {
        try {
            return Amo::api()->webhooks();
        } catch (AmoCRMMissedTokenException $e) {
            error_log($e);
            throw new AmoCRMoAuthApiException("Token is required");
        }
    }
}
