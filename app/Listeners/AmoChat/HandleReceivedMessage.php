<?php

namespace App\Listeners\AmoChat;

use App\Base\Chat\Message\Response;
use App\Events\AmoCrm\MessageReceived;
use App\Models\AmoConnection;
use App\Models\Chat;
use App\Models\Instance;
use App\Models\Message;
use App\Models\User;
use App\Services\Whatsapp\Facades\Whatsapp;
use App\Services\Whatsapp\Messaging\Types\Text;
use Exception;

class HandleReceivedMessage
{
    public function __construct()
    {
    }

    /**
     * @throws Exception
     */
    public function handle(MessageReceived $event): void
    {
        $payload = $event->payload['message'];
        $scopeId = $event->payload['scope_id'];
        $receiverData = $payload['receiver'];
        $conversation = $payload['conversation'];
        $message = $payload['message'];

        $connection = $this->getConnection($scopeId); //
        $user = $this->getUser($connection);
        $chat = $this->getChat($conversation);

        $instance = $this->getInstance($chat, $user);

        $this->ensureChatFilled($chat, $receiverData);

        $whatsMessage = $this->sendMessage($message, $instance, $chat);

        Message::query()->updateOrCreate([
            'amo_message_id' => $message['id'],
            'whatsapp_message_id' => $whatsMessage->id->value,
        ], [
            'chat_id' => $chat->id,
        ]);
    }

    /**
     * @throws Exception
     */
    private function getInstance(Chat $chat, User $user): Instance
    {
        /** @var Instance */
        return $chat->instance ?? Instance::firstInAccount($user) ?? throw new Exception('Instance not found');
    }

    private function getChat(array $conversation): Chat
    {
        /** @var Chat */
        return Chat::query()->firstOrCreate([
            'amo_chat_id' => $conversation['id'],
        ]);
    }

    private function getUser(AmoConnection $connection): User
    {
        return $connection->user;
    }

    private function getConnection(string $scopeId): AmoConnection
    {
        /** @var AmoConnection */
        return AmoConnection::findByScopeId($scopeId);
    }

    private function ensureChatFilled(Chat $chat, array $receiverData): void
    {
        $chat->whatsapp_chat_id = $receiverData['client_id'];
        $chat->save();
    }

    private function sendMessage(array $messageData, Instance $instance, Chat $chat): Response
    {
        $message = new Text($chat->whatsapp_chat_id, $messageData['text']);

        return Whatsapp::for($instance)->massaging()->send($message);
    }
}
