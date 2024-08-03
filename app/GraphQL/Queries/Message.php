<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

final readonly class Message
{
    /** @param array{} $args */
    public function __invoke(null $_, array $args): string
    {
        return 'Success';
    }
}
