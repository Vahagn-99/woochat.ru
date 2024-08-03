<?php

namespace Tests;

use App\Models\User;

trait WithAuth
{
    private function authenticateUser(array $params = [], string $guard = null): User
    {
        $user = User::factory()->create($params);
        $this->actingAs($user, $guard);
        return $user;
    }
}