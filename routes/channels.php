<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('instances.{instanceId}', function () {
    return true;
});
