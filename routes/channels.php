<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::routes(['middleware' => ['web']]);

Broadcast::channel('instances.{instanceId}', function (User $user) {
    return true;
});
