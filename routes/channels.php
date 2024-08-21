<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('instances.{instanceId}', function (User $user) {
    return $user instanceof User;
});
