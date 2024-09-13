<?php

namespace App\Listeners\Messaging;

use App\Base\Messaging\Factory;
use App\Base\Messaging\IMessage;
use App\Events\Messaging\MessageReceived;
use App\Exceptions\Messaging\AdapterNotDefinedException;
use App\Exceptions\Messaging\ProviderNotConfiguredException;
use App\Exceptions\Messaging\SendMessageException;
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
use Exception;
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
        return 'messaging';
    }

    public function handle(MessageReceived $event): void
    {
        try {
            $whatsappInstance = $this->getWhatsappInstance($event->payload['instanceData']['idInstance']);

            $amoInstance = $this->getAmoInstance($whatsappInstance);

            $sender = $this->mapSender($event->payload['senderData']);

            $chat = $this->getChat($event->payload['senderData']['chatId'], $amoInstance, $whatsappInstance, $sender);

            $message = $this->mapMessage($event->payload, $chat, $sender, $whatsappInstance);

            $massager = AmoChat::messaging($amoInstance->scope_id);

            $sentMessage = $massager->send($message);

            Message::query()->updateorCreate([
                'whatsapp_message_id' => $sentMessage->ref_id,
                'amo_message_id' => $sentMessage->id,
                'from' => 'whatsapp',
                'to' => 'amochat',
            ], [
                'chat_id' => $chat->id,
            ]);

            do_log("messaging/".class_basename($this))->info("Собшение отправлено. ID: ".$sentMessage->id, $massager->getLastRequestInfo());
        } catch (ProviderNotConfiguredException|AdapterNotDefinedException|UnknownMessageTypeException|ModelNotFoundException|SendMessageException|Exception $e) {
            do_log("messaging/".class_basename($this))->error($e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            $this->release();
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
        Actor $sender): Chat
    {
        /** @var Chat $chat */
        $chat = Chat::query()->where([
            'whatsapp_chat_id' => $whatsappChatId,
            'whatsapp_instance_id' => $whatsappInstance->id,
        ])->latest('created_at')->first();

        if (! $chat) {
            $chat = new Chat();
            $chat->whatsapp_chat_id = $whatsappChatId;
            $chat->amo_chat_instance_id = $amoInstance->id;
            $chat->whatsapp_instance_id = $whatsappInstance->id;
        } elseif (! $chat->amo_chat_id) {
            $chat->amo_chat_id = AmoChat::chat($amoInstance->scope_id)->create(new SaveAmoChatDTO($chat->whatsapp_chat_id, $sender))->id;
        }

        $chat->save();

        return $chat;
    }

    /**
     * @throws \App\Exceptions\Messaging\ProviderNotConfiguredException
     * @throws \App\Exceptions\Messaging\AdapterNotDefinedException
     * @throws \App\Exceptions\Messaging\UnknownMessageTypeException
     */
    private function mapMessage(
        array $payload,
        Chat $chat,
        Actor $sender,
        WhatsappInstance $whatsappInstance): AmoMessage
    {
        $source = new Source($whatsappInstance->id);

        return new AmoMessage(sender: $sender, payload: $this->mapMessagePayload($payload['messageData']), source: $source, conversation_id: $chat->whatsapp_chat_id, conversation_ref_id: $chat->amo_chat_id, msgid: $payload['idMessage']);
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

        $factory->from('whatsapp')->type($type);

        return $factory->to('amochat')->getAdaptedMessage($messageData);
    }
}
