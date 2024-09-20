<?php

namespace App\Policies;

use App\Models\User;

class WhatsappInstancePolicy
{
    public function save(User $user): bool
    {
        return $user->activeSubscription()->exists();
    }

    public function delete(User $user): bool
    {
        return $user->activeSubscription()->exists();
    }
}
