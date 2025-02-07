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
        "quoted",
        "reaction",
    ],

    /*
    |--------------------------------------------------------------------------
    | Статусы доставки собшения
    |--------------------------------------------------------------------------
    */
    'delivery_statuses' => [
        'sent',
        'delivered',
        'read',
        'failed',
        'cancelled',
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
                    'picture' => App\Services\Whatsapp\Messaging\Adapters\AmoChat\PictureAdapter::class,
                    'audio' => App\Services\Whatsapp\Messaging\Adapters\AmoChat\VoiceAdapter::class,
                    'video' => App\Services\Whatsapp\Messaging\Adapters\AmoChat\VideoAdapter::class,
                    'voice' => App\Services\Whatsapp\Messaging\Adapters\AmoChat\VoiceAdapter::class,
                    'text' => App\Services\Whatsapp\Messaging\Adapters\AmoChat\TextAdapter::class,
                    'quoted' => App\Services\Whatsapp\Messaging\Adapters\AmoChat\QuotedAdapter::class,
                    'reaction' => App\Services\Whatsapp\Messaging\Adapters\AmoChat\ReactionAdapter::class,
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
                    'type' => 'text',
                    'local_type' => 'extendedTextMessage',
                    'model' => App\Services\Whatsapp\Messaging\Types\Text::class,
                ],
                [
                    'type' => 'picture',
                    'local_type' => 'imageMessage',
                    'model' => App\Services\Whatsapp\Messaging\Types\File::class,
                ],
                [
                    'type' => 'voice',
                    'local_type' => 'audioMessage',
                    'model' => App\Services\Whatsapp\Messaging\Types\File::class,
                ],
                [
                    'type' => 'audio',
                    'local_type' => 'audioMessage',
                    'model' => App\Services\Whatsapp\Messaging\Types\File::class,
                ],
                [
                    'type' => 'file',
                    'local_type' => 'documentMessage',
                    'model' => App\Services\Whatsapp\Messaging\Types\File::class,
                ],
                [
                    'type' => 'video',
                    'local_type' => 'videoMessage',
                    'model' => App\Services\Whatsapp\Messaging\Types\File::class,
                ],
                [
                    'type' => 'quoted',
                    'local_type' => 'quotedMessage',
                    'model' => App\Services\Whatsapp\Messaging\Types\Text::class,
                ],
                [
                    'type' => 'reaction',
                    'local_type' => 'reactionMessage',
                    'model' => App\Services\Whatsapp\Messaging\Types\Text::class,
                ],
            ],
            'delivery_status' => [
                'sent' => 'sent',
                'delivered' => 'delivered',
                'read' => 'read',
                'failed' => 'failed',
            ],
        ],

        'amochat' => [
            'adapters' => [
                'whatsapp' => [
                    'file' => App\Services\AmoChat\Messaging\Adapters\Whatsapp\FileAdapter::class,
                    'picture' => App\Services\AmoChat\Messaging\Adapters\Whatsapp\FileAdapter::class,
                    'audio' => App\Services\AmoChat\Messaging\Adapters\Whatsapp\FileAdapter::class,
                    'video' => App\Services\AmoChat\Messaging\Adapters\Whatsapp\FileAdapter::class,
                    'voice' => App\Services\AmoChat\Messaging\Adapters\Whatsapp\FileAdapter::class,
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
                [
                    'type' => 'reaction',
                    'local_type' => 'reaction',
                    'model' => App\Services\AmoChat\Messaging\Types\Text::class,
                ],
            ],
            'delivery_status' => [
                'sent' => 0,
                'delivered' => 1,
                'read' => 2,
                'failed' => -1,
            ],
        ],
    ],
];
