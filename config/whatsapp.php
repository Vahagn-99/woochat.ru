<?php

use App\Events\Messaging\MessageReceived;
use App\Events\Whatsapp\InstanceStatusChanged;

return [
    'partner' => [
        'api_url' => ENV('WHATSAPP_PARTNER_API_URL', 'https://api.green-api.com'),
        'api_token' => ENV('WHATSAPP_PARTNER_API_TOKEN', 'gac.1c81d6d37a3145dd8e4240ed3fa24852938ae575550741'),
    ],
    /*
     * \
     * \ Рекомендуемые параметры запроса:
     * \
     */
    'instance' => [
        // URL для получения входящих уведомлений
        "webhookRouteName" => ENV('WHATSAPP_WEBHOOK_API_ROUTE_NAME', 'webhooks.whatsapp'),
        // URL для получения входящих уведомлений
        "webhookUrl" => ENV('WHATSAPP_WEBHOOK_API_URL', null),
        // токен для доступа к вашему серверу уведомлений
        "webhookUrlToken" => ENV("WEBHOOK_URL_TOKEN", ""),
        // время отправки сообщений из очереди
        "delaySendMessagesMilliseconds" => ENV("DELAY_SEND_MESSAGES_MILLISECONDS", 3000),
        // отмечать входящие сообщения прочитанными
        "markIncomingMessagesReaded" => ENV("MARK_INCOMING_MESSAGES_READED", "no"),
        // отмечать входящие сообщения прочитанными при ответе собеседнику
        "markIncomingMessagesReadedOnReply" => ENV("MARK_INCOMING_MESSAGES_READED_ON_REPLY", "no"),
        // получать уведомления о статусах отправленных сообщений
        "outgoingWebhook" => ENV("OUTGOING_WEBHOOK", "no"),
        // получать уведомления при отправке с устройства
        "outgoingMessageWebhook" => ENV("OUTGOING_MESSAGE_WEBHOOK", "no"),
        // получать уведомления при отправке с API
        "outgoingAPIMessageWebhook" => ENV("OUTGOING_API_MESSAGE_WEBHOOK", "no"),
        // получать уведомления о входящих сообщениях
        "incomingWebhook" => ENV("INCOMING_WEBHOOK", "yes"),
        // получать уведомления об устройстве. Уведомление временно не работает.
        "deviceWebhook" => ENV("DEVICE_WEBHOOK", "no"),
        // получать уведомления об изменении состояния авторизации инстанса
        "stateWebhook" => ENV("STATE_WEBHOOK", "yes"),
        // выставляет статус 'В сети' для вашего аккаунта
        "keepOnlineStatus" => ENV("KEEP_ONLINE_STATUS", "no"),
        // получать уведомления о создании опроса и голосовании в опросе
        "pollMessageWebhook" => ENV("POLL_MESSAGE_WEBHOOK", "no"),
        // получать уведомления о добавлении чата в список заблокированных контактов. Уведомление временно не работает.
        "incomingBlockWebhook" => ENV("INCOMING_BLOCK_WEBHOOK", "no"),
    ],

    'webhooks' => [
        'incomingMessageReceived' => MessageReceived::class, // Входящее сообщение
        'stateInstanceChanged' => InstanceStatusChanged::class, // Статус инстанса
    ],
];
