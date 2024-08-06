<?php

namespace App\Services\AmoChat\Providers;

use App\Services\AmoChat\Chat\Connect\ConnectChatService;
use App\Services\AmoChat\Chat\Connect\ConnectChatServiceInterface;
use App\Services\AmoChat\Chat\Create\ChatService;
use App\Services\AmoChat\Chat\Create\ChatServiceInterface;
use App\Services\AmoChat\Client\ApiClient;
use App\Services\AmoChat\Client\ApiClientInterface;
use App\Services\AmoChat\Manager\Manager;
use App\Services\AmoChat\Manager\ManagerInterface;
use App\Services\AmoChat\Messaging\MessageService;
use App\Services\AmoChat\Messaging\MessageServiceInterface;
use Illuminate\Support\ServiceProvider;

class AmoChatServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->app->singleton(ApiClientInterface::class, ApiClient::class);
        $this->app->bind(ManagerInterface::class, Manager::class);
        $this->app->bind(MessageServiceInterface::class, MessageService::class);
        $this->app->bind(ConnectChatServiceInterface::class, ConnectChatService::class);
        $this->app->bind(ChatServiceInterface::class, ChatService::class);

        // facade
        $this->app->bind('amo-chat', ManagerInterface::class);
    }

    public function boot(): void
    {

    }
}
