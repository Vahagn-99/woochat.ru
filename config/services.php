<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'amocrm' => [
        'client_id' => env('AMOCRM_CLIENT_ID', '02b4822e-1b63-4f2f-a0c9-d178c9527444'),
        'client_secret' => env('AMOCRM_CLIENT_SECRET', '5VO0DErbk6esWmKNM1N3U5pEUe1STi5RK5uNpcP3EADEOf24YpbzFbDRpkM5O1jL'),
        'redirect_url' => env('AMOCRM_REDIRECT_URL', 'https://api.woochat.ru/api/amocrm/auth/callback'),
        'account_delete' => env('AMOCRM_ACCOUNT_DELETE', 'https://api.woochat.ru/api/amocrm/widget/delete'),
    ],
];
