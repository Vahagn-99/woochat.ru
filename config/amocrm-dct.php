<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | ID Тарифные планы в сделках аккаунте Админа амосрм
    |--------------------------------------------------------------------------
    |
    */
        'tariffs' => [
            '1231291' => 'тестовый период',
            '1231277' => 'базовый',
            '1231279' => 'расширенный',
            '1231281' => 'профессиональный',
            '1231283' => 'старт ап',
            '1231285' => 'микро бизнес',
            '1231287' => 'расширенный архивный',
            '1231289' => 'архивный',
            '1233767' => 'партнер',
            '1271575' => 'закончилась лицензия',
        ],

    /*
    |--------------------------------------------------------------------------
    | ID полей для создания нотификации об установке виджета
    |--------------------------------------------------------------------------
    |
    */
    'pipeline_id' => 970717,
    'status_id' => 37645075,
    'responsible_user_id' => 3762532,
    'tariff_id' => 608617,
    'account_id' => 606973,
    'user_count_id' => 608623,
    'paid_till_id' => 598055,

    /*
    |--------------------------------------------------------------------------
    | Данные управляюшего виджета
    |--------------------------------------------------------------------------
    |
    */
    'widget' => [
        'client_id' => env('DCT_AMOCRM_CLIENT_ID', '99d7c33b-1d67-477b-9614-844a2eb3bcd7'),
        'domain' => env('DCT_AMOCRM_CLIENT_DOMAIN', 'tech8.amocrm.ru'),
        'client_secret' => env(
            'DCT_AMOCRM_CLIENT_SECRET',
            'zb3ZSIQ5BxcKGqYeFf44O14t84IqHjRNxeTEUN8VRB8poIix3Jm3ZBDauRlNrbYm'
        ),
        'redirect_url' => env('DCT_AMOCRM_REDIRECT_URL', 'https://api.woochat.ru/api/amocrm/dct/auth/callback'),
        'account_delete' => env('DCT_AMOCRM_ACCOUNT_DELETE', 'https://api.woochat.ru/api/amocrm/widget/delete'),
    ],

    'private-auth' => [
        'subdomain' => env('DCT_AMOCRM_SUBDOMAIN', 'tech8'),
        'username' => env('DCT_AMOCRM_PRIVATE_AUTH_USERNAME', 'widget.dev@dicitech.com'),
        'password' => env('DCT_AMOCRM_PRIVATE_AUTH_PASSWORD', 'Njfi23kcnd9j!njcc'),
        'csrf_token' => env('DCT_AMOCRM_CSRF_TOKEN', 'def50200783259495261092966bb57daf48d594650df9625bea6afeac6954719f13e6acba3046999e6f068d24168a2580ab8c670baa6ce0b6de552d64fcf113f8baacac213d6e24840343be08e837ddf1ae9a5a0ca624ad6d043f6ff8090e0a70371cefbee7ae3b7a468e5254ef4372dd9068507767ee3c6093730c6baf00f2dc0a794da9d4138cfaa48593a785702f66ad01b5ae5cf89ce88043a11ccd5c0af6991e1eea41fbe88f9625d5561eeffa07e35f88ea80f9f2b71e4f8642590efb3a282ac62cdddf3d3db7d310c8f5574dd2ce7feb71bced40658192af0ceb1165ed624a099395539b3da827d7485d9745c9195576f4bda19e160dfe643aaf2f93a598b078994042eaf580e2f'),
    ]
];
