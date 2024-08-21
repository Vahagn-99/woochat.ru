<?php

use App\Models\User;
use App\Models\WhatsappInstance;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('instances.{instanceId}', function (User $user, int $instanceId) {
    return $user->id === WhatsappInstance::query()->find($instanceId)->user_id;
});
