<?php declare(strict_types=1);

namespace App\GraphQL\Queries\AmoCrm;

use App\Models\Instance;
use App\Services\Whatsapp\DTO\InstanceDTO;
use App\Services\Whatsapp\Facades\Whatsapp;
use App\Services\Whatsapp\QRCode\QRCodeServiceInterface;

final readonly class GetAccountPipelines
{
    /** @param array{} $args */
    public function __invoke(null $_, array $args): array
    {
        // amocrm api call

        return [
            [
                'id' => 1,
                'statuses' => [
                    [
                        'id' => 1,
                    ],
                    [
                        'id' => 2,
                    ]
                ]
            ],
            [
                'id' => 2,
                'statuses' => [
                    [
                        'id' => 3,
                    ],
                    [
                        'id' => 4,
                    ]
                ]
            ],
        ];
    }
}
