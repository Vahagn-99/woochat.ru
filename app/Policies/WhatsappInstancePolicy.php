<?php

namespace App\Policies;

use App\Models\User;

class WhatsappInstancePolicy
{
    public function save(User $user): bool
    {
        return $user->active_subscription()->exists();
    }

    public function delete(User $user): bool
    {
        return $user->active_subscription()->exists();
    }
}
