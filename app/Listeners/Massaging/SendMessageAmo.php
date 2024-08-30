<?php

namespace App\Listeners\Massaging;

use App\Base\Messaging\Factory;
use App\Base\Messaging\IMessage;
use App\Events\Messaging\MessageReceived;
use App\Exceptions\Messaging\AdapterNotDefinedException;
use App\Exceptions\Messaging\ProviderNotConfiguredException;
use App\Exceptions\Messaging\UnknownMessageTypeException;
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
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Str;

class SendMessageAmo implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct()
    {

    }

    /**
     * Determine whether the listener should be queued.
     */
    public function shouldQueue(MessageReceived $event): bool
    {
        return $event->from === 'whatsapp';
    }

    /**
     * Get the name of the listener's queue.
     */
    public function viaQueue(): string
    {
        return 'massaging';
    }

    public function handle(MessageReceived $event): void
    {
        try {
            $whatsappInstance = $this->getWhatsappInstance($event->payload['instanceData']['idInstance']);

            $amoInstance = $this->getAmoInstance($whatsappInstance);

            $sender = $this->mapSender($event->payload['senderData']);

            $chat = $this->getChat($event->payload['senderData']['chatId'], $amoInstance, $whatsappInstance, $sender);

            $messagePayload = $this->mapMessagePayload($event->payload['messageData']);

            $amoMessage = $this->mapMessage($event->payload['idMessage'], $chat, $sender, $whatsappInstance, $messagePayload);

            $massager = AmoChat::messaging($amoInstance->scope_id);

            $sentMessage = $massager->send($amoMessage);

            Message::query()->create([
                'chat_id' => $chat->id,
                'whatsapp_message_id' => $sentMessage->ref_id,
                'amo_message_id' => $sentMessage->id,
            ]);

            do_log("messaging/sent/whatsapp")->info("sent message with ID: ".$sentMessage->id, $massager->getLastRequestInfo());
        } catch (ProviderNotConfiguredException|AdapterNotDefinedException|UnknownMessageTypeException|ModelNotFoundException $e) {
            do_log("messaging/error/whatsapp")->error($e->getMessage());
            $this->release();

            return;
        }
    }

    private function getAmoInstance(WhatsappInstance $whatsappInstance): AmoInstance
    {
        return $whatsappInstance->user->amoInstance;
    }

    private function getWhatsappInstance(string $id): WhatsappInstance
    {
        /** @var WhatsappInstance */
        return WhatsappInstance::query()->findOrFail($id);
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
        IMessage $payload
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

    /**
     * @throws \App\Exceptions\Messaging\ProviderNotConfiguredException
     * @throws \App\Exceptions\Messaging\UnknownMessageTypeException
     * @throws \App\Exceptions\Messaging\AdapterNotDefinedException
     */
    private function mapMessagePayload(array $messageData): IMessage
    {
        $type = $messageData['typeMessage'];
        $factory = Factory::make();

        $factory->from('whatsapp', $type);

        return $factory->to('amochat', $messageData);
    }
}
