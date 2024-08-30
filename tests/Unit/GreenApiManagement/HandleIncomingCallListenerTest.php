<?php

namespace Tests\Unit\GreenApiManagement;

use App\Enums\InstanceStatus;
use App\Events\Messengers\Whatsapp\InstanceStatusChanged;
use App\Listeners\Messengers\Whatsapp\UpdateInstanceStatus;
use App\Models\User;
use App\Models\WhatsappInstance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HandleIncomingCallListenerTest extends TestCase
{
    use RefreshDatabase;

    public function test_handle_incoming_call_listener_test(): void
    {
        $user = User::factory()->create();

        $instance = WhatsappInstance::factory()->create([
            'user_id' => $user->getKey(),
            'status' => InstanceStatus::NOT_AUTHORIZED
        ]);

        $listener = new UpdateInstanceStatus();
        $listener->handle(new InstanceStatusChanged([
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
