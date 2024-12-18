<?php

namespace App\Listeners\Messaging;

use App\Base\Messaging\Factory;
use App\Base\Messaging\IMessage;
use App\Base\Messaging\SentMessage;
use App\Enums\InstanceStatus;
use App\Events\Messaging\MessageReceived;
use App\Exceptions\Whatsapp\InstanceBlockedException;
use App\Exceptions\Whatsapp\InstanceNotFoundException;
use App\Models\AmoInstance;
use App\Models\Chat;
use App\Models\Message;
use App\Models\WhatsappInstance;
use App\Services\Whatsapp\Facades\Whatsapp;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

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
        return 'messaging';
    }

    /**
     * @throws Exception
     */
    public function handle(MessageReceived $event): void
    {
        try {

            $amoMessageId = $event->payload['message']['message']['id'];

            $messagePayload = $this->mapMessage($event->payload['message']);

            $user = AmoInstance::with([
                'user' => fn(
                    $query) => $query->with('whatsapp_instances'),
            ])->where('scope_id', $event->payload['scope_id'])->first()->user;

            /** @var ?WhatsappInstance $whatsappInstance */
            $whatsappInstance = $user->whatsapp_instances->first(fn($item) => $item->id == $event->payload['message']['source']['external_id']);

            if (! $whatsappInstance) {
                throw new InstanceNotFoundException("Нет удалесь отправить собшение из амо в ватсапп так как нет подключеного инстанса для этого аккаунта {$user->domain}");
            }

            if ($whatsappInstance->status === InstanceStatus::BLOCKED) {
                throw new InstanceBlockedException(
                    "Инстанс {$whatsappInstance->id} блокирован и не может быть исползован!"
                );
            }

            $chat = $this->getChat($event->payload['message'], $whatsappInstance);

            $sentMessage = $this->sendMessage($messagePayload, $whatsappInstance);

            $record = Message::query()->updateOrCreate([
                'amo_message_id' => $amoMessageId,
                'whatsapp_message_id' => $sentMessage->id,
                'from' => 'amochat',
                'to' => 'whatsapp',
            ], [
                'chat_id' => $chat->id,
            ]);

            do_log("messaging", class_basename($this))->info("Сообщение отправлено. ID: ".$sentMessage->id, [
                'record' => $record->toArray(),
                'payload' => $messagePayload->toArray(),
                'response' => $sentMessage->ref_id,
            ]);
        } catch (InstanceBlockedException|Exception|ModelNotFoundException|InstanceNotFoundException $e) {
            do_log("messaging", class_basename($this))->error($e->getMessage(), $event->payload);
            $this->release();
        }
    }

    private function getChat(array $chatPayload, WhatsappInstance $whatsappInstance): Chat
    {
        $whatsappChatId = null;

        if (isset($chatPayload['conversation']['client_id'])) {
            $whatsappChatId = Str::replaceStart('+', '', $chatPayload['conversation']['client_id']);
        }
        elseif (isset($chatPayload['receiver']['phone'])) {
            $whatsappChatId = Str::replaceStart('8', '7', $chatPayload['receiver']['phone']);
            $whatsappChatId .= "@c.us";
        }

        /** @var Chat $chat */
        $chat = Chat::query()->where('amo_chat_id', $chatPayload['conversation']['id'])->latest('created_at')->first();

        if (! $chat) {
            $chat = new Chat();
            $chat->amo_chat_id = $chatPayload['conversation']['id'];
            $chat->whatsapp_chat_id = $whatsappChatId;
            $chat->whatsapp_instance_id = $whatsappInstance->id;
            $chat->save();
        }

        return $chat;
    }

    private function sendMessage(IMessage $message, WhatsappInstance $whatsappInstance): SentMessage
    {
        return Whatsapp::for($whatsappInstance)->messaging()->send($message);
    }

    /**
     * @throws \App\Exceptions\Messaging\ProviderNotConfiguredException
     * @throws \App\Exceptions\Messaging\AdapterNotDefinedException
     * @throws \App\Exceptions\Messaging\UnknownMessageTypeException
     */
    private function mapMessage(array $message): IMessage
    {
        $factory = Factory::make();

        $factory->from('amochat')->type($message['message']['type']);

        return $factory->to('whatsapp')->getAdaptedMessage($message);
    }
}
