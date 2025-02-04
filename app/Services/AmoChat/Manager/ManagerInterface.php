<?php

namespace App\Services\AmoChat\Manager;

use App\Models\AmoInstance;
use App\Services\AmoChat\Chat\Connect\ConnectChatServiceInterface;
use App\Services\AmoChat\Chat\Create\ChatServiceInterface;
use App\Services\AmoChat\Messaging\AmoMessagingInterface;

interface ManagerInterface
{
    public function connector(): ConnectChatServiceInterface;

    public function chat(string|AmoInstance $scope_id): ChatServiceInterface;

    public function messaging(string $scope_id): AmoMessagingInterface;
}