<?php

namespace App\Services\AmoChat\Chat\Connect;

interface ConnectChatServiceInterface
{
    public function connect($accountId): Connetion;
}
