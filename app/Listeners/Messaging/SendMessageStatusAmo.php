<?php

namespace App\Listeners\Messaging;

use App\Base\Messaging\Factory;
use App\Events\Messaging\MessageStatusReceived;
use App\Exceptions\Messaging\ProviderNotConfiguredException;
use App\Exceptions\Messaging\UnknownMessageException;
use App\Exceptions\Messaging\UnknownMessageStatusException;
use App\Models\Message;
use App\Models\WhatsappInstance;
use App\Services\AmoChat\Facades\AmoChat;
use App\Services\AmoChat\Messaging\Status\Status;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Queue\InteractsWithQueue;

class SendMessageStatusAmo implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct()
    {
    }

    /**
     * Determine whether the listener should be queued.
     */
    public function shouldQueue(MessageStatusReceived $event): bool
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

    public function handle(MessageStatusReceived $event): void
    {
        try {
            /** @var Message $message */
            $message = Message::query()->where('whatsapp_message_id', $event->payload['idMessage'])->first();

            if (! $message) {
                throw new UnknownMessageException($event->payload['idMessage'], 'whatsapp', $event->payload);
            }

            $whatsappInstance = $this->getWhatsappInstance($event->payload['instanceData']['idInstance']);

            $amoInstance = $whatsappInstance->user->amo_instance;

            $massager = AmoChat::messaging($amoInstance->scope_id);

            $newStatus = Factory::make()->from('whatsapp')->to('amochat')->getAdaptedStatus($event->payload['status']);

            $massager->sendStatus(new Status($message->amo_message_id, $newStatus));

            do_log("messaging/status/amochat")->info(
                "Обновлено статус сообщения ID: ".$message->amo_message_id,
                $massager->getLastRequestInfo()
            );
        } catch (ProviderNotConfiguredException|ModelNotFoundException|UnknownMessageStatusException $e) {
            do_log("messaging/status/amochat")->error($e->getMessage());

            $this->release();
        } catch (UnknownMessageException $e) {
            do_log("messaging/status/amochat")->error($e->getMessage(), $e->getPayload());
        }
    }

    private function getWhatsappInstance(string $id): WhatsappInstance
    {
        /** @var WhatsappInstance */
        return WhatsappInstance::with(['user' => fn($query) => $query->with('amo_instance')])->findOrFail($id);
    }
}
