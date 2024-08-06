<?php

namespace App\Services\AmoChat\Manager;

use App\Services\AmoChat\Chat\Connect\ConnectChatServiceInterface;
use App\Services\AmoChat\Chat\Create\ChatServiceInterface;
use App\Services\AmoChat\Messaging\MessageServiceInterface;

class Manager implements ManagerInterface
{
    public function __construct(
        private readonly ConnectChatServiceInterface $connectChatService,
        private readonly ChatServiceInterface        $chatService,
        private readonly MessageServiceInterface     $messageApi,
    )
    {
    }

    public function connector(): ConnectChatServiceInterface
    {
        return $this->connectChatService;
    }

    public function chat(string $scope_id): ChatServiceInterface
    {
        $this->chatService->setScopeId($scope_id);
        return $this->chatService;
    }

    public function messaging(string $scope_id): MessageServiceInterface
    {
        $this->messageApi->setScopeId($scope_id);
        return $this->messageApi;
    }
}