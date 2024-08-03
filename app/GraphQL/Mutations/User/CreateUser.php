<?php declare(strict_types=1);

namespace App\GraphQL\Mutations\User;

use App\Models\User;

final readonly class CreateUser
{
    /** @param array{} $args */
    public function __invoke(null $_, array $args): User
    {
        $input = $args['input'];
        logger($args);
        return User::query()->create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => bcrypt($input['password'] ?? 'password'),
        ]);

    }
}
