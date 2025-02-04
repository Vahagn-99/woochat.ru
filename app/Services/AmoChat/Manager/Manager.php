<?php

namespace App\Services\AmoChat\Manager;

use App\Models\AmoInstance;
use App\Services\AmoChat\Chat\Connect\ConnectChatServiceInterface;
use App\Services\AmoChat\Chat\Create\ChatServiceInterface;
use App\Services\AmoChat\Messaging\AmoMessagingInterface;

class Manager implements ManagerInterface
{
    public function __construct(
        private readonly ConnectChatServiceInterface $connectChatService,
        private readonly ChatServiceInterface $chatService,
        private readonly AmoMessagingInterface $messageApi,
    ) {
    }

    public function connector(): ConnectChatServiceInterface
    {
        return $this->connectChatService;
    }

    public function chat(string|AmoInstance $scope_id): ChatServiceInterface
    {
        if ($scope_id instanceof AmoInstance) {
            $scope_id = $scope_id->scope_id;
        }

        $this->chatService->setScopeId($scope_id);

        return $this->chatService;
    }

    public function messaging(string $scope_id): AmoMessagingInterface
    {
        $this->messageApi->setScopeId($scope_id);

        return $this->messageApi;
    }
}