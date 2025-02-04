<?php

namespace App\Policies;

use App\Models\User;

class SettingsPolicy
{
    public function save(User $user): bool
    {
        return $user->active_subscription()->exists();
    }
}
