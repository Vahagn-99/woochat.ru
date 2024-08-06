<?php

namespace App\Listeners\AmoChat;

use App\Events\AmoCrm\MessageReceived;
use App\Models\AmoConnection;
use App\Models\Chat;
use App\Models\Instance;
use App\Models\Message;
use App\Models\User;
use App\Services\GreenApi\Facades\GreenApi;
use App\Services\GreenApi\Messaging\MessageId;
use App\Services\GreenApi\Messaging\Types\TextMessage;
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

        $this->ensureChatFilled($chat, $instance, $receiverData);

        $whatsMessage = $this->sendMessage($message, $instance, $chat);
        Message::query()->updateOrCreate(
            [
                'amo_message_id' => $message['id'],
                'whatsapp_message_id' => $whatsMessage->messageId,
            ],
            [
                'chat_id' => $chat->id,
            ]
        );
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

    private function ensureChatFilled(Chat $chat, Instance $instance, array $receiverData): void
    {
        if (!$chat->whatsapp_chat_id) {
            $chat->whatsapp_chat_id = $receiverData['phone'] . '@c.us';
            $chat->save();
        }
    }

    private function sendMessage(array $messageData, Instance $instance, Chat $chat): MessageId
    {
        $message = new TextMessage(
            $chat->whatsapp_chat_id,
            $messageData['text']
        );

        return GreenApi::fromModel($instance)->massaging()->send($message);
    }

}
