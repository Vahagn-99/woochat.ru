<?php

namespace App\Services\AmoCRM\Entities\Webhook;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\EntitiesServices\Webhooks;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMMissedTokenException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Filters\WebhooksFilter;
use AmoCRM\Models\WebhookModel;

readonly class WebhookSubscriberApi implements SubscriberInterface
{
    public function __construct(private AmoCRMApiClient $apiClient)
    {
    }

    public function subscribe(string $destination, array $actions): void
    {
        $amoWebhook = new  WebhookModel;
        $amoWebhook->setDestination($destination);
        $amoWebhook->setSettings($actions);

        try {
            //Подпишемся на веб хук добавления сделки
            $this->endpoint()->subscribe($amoWebhook);
        } catch (AmoCRMApiException $e) {
            dd($e->getLastRequestInfo());
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
        $filter = app(WebhooksFilter::class);
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
            return $this->apiClient->webhooks();
        } catch (AmoCRMMissedTokenException $e) {
            error_log($e);
            throw new AmoCRMoAuthApiException("Token is required");
        }
    }
}
