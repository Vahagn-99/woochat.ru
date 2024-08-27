<?php

namespace App\Listeners\AmoChat;

use App\Base\Messaging\Factory;
use App\Base\Messaging\IMessage;
use App\Base\Messaging\SentMessage;
use App\Events\Messaging\MessageReceived;
use App\Models\AmoInstance;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use App\Models\WhatsappInstance;
use App\Services\Whatsapp\Facades\Whatsapp;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendMessageWhatsapp implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct()
    {
    }

    /**
     * @throws Exception
     */
    public function handle(MessageReceived $event): void
    {
        if ($event->from !== 'amochat') {
            return;
        }

        $amoMessageId = $event->payload['message']['message']['id'];

        $messagePayload = $this->mapMessage($event->payload['message']);

        $amoInstance = $this->getAmoInstance($event->payload['account_id']);

        $user = $amoInstance->user;

        $chat = $this->mapChat($event->payload);

        $whatsappInstance = $this->getWhatsappInstance($chat, $user);

        $sentMessage = $this->sendMessage($messagePayload, $whatsappInstance);

        $record = Message::query()->updateOrCreate([
            'amo_message_id' => $amoMessageId,
            'whatsapp_message_id' => $sentMessage->id,
        ], [
            'chat_id' => $chat->id,
        ]);

        do_log("messaging/amochat")->info("sent message with ID: ".$sentMessage->id, [
            'record' => $record->toArray(),
            'payload' => $messagePayload->toArray(),
        ]);
    }

    /**
     * @throws Exception
     */
    private function getWhatsappInstance(Chat $chat, User $user): WhatsappInstance
    {
        /** @var WhatsappInstance */
        return $chat->whatsappInstance ?? WhatsappInstance::firstInAccount($user) ?? throw new Exception('Instance not found');
    }

    private function mapChat(array $payload): Chat
    {
        /** @var Chat $chat */
        $chat = Chat::query()->firstOrCreate(['amo_chat_id' => $payload['message']['conversation']['id']]);

        if (! $chat->whatsapp_chat_id) {
            $whatsappId = $payload['message']['conversation']['client_id'] ?? $payload['message']['receiver']['phone'].'@c.us';
            $chat->whatsapp_chat_id = $whatsappId;

            $chat->save();
        }

        return $chat;
    }

    private function getAmoInstance(string $accountId): AmoInstance
    {
        /** @var AmoInstance */
        return AmoInstance::findByAccountId($accountId);
    }

    private function sendMessage(IMessage $message, WhatsappInstance $whatsappInstance): SentMessage
    {
        return Whatsapp::for($whatsappInstance)->massaging()->send($message);
    }

    /**
     * @throws \App\Exceptions\Messaging\ProviderNotConfiguredException
     * @throws \App\Exceptions\Messaging\AdapterNotDefinedException
     * @throws \App\Exceptions\Messaging\UnknownMessageTypeException
     */
    private function mapMessage(array $message): IMessage
    {
        $factory = Factory::make();
        $factory->from('amochat', $message['message']['type']);

        return $factory->to('whatsapp', $message);
    }
}
