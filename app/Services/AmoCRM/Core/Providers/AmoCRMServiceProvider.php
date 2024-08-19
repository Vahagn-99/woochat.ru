<?php

namespace App\Services\AmoCRM\Core\Providers;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Client\AmoCRMApiClientFactory;
use AmoCRM\OAuth\OAuthConfigInterface;
use AmoCRM\OAuth\OAuthServiceInterface;
use App\Services\AmoCRM\Auth\AmoCrmAuthManager;
use App\Services\AmoCRM\Auth\AuthManagerInterface;
use App\Services\AmoCRM\Core\Manager\AmoManager;
use App\Services\AmoCRM\Core\Manager\AmoManagerInterface;
use App\Services\AmoCRM\Core\Oauth\OauthConfig;
use App\Services\AmoCRM\Core\Oauth\OauthService;
use App\Services\AmoCRM\Entities\Contact\ContactApi;
use App\Services\AmoCRM\Entities\Contact\ContactApiInterface;
use App\Services\AmoCRM\Entities\Lead\LeadApi;
use App\Services\AmoCRM\Entities\Lead\LeadApiInterface;
use App\Services\AmoCRM\Entities\Source\SourceApi;
use App\Services\AmoCRM\Entities\Source\SourceApiInterface;
use App\Services\AmoCRM\Entities\User\UserApi;
use App\Services\AmoCRM\Entities\User\UserApiInterface;
use App\Services\AmoCRM\Entities\Webhook\SubscriberInterface;
use App\Services\AmoCRM\Entities\Webhook\WebhookSubscriberApi;
use Illuminate\Support\ServiceProvider;

class AmoCRMServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //$this->app->singleton(OAuthConfigInterface::class, function () {
        //    return new OAuthConfig(config('services.amocrm.client_id'), config('services.amocrm.client_secret'), config('services.amocrm.redirect_url'));
        //});
        $this->app->singleton(OAuthServiceInterface::class, OauthService::class);
        $this->app->singleton(AmoCRMApiClient::class, function () {
           return new AmoCRMApiClient(
               config('services.amocrm.client_id'), config('services.amocrm.client_secret'), config('services.amocrm.redirect_url')
           );
        });
        $this->app->singleton("dct-amo-client", function () {
            return new AmoCRMApiClient(config('amocrm-dct.widget.client_id'), config('amocrm-dct.widget.client_secret'), config('amocrm-dct.widget.redirect_url'));
        });
        $this->app->singleton(AmoManagerInterface::class, AmoManager::class);
        $this->app->singleton(AuthManagerInterface::class, AmoCrmAuthManager::class);
        $this->app->bind('amocrm', AmoManagerInterface::class);

        //entity api client bindings
        $this->app->bind(SubscriberInterface::class, WebhookSubscriberApi::class);
        $this->app->bind(UserApiInterface::class, UserApi::class);
        $this->app->bind(ContactApiInterface::class, ContactApi::class);
        $this->app->bind(LeadApiInterface::class, LeadApi::class);
        $this->app->bind(SourceApiInterface::class, SourceApi::class);
    }
}
