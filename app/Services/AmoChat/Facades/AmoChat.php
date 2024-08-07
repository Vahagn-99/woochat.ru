<?php

namespace App\Services\AmoChat\Facades;

use App\Services\AmoChat\Chat\Connect\ConnectChatServiceInterface;
use App\Services\AmoChat\Chat\Create\ChatServiceInterface;
use App\Services\AmoChat\Manager\ManagerInterface;
use App\Services\AmoChat\Messaging\AmoMessagingInterface;
use Illuminate\Support\Facades\Facade;

/**
 * @method static ConnectChatServiceInterface connector()
 * @method static ChatServiceInterface chat(string $scope_id)
 * @method static AmoMessagingInterface messaging(string $scope_id)
 *
 * @see ManagerInterface
 */
class AmoChat extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'amo-chat';
    }
}