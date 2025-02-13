<?php

namespace App\Listeners\Messaging;

use App\Base\Messaging\Factory;
use App\Base\Messaging\IMessage;
use App\Enums\InstanceStatus;
use App\Events\Messaging\MessageReceived;
use App\Exceptions\ProviderNotAvailableException;
use App\Exceptions\Whatsapp\InstanceBlockedException;
use App\Models\AmoInstance;
use App\Models\Chat;
use App\Models\Message;
use App\Models\WhatsappInstance;
use App\Services\AmoChat\Facades\AmoChat;
use App\Services\AmoChat\Messaging\Actor\Actor;
use App\Services\AmoChat\Messaging\Actor\Profile;
use App\Services\AmoChat\Messaging\Source\Source;
use App\Services\AmoChat\Messaging\Types\AmoMessage;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SendMessageAmo implements ShouldQueue
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
    public function backoff() : array
    {
        return [10, 30, 60, 120, 300];
    }

    /**
     * Determine whether the listener should be queued.
     */
    public function shouldQueue(MessageReceived $event) : bool
    {
        return $event->from === 'whatsapp';
    }

    /**
     * Get the name of the listener's queue.
     */
    public function viaQueue() : string
    {
        return 'messaging';
    }

    /**
     * @throws \App\Exceptions\Messaging\SendMessageException
     */
    public function handle(MessageReceived $event) : void
    {
        try {
            // Логируем входящие данные для отладки
            do_log("messaging/debug")->info("Получено сообщение для обработки", [
                'message_id' => $event->payload['idMessage'] ?? null,
                'instance_id' => $event->payload['instanceData']['idInstance'] ?? null,
                'timestamp' => $event->payload['timestamp'] ?? null,
                'sender' => $event->payload['senderData'] ?? null,
                'message_type' => $event->payload['messageData']['typeMessage'] ?? null,
            ]);

            $whatsappInstance = $this->getWhatsappInstance($event->payload['instanceData']['idInstance']);

            if ($whatsappInstance->status === InstanceStatus::BLOCKED) {
                throw new InstanceBlockedException(
                    "Инстанс {$whatsappInstance->id} блокирован и не может быть использован!"
                );
            }

            $amoInstance = $this->getAmoInstance($whatsappInstance);

            /*
             * TODO: Включить проверку доступности AmoCRM
             * Это позволит избежать отправки сообщений когда AmoCRM недоступен
             * или токен невалиден, что может приводить к потере сообщений.
             */
            // $this->checkAmoAvailability($amoInstance);

            $sender = $this->mapSender($event->payload['senderData']);

            $chat = $this->getChat($event->payload['senderData']['chatId'], $amoInstance, $whatsappInstance);

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

            do_log("messaging/".class_basename($this))->info(
                "Сообщение отправлено. ID: ".$sentMessage->id,
                [
                    'request_info' => $massager->getLastRequestInfo(),
                    'attempt' => $this->attempts(),
                    'message_id' => $event->payload['idMessage'] ?? null
                ]
            );
        } catch (Exception $e) {
            do_log("messaging/".class_basename($this))->error(
                "Ошибка обработки сообщения WhatsApp",
                [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'payload' => $event->payload,
                    'attempt' => $this->attempts()
                ]
            );

            if ($this->attempts() >= $this->tries) {
                do_log("messaging/".class_basename($this))->critical(
                    "Сообщение WhatsApp потеряно после всех попыток обработки",
                    [
                        'message_id' => $event->payload['idMessage'] ?? null,
                        'last_error' => $e->getMessage()
                    ]
                );
            }

            $this->release(30);
            return;
        }
    }

    private function getAmoInstance(WhatsappInstance $whatsappInstance) : AmoInstance
    {
        return $whatsappInstance->user->amo_instance;
    }

    private function getWhatsappInstance(string $id) : WhatsappInstance
    {
        /** @var WhatsappInstance */
        return WhatsappInstance::query()->findOrFail($id);
    }

    private function getChat(
        string $whatsappChatId,
        AmoInstance $amoInstance,
        WhatsappInstance $whatsappInstance
    ) : Chat
    {
        return DB::transaction(function () use ($whatsappChatId, $amoInstance, $whatsappInstance) {
            /** @var Chat $chat */
            $chat = Chat::query()
                ->where([
                    'whatsapp_chat_id' => $whatsappChatId,
                    'whatsapp_instance_id' => $whatsappInstance->id,
                ])
                ->lockForUpdate()
                ->latest('created_at')
                ->first();

            if (! $chat) {
                $chat = new Chat();
                $chat->whatsapp_chat_id = $whatsappChatId;
                $chat->amo_chat_instance_id = $amoInstance->id;
                $chat->whatsapp_instance_id = $whatsappInstance->id;
            }

            if (! $chat->amo_chat_instance_id) {
                $chat->amo_chat_instance_id = $amoInstance->id;
            }

            $chat->save();

            return $chat;
        });
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
        WhatsappInstance $whatsappInstance
    ) : AmoMessage
    {
        $source = new Source($whatsappInstance->id);

        return new AmoMessage(
            sender : $sender,
            payload : $this->mapMessagePayload($payload['messageData']),
            source : $source,
            conversation_id : $chat->whatsapp_chat_id,
            conversation_ref_id : $chat->amo_chat_id,
            msgid : $payload['idMessage']
        );
    }

    private function mapSender(mixed $senderData) : Actor
    {
        return new Actor(
            id : $senderData['sender'], name : $senderData['senderName'], profile : new Profile(
            phone : Str::replace([
                "@c.us",
                "@g.us",
            ], "", $senderData['sender'])
        )
        );
    }

    /**
     * @throws \App\Exceptions\Messaging\ProviderNotConfiguredException
     * @throws \App\Exceptions\Messaging\UnknownMessageTypeException
     * @throws \App\Exceptions\Messaging\AdapterNotDefinedException
     */
    private function mapMessagePayload(array $messageData) : IMessage
    {
        $type = $messageData['typeMessage'];

        $factory = Factory::make();

        $factory->from('whatsapp')->type($type);

        return $factory->to('amochat')->getAdaptedMessage($messageData);
    }

    /**
     * Determine the time at which the job should timeout.
     */
    public function retryUntil() : \DateTime
    {
        return now()->addMinutes(5);
    }

    /**
     * TODO: Метод для проверки доступности AmoCRM
     * Будет использоваться после включения проверки доступности
     * @throws \App\Exceptions\ProviderNotAvailableException
     */
    private function checkAmoAvailability(AmoInstance $amoInstance) : void
    {
        try {
            $messenger = AmoChat::messaging($amoInstance->scope_id);
            if (! $messenger->isAvailable()) {
                throw new ProviderNotAvailableException("AmoCRM API недоступен или токен невалиден");
            }
        } catch (Exception $e) {
            do_log("messaging/".class_basename($this))->error(
                "Ошибка проверки доступности AmoCRM",
                [
                    'error' => $e->getMessage(),
                    'scope_id' => $amoInstance->scope_id
                ]
            );
            throw new ProviderNotAvailableException(
                "Ошибка при проверке доступности AmoCRM: ".$e->getMessage()
            );
        }
    }
}
