<?php

namespace App\Listeners\Whatsapp;

use App\Events\Messaging\MessageReceived;
use App\Models\AmoInstance;
use App\Models\Chat;
use App\Models\Message;
use App\Models\WhatsappInstance;
use App\Services\AmoChat\Chat\Create\SaveAmoChatDTO;
use App\Services\AmoChat\Facades\AmoChat;
use App\Services\AmoChat\Messaging\Actor\Actor;
use App\Services\AmoChat\Messaging\Actor\Profile;
use App\Services\AmoChat\Messaging\Source\Source;
use App\Services\AmoChat\Messaging\Types\AmoMessage;
use App\Services\AmoChat\Messaging\Types\Payload;
use App\Services\AmoChat\Messaging\Types\Text;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Str;

class SendMessageAmo implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct()
    {

    }

    public function handle(MessageReceived $event): void
    {
        if ($event->from !== 'whatsapp') {
            return;
        }

        $whatsappInstance = $this->getWhatsappInstance($event->payload['instanceData']['idInstance']);

        $amoInstance = $this->getAmoInstance($whatsappInstance);

        $sender = $this->mapSender($event->payload['senderData']);

        $chat = $this->getChat($event->payload['senderData']['chatId'], $amoInstance, $whatsappInstance, $sender);

        $messagePayload = $this->mapMessagePayload($chat->amo_chat_id, $event->payload['messageData']);

        $amoMessage = $this->mapMessage($event->payload['idMessage'], $chat, $sender, $whatsappInstance, $messagePayload);

        $massager = AmoChat::messaging($amoInstance->scope_id);

        $sentMessage = $massager->send($amoMessage);

        Message::query()->create([
            'chat_id' => $chat->id,
            'whatsapp_message_id' => $sentMessage->ref_id,
            'amo_message_id' => $sentMessage->id,
        ]);

        do_log("messaging/whatsapp")->info("sent message with ID: ".$sentMessage->id, $massager->getLastRequestInfo());
    }

    private function getAmoInstance(WhatsappInstance $whatsappInstance): AmoInstance
    {
        return $whatsappInstance->user->amoInstance;
    }

    private function getWhatsappInstance(string $id): WhatsappInstance
    {
        /** @var WhatsappInstance */
        return WhatsappInstance::query()->find($id);
    }

    private function getChat(
        string $whatsappChatId,
        AmoInstance $amoInstance,
        WhatsappInstance $whatsappInstance,
        Actor $sender
    ): Chat {
        /** @var Chat $chat */
        $chat = Chat::query()->firstOrCreate(['whatsapp_chat_id' => $whatsappChatId]);

        if (! $chat->amo_chat_id) {
            $data = new SaveAmoChatDTO($chat->id, $chat->whatsapp_chat_id, $sender);
            $amoChat = AmoChat::chat($amoInstance->scope_id)->create($data);
            $chat->amo_chat_id = $amoChat->id;
            $chat->save();
        }

        if (! $chat->whatsapp_instance_id) {
            $chat->whatsapp_instance_id = $whatsappInstance->id;
            $chat->save();
        }

        return $chat;
    }

    private function mapMessage(
        string $id,
        Chat $chat,
        Actor $sender,
        WhatsappInstance $whatsappInstance,
        Payload $payload
    ): AmoMessage {
        $settings = $whatsappInstance->settings;

        $source = null;

        if ($settings->source_id) {
            $source = new Source($settings->id);
        }

        return new AmoMessage(sender: $sender, payload: $payload, source: $source, conversation_id: $chat->whatsapp_chat_id, msgid: $id);
    }

    private function mapSender(mixed $senderData): Actor
    {
        return new Actor(id: $senderData['sender'], name: $senderData['senderName'], profile: new Profile(phone: Str::replace([
            "@c.us",
            "@g.us",
        ], "", $senderData['sender'])));
    }

    private function mapMessagePayload(string $chatId, array $messageData): Payload
    {
        $type = $messageData['typeMessage'];

        return match ($type) {
            'textMessage', 'extendedTextMessage' => $this->createTextMessage($chatId, $messageData), //'imageMessage'       => $this->createImageMessage($chatId, $messageData),
            //'audioMessage'       => $this->createAudioMessage($chatId, $messageData),
            //'locationMessage'    => $this->createLocationMessage($chatId, $messageData),
            //'contactMessage'     => $this->createContactMessage($chatId, $messageData),
            //'reactionMessage'    => $this->createReactionMessage($chatId, $messageData),
            //'groupInviteMessage' => $this->createGroupInviteMessage($chatId, $messageData),
        };
    }

    private function createTextMessage(string $chatId, array $messageData): Text
    {
        return new Text($chatId, $messageData['textMessageData']['textMessage'] ?? $messageData['extendedTextMessageData']['text']);
    }
    //
    //private function createImageMessage(string $chatId, array $messageData): Media
    //{
    //
    //}
    //
    //private function createAudioMessage(string $chatId, array $messageData): Media
    //{
    //}
    //
    //private function createLocationMessage(string $chatId, array $messageData): Location
    //{
    //}
    //
    //private function createContactMessage(string $chatId, array $messageData): Contact
    //{
    //}
    //
    //private function createReactionMessage(string $chatId, array $messageData): Text
    //{
    //}
    //
    //private function createGroupInviteMessage(string $chatId, array $messageData): Payload
    //{
    //}
}
