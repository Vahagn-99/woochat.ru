<?php

namespace App\Listeners\GreenApi\Webhooks;

use App\Events\GreenApi\Webhooks\OutgoingMessageReceived;
use App\Models\AmoConnection;
use App\Models\Chat;
use App\Models\Instance;
use App\Models\User;
use App\Services\AmoChat\Chat\Create\CreateAmoChatDTO;
use App\Services\AmoChat\Facades\AmoChat;
use App\Services\AmoChat\Messaging\MessageResponse;
use App\Services\AmoChat\Messaging\Types\Actor;
use App\Services\AmoChat\Messaging\Types\Message;
use App\Services\AmoChat\Messaging\Types\Text;

class HandleOutgoingMessage
{
    public function __construct()
    {
        //
    }

    public function handle(OutgoingMessageReceived $event): void
    {
        $instanceData = $event->webhookPayload['instanceData'];
        $senderData = $event->webhookPayload['senderData'];
        $messageData = $event->webhookPayload['messageData'];
        $messageId = $event->webhookPayload['idMessage'];

        $instance = $this->getInstance($instanceData);
        $user = $this->getUser($instance);
        $connection = $this->getConnection($user);

        $chat = $this->getChat($instanceData, $senderData);
        $this->ensureAmoChatCreated($chat, $connection, $senderData);

        $message = $this->sendMessage($messageId, $messageData, $senderData, $connection, $chat);

        \App\Models\Message::query()->updateOrCreate(
            [
                'amo_message_id' => $message->msgId,
                'whatsapp_message_id' => $messageId,
            ],
            [
                'chat_id' => $chat->id,
            ]
        );
    }

    private function getInstance(array $instanceData): Instance
    {
        /** @var Instance */
        return Instance::query()->find($instanceData['idInstance']);
    }

    private function getChat(array $instanceData, array $senderData): Chat
    {
        /** @var Chat */
        return Chat::query()->firstOrCreate([
            'instance_id' => $instanceData['idInstance'],
            'whatsapp_chat_id' => $senderData['chatId'],
        ]);
    }

    private function getUser(Instance $instance): User
    {
        return $instance->user;
    }

    private function getConnection(User $user): AmoConnection
    {
        /** @var AmoConnection */
        return $user->amoConnections()->first();
    }

    private function ensureAmoChatCreated(Chat $chat, AmoConnection $connection, mixed $senderData): void
    {
        if (!$chat->amo_chat_id) {
            $data = new CreateAmoChatDTO(
                $chat->id,
                $chat->whatsapp_chat_id,
                $senderData['sender'],
                $senderData['senderName'],
            );
            $amoChat = AmoChat::chat($connection->scope_id)->create($data);
            $chat->amo_chat_id = $amoChat->id;
            $chat->save();
        }
    }

    private function sendMessage(string $messageId, array $messageData, array $senderData, AmoConnection $connection, Chat $chat): MessageResponse
    {
        $sender = new Actor(
            id: $senderData['sender'],
            name: $senderData['senderName'],
        );

        $payload = new Text(
            chatId: $chat->amo_chat_id,
            text: $messageData['textMessageData']['textMessage'],
        );

        $message = new Message(
            sender: $sender,
            payload: $payload,
            silent: false,
            conversation_id: $chat->whatsapp_chat_id,
            msgid: $messageId,
        );

        return AmoChat::messaging($connection->scope_id)->send($message);
    }
}
