<?php

return [
    'partner' => [
        'api_url' => ENV('GREENAPI_PARTNER_API_URL', 'https://api.green-api.com'),
        'api_token' => ENV('GREENAPI_PARTNER_API_TOKEN', 'gac.1c81d6d37a3145dd8e4240ed3fa24852938ae575550741'),
    ],
    'instance' => [
        "webhookUrl" => ENV('GREENAPI_WEBHOOK_API_URL', 'https://api.green-api.com'),
//        "webhookUrlToken" => "f93537eb3e8fed66847b5bd",
//        "delaySendMessagesMilliseconds" => 1000,
//        "markIncomingMessagesReaded" => "no",
//        "markIncomingMessagesReadedOnReply" => "no",
        "outgoingAPIMessageWebhook" => "yes",
        "outgoingWebhook" => "yes",
        "outgoingMessageWebhook" => "yes",
        "incomingWebhook" => "yes",
//        "deviceWebhook" => "no",
//        "stateWebhook" => "no",
//        "keepOnlineStatus" => "no",
//        "pollMessageWebhook" => "no",
//        "incomingBlockWebhook" => "yes",
//        "incomingCallWebhook" => "yes"
    ]
];
