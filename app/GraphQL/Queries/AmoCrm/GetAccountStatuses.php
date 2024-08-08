<?php declare(strict_types=1);

namespace App\GraphQL\Queries\AmoCrm;

use App\Models\Instance;
use App\Services\Whatsapp\DTO\InstanceDTO;
use App\Services\Whatsapp\Facades\Whatsapp;
use App\Services\Whatsapp\QRCode\QRCodeServiceInterface;

final readonly class GetAccountStatuses
{
    /** @param array{} $args */
    public function __invoke(null $_, array $args): array
    {
        return [
            [
                'id' => 1,
                'name' => "Hasmik 1",
            ],
            [
                'id' => 2,
                'name' => "Hasmik 2",
            ],
            [
                'id' => 3,
                'name' => "Hasmik 3",
            ],
            [
                'id' => 4,
                'name' => "Hasmik 4",
            ],
        ];
    }
}
