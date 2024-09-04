<?php

namespace App\Listeners\Massaging;

use App\Base\Messaging\Factory;
use App\Base\Messaging\IMessage;
use App\Base\Messaging\SentMessage;
use App\Events\Messaging\MessageReceived;
use App\Models\AmoInstance;
use App\Models\Chat;
use App\Models\Message;
use App\Models\Settings;
use App\Models\User;
use App\Models\WhatsappInstance;
use App\Services\Whatsapp\Facades\Whatsapp;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Arr;

class SendMessageWhatsapp implements ShouldQueue
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
        return $event->from === 'amochat';
    }

    /**
     * Get the name of the listener's queue.
     */
    public function viaQueue(): string
    {
        return 'massaging';
    }

    /**
     * @throws Exception
     */
    public function handle(MessageReceived $event): void
    {
        try {

            $amoMessageId = $event->payload['message']['message']['id'];

            $messagePayload = $this->mapMessage($event->payload['message']);

            $chat = $this->mapChat($event->payload['message']);

            $whatsappInstance = $chat->whatsappInstance;

            $sentMessage = $this->sendMessage($messagePayload, $whatsappInstance);

            $record = Message::query()->updateOrCreate([
                'amo_message_id' => $amoMessageId,
                'whatsapp_message_id' => $sentMessage->id,
            ], [
                'chat_id' => $chat->id,
            ]);

            do_log("messaging/sent/amochat")->info("sent message with ID: ".$sentMessage->id, [
                'record' => $record->toArray(),
                'payload' => $messagePayload->toArray(),
            ]);
        } catch (Exception|ModelNotFoundException $e) {
            do_log("messaging/sent/amochat")->error($e->getMessage(), $event->payload);
            $this->release();

            return;
        }
    }

    private function mapChat(array $chatPayload): Chat
    {
        $clientId = Arr::get(Arr::get($chatPayload, 'conversation'), 'client_id') && Arr::get(Arr::get($chatPayload, 'receiver'), 'phone').'@c.us';

        /** @var Settings $settings */
        $settings = Settings::query()->find(Arr::get(Arr::get($chatPayload, 'source'), 'external_id'));

        /** @var Chat $chat */
        $chat = Chat::query()->firstOrCreate(['amo_chat_id' => $chatPayload['conversation']['id']]);

        $chat->whatsapp_chat_id = $clientId;
        $chat->whatsapp_instance_id = $settings->instance_id;
        $chat->save();

        return $chat;
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
