<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Все доступные типы для для передачи собшениея
    |--------------------------------------------------------------------------
    |
    |
    */
    'types' => [
        "text",
        "file",
        "video",
        "picture",
        "voice",
        "audio",
        "quoted",
    ],
    /*
    |--------------------------------------------------------------------------
    | Провайдеры собшения
    |--------------------------------------------------------------------------
    | по умалчанию толко ватсап и амосрм
    |
    */
    'providers' => [

        'whatsapp' => [
            'adapters' => [
                'amochat' => [
                    'file' => App\Services\Whatsapp\Messaging\Adapters\AmoChat\FileAdapter::class,
                    'picture' => App\Services\Whatsapp\Messaging\Adapters\AmoChat\FileAdapter::class,
                    'audio' => App\Services\Whatsapp\Messaging\Adapters\AmoChat\FileAdapter::class,
                    'video' => App\Services\Whatsapp\Messaging\Adapters\AmoChat\FileAdapter::class,
                    'voice' => App\Services\Whatsapp\Messaging\Adapters\AmoChat\FileAdapter::class,
                    'text' => App\Services\Whatsapp\Messaging\Adapters\AmoChat\TextAdapter::class,
                    'quoted' => App\Services\Whatsapp\Messaging\Adapters\AmoChat\QuotedAdapter::class,
                ],
            ],
            'settings' => [],
            'schema' => [
                [
                    'type' => 'text',
                    'local_type' => 'textMessage',
                    'model' => App\Services\Whatsapp\Messaging\Types\Text::class,
                ],
                [
                    'type' => 'text',
                    'local_type' => 'extendedTextMessageData',
                    'model' => App\Services\Whatsapp\Messaging\Types\Text::class,
                ],
                [
                    'type' => 'file',
                    'local_type' => 'imageMessage',
                    'model' => App\Services\Whatsapp\Messaging\Types\File::class,
                ],
                [
                    'type' => 'file',
                    'local_type' => 'audioMessage',
                    'model' => App\Services\Whatsapp\Messaging\Types\File::class,
                ],
                [
                    'type' => 'file',
                    'local_type' => 'documentMessage',
                    'model' => App\Services\Whatsapp\Messaging\Types\File::class,
                ],
                [
                    'type' => 'file',
                    'local_type' => 'videoMessage',
                    'model' => App\Services\Whatsapp\Messaging\Types\File::class,
                ],
                [
                    'type' => 'quotedMessage',
                    'local_type' => 'quoted',
                    'model' => App\Services\Whatsapp\Messaging\Types\Text::class,
                ],
            ],
        ],

        'amochat' => [
            'adapters' => [
                'whatsapp' => [
                    'file' => App\Services\AmoChat\Messaging\Adapters\Whatsapp\FileAdapter::class,
                    'text' => App\Services\AmoChat\Messaging\Adapters\Whatsapp\TextAdapter::class,
                ],
            ],
            'settings' => [],
            'schema' => [
                [
                    'type' => 'text',
                    'model' => App\Services\AmoChat\Messaging\Types\Text::class,
                    'local_type' => 'text',
                ],
                [
                    'type' => 'file',
                    'model' => App\Services\AmoChat\Messaging\Types\File::class,
                    'local_type' => 'file',
                ],
                [
                    'type' => 'video',
                    'model' => App\Services\AmoChat\Messaging\Types\Video::class,
                    'local_type' => 'video',
                ],
                [
                    'type' => 'picture',
                    'model' => App\Services\AmoChat\Messaging\Types\Picture::class,
                    'local_type' => 'picture',
                ],
                [
                    'type' => 'voice',
                    'model' => App\Services\AmoChat\Messaging\Types\Voice::class,
                    'local_type' => 'voice',
                ],
                [
                    'type' => 'audio',
                    'model' => App\Services\AmoChat\Messaging\Types\Audio::class,
                    'local_type' => 'audio',
                ],
                [
                    'type' => 'sticker',
                    'model' => App\Services\AmoChat\Messaging\Types\Sticker::class,
                    'local_type' => 'sticker',
                ],
                [
                    'type' => 'quoted',
                    'local_type' => 'quoted',
                    'model' => App\Services\AmoChat\Messaging\Types\Text::class,
                ],
            ],
        ],
    ],
];