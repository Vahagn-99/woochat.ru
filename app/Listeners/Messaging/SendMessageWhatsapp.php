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
use DateTime;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class SendMessageWhatsapp implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Максимальное количество попыток
     *
     * @var int
     */
    public int $tries = 5;

    /**
     * Базовая задержка между попытками в секундах
     *
     * @var int
     */
    public int $backoff = 30;

    /**
     * Получить массив задержек для повторных попыток
     *
     * @return array
     */
    public function backoff(): array
    {
        return [10, 30, 60, 120, 300];
    }

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
                'user' => fn($query) => $query->with('whatsapp_instances'),
            ])->where('scope_id', $event->payload['scope_id'])->first()->user;

            /** @var ?WhatsappInstance $whatsappInstance */
            $whatsappInstance = $user->whatsapp_instances->first(fn($item) => $item->id == $event->payload['message']['source']['external_id']);

            if (!$whatsappInstance) {
                throw new InstanceNotFoundException("Невозможно отправить сообщение из amoCRM в WhatsApp: отсутствует подключенный инстанс для аккаунта {$user->domain}");
            }

            if ($whatsappInstance->status === InstanceStatus::BLOCKED) {
                throw new InstanceBlockedException(
                    "Инстанс {$whatsappInstance->id} блокирован и не может быть использован!"
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

            do_log("messaging", class_basename($this))->info(
                "Сообщение отправлено. ID: " . $sentMessage->id,
                [
                    'record' => $record->toArray(),
                    'payload' => $messagePayload->toArray(),
                    'response' => $sentMessage->ref_id,
                    'attempt' => $this->attempts()
                ]
            );
        } catch (Exception $e) {
            do_log("messaging", class_basename($this))->error(
                "Ошибка отправки сообщения в WhatsApp",
                [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'payload' => $event->payload,
                    'attempt' => $this->attempts()
                ]
            );

            if ($this->attempts() >= $this->tries) {
                do_log("messaging", class_basename($this))->critical(
                    "Сообщение в WhatsApp потеряно после всех попыток отправки",
                    [
                        'message_id' => $amoMessageId ?? null,
                        'last_error' => $e->getMessage()
                    ]
                );
            }

            $this->release(30);
            return;
        }
    }

    private function getChat(array $chatPayload, WhatsappInstance $whatsappInstance): Chat
    {
        return DB::transaction(function() use ($chatPayload, $whatsappInstance) {
            $whatsappChatId = null;

            if (isset($chatPayload['conversation']['client_id'])) {
                $whatsappChatId = Str::replaceStart('+', '', $chatPayload['conversation']['client_id']);
            } elseif (isset($chatPayload['receiver']['phone'])) {
                $whatsappChatId = Str::replaceStart('8', '7', $chatPayload['receiver']['phone']);
                $whatsappChatId .= "@c.us";
            }

            /** @var Chat $chat */
            $chat = Chat::query()
                ->where('amo_chat_id', $chatPayload['conversation']['id'])
                ->lockForUpdate()
                ->latest('created_at')
                ->first();

            if (!$chat) {
                $chat = new Chat();
                $chat->amo_chat_id = $chatPayload['conversation']['id'];
                $chat->whatsapp_chat_id = $whatsappChatId;
                $chat->whatsapp_instance_id = $whatsappInstance->id;
                $chat->save();
            }

            return $chat;
        });
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


    /**
     * Determine the time at which the job should timeout.
     */
    public function retryUntil() : DateTime
    {
        return now()->addMinutes(30);
    }
}
