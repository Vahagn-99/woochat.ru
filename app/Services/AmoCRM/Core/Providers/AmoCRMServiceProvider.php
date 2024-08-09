<?php

namespace App\Services\AmoCRM\Core\Providers;

use App\Services\AmoCRM\Api\Contact\ContactApi;
use App\Services\AmoCRM\Api\Contact\ContactApiInterface;
use App\Services\AmoCRM\Api\Lead\LeadApi;
use App\Services\AmoCRM\Api\Lead\LeadApiInterface;
use App\Services\AmoCRM\Api\User\UserApi;
use App\Services\AmoCRM\Api\User\UserApiInterface;
use App\Services\AmoCRM\Api\Webhook\SubscriberInterface;
use App\Services\AmoCRM\Api\Webhook\WebhookSubscriberApi;
use App\Services\AmoCRM\Auth\AmoCrmAuthManager;
use App\Services\AmoCRM\Auth\AuthManagerInterface;
use App\Services\AmoCRM\Core\AmoManager;
use App\Services\AmoCRM\Core\AmoManagerInterface;
use App\Services\AmoCRM\Core\ApiClient\ApiClient;
use App\Services\AmoCRM\Core\ManageAccessToken\AccessTokenManager;
use App\Services\AmoCRM\Core\ManageAccessToken\AccessTokenManagerInterface;
use Illuminate\Support\ServiceProvider;

class AmoCRMServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ApiClient::class, function () {
            return new ApiClient(config('services.amocrm.client_id'), config('services.amocrm.client_secret'), config('services.amocrm.redirect_url'));
        });
        $this->app->singleton(AmoManagerInterface::class, AmoManager::class);
        $this->app->singleton(AuthManagerInterface::class, AmoCrmAuthManager::class);

        $this->app->bind(AccessTokenManagerInterface::class, AccessTokenManager::class);

        $this->app->bind('amocrm', AmoManagerInterface::class);

        //entity api client bindings
        $this->app->bind(SubscriberInterface::class, WebhookSubscriberApi::class);
        $this->app->bind(UserApiInterface::class, UserApi::class);
        $this->app->bind(ContactApiInterface::class, ContactApi::class);
        $this->app->bind(LeadApiInterface::class, LeadApi::class);
    }
}
