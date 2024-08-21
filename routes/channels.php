<?php

use App\Models\User;
use App\Models\WhatsappInstance;
use Illuminate\Support\Facades\Broadcast;

Broadcast::routes(['middleware' => ['auth:sanctum']]);

Broadcast::channel('instances.{instanceId}', function (User $user, $instanceId) {
    return WhatsappInstance::query()->findOrFail($instanceId)->user_id === $user->id;
});
