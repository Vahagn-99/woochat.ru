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
        '1231291' => 'тестовый_период',
        '1231277' => 'базовый',
        '1231279' => 'расширенный',
        '1231281' => 'профессиональный',
        '1231283' => 'старт_ап',
        '1231285' => 'микро_бизнес',
        '1231287' => 'расширенный_архивный',
        '1231289' => 'архивный',
        '1233767' => 'партнер',
        '1271575' => 'закончилась_лицензия',
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
    'contact_cf_id' => 608617,
    'leads_cf_id' => 598055,
    'amocrm_id_cf_id' => 606973,
    'user_count_cf_id' => 608623,
    'paid_till_cf_id' => 608615,

    /*
    |--------------------------------------------------------------------------
    | Данные управляюшего виджета
    |--------------------------------------------------------------------------
    |
    */
    'widget' => [
        'client_id' => env('DCT_AMOCRM_CLIENT_ID', '99d7c33b-1d67-477b-9614-844a2eb3bcd7'),
        'domain' => env('DCT_AMOCRM_CLIENT_DOMAIN', 'tech8.amocrm.ru'),
        'client_secret' => env('DCT_AMOCRM_CLIENT_SECRET', 'zb3ZSIQ5BxcKGqYeFf44O14t84IqHjRNxeTEUN8VRB8poIix3Jm3ZBDauRlNrbYm'),
        'redirect_url' => env('DCT_AMOCRM_REDIRECT_URL', 'https://united-gopher-amazing.ngrok-free.app/api/amocrm/dct/auth/callback'),
        'account_delete' => env('DCT_AMOCRM_ACCOUNT_DELETE', 'https://united-gopher-amazing.ngrok-free.app/api/amocrm/widget/delete'),
    ],
];