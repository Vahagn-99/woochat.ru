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
        "webhookRouteName" => ENV('WHATSAPP_WEBHOOK_API_ROUTE_NAME', 'webhooks.whatsapp'),
        // URL для получения входящих уведомлений
        "webhookUrl" => ENV('WHATSAPP_WEBHOOK_API_URL', null),
        // URL для получения входящих уведомлений
        "webhookUrlToken" => ENV("WEBHOOK_URL_TOKEN", ""),
        // токен для доступа к вашему серверу уведомлений
        "delaySendMessagesMilliseconds" => ENV("DELAY_SEND_MESSAGES_MILLISECONDS", 3000),
        // время отправки сообщений из очереди
        "markIncomingMessagesReaded" => ENV("MARK_INCOMING_MESSAGES_READED", "no"),
        // отмечать входящие сообщения прочитанными
        "markIncomingMessagesReadedOnReply" => ENV("MARK_INCOMING_MESSAGES_READED_ON_REPLY", "yes"),
        // отмечать входящие сообщения прочитанными при ответе собеседнику
        "outgoingWebhook" => ENV("OUTGOING_WEBHOOK", "yes"),
        // получать уведомления о статусах отправленных сообщений
        "outgoingMessageWebhook" => ENV("OUTGOING_MESSAGE_WEBHOOK", "yes"),
        // получать уведомления при отправке с устройства
        "outgoingAPIMessageWebhook" => ENV("OUTGOING_API_MESSAGE_WEBHOOK", "no"),
        // получать уведомления при отправке с API
        "incomingWebhook" => ENV("INCOMING_WEBHOOK", "yes"),
        // получать уведомления о входящих сообщениях
        "deviceWebhook" => ENV("DEVICE_WEBHOOK", "no"),
        // получать уведомления об устройстве. Уведомление временно не работает.
        "stateWebhook" => ENV("STATE_WEBHOOK", "yes"),
        // получать уведомления об изменении состояния авторизации инстанса
        "keepOnlineStatus" => ENV("KEEP_ONLINE_STATUS", "yes"),
        // выставляет статус 'В сети' для вашего аккаунта
        "pollMessageWebhook" => ENV("POLL_MESSAGE_WEBHOOK", "yes"),
        // получать уведомления о создании опроса и голосовании в опросе
        "incomingBlockWebhook" => ENV("INCOMING_BLOCK_WEBHOOK", "yes"),
        // получать уведомления о добавлении чата в список заблокированных контактов. Уведомление временно не работает.
    ],

    'webhooks' => [
        'incomingMessageReceived' => MessageReceived::class, // Входящее сообщение
        'stateInstanceChanged' => InstanceStatusChanged::class, // Статус инстанса
    ],

    'messages' => [
        'types' => [
            'textMessage' => [
                'textMessageData' => 'textMessage',
            ],
            'quotedMessage' => [
                'extendedTextMessageData' => 'text',
            ],
        ],
    ],
];
