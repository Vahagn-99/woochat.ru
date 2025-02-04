<?php

namespace App\Services\AmoChat\Chat\Create;

interface ChatServiceInterface
{
    public function create(SaveAmoChatDTO $data): AmoChat;

    public function setScopeId(string $scopeId): static;
}