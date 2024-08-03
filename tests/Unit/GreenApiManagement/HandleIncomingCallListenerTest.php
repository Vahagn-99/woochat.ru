<?php

namespace Tests\Unit\GreenApiManagement;

use App\Enums\InstanceStatus;
use App\Events\Webhooks\GreenApi\StateInstanceChanged;
use App\Listeners\Webhooks\GreenApi\HandleStateInstance;
use App\Models\Instance;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HandleIncomingCallListenerTest extends TestCase
{
    use RefreshDatabase;

    public function test_handle_incoming_call_listener_test(): void
    {
        $user = User::factory()->create();

        $instance = Instance::factory()->create([
            'user_id' => $user->getKey(),
            'status' => InstanceStatus::NOT_AUTHORIZED
        ]);

        $listener = new HandleStateInstance();
        $listener->handle(new StateInstanceChanged([
            "typeWebhook" => "stateInstanceChanged",
            "instanceData" => [
                "idInstance" => $instance->getKey(),
                "wid" => "some_wid",
                "typeInstance" => "whatsapp"
            ],
            "timestamp" => 1586700690,
            "stateInstance" => "authorized"
        ]));

        $instance->refresh();

        $this->assertTrue($instance->status->isAuthorized());
        $this->assertTrue($instance->phone === "some_wid");
    }
}
