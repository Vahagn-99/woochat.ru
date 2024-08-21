<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::routes();

Broadcast::channel('instances.{instanceId}', function (User $user) {
    return true;
});
