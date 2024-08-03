<?php

namespace Tests\Feature\GreenApiManagement;

use App\Enums\InstanceStatus;
use App\Models\User;
use App\Services\GreenApi\Instance\CreatedInstanceDTO;
use App\Services\GreenApi\Instance\CreateInstanceApiInterface;
use App\Services\GreenApi\Instance\InstanceManager;
use App\Services\GreenApi\Instance\InstanceManagerInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\GraphqlTestCase;

class CreateInstanceApiTest extends GraphqlTestCase
{
    use RefreshDatabase;

    protected CreatedInstanceDTO $instanceDTO;

    protected function setUp(): void
    {
        parent::setUp();

        $mockGreenApiCreateInstance = Mockery::mock(CreateInstanceApiInterface::class);
        $mockGreenApiCreateInstance->shouldReceive('newInstance')
            ->once()
            ->andReturn($this->instanceDTO = new CreatedInstanceDTO('test_instance_id', 'test_instance_token', 'test_instance_type'));

        $fakeManager = new InstanceManager($mockGreenApiCreateInstance);

        $this->app->instance(InstanceManagerInterface::class, $fakeManager);
    }

    public function test_user_can_create_new_instance(): void
    {
        $user = User::factory()->create();

        $params = [
            'name' => 'test instance name',
            'user_id' => $user->getKey(),
        ];

        $response = $this->graphQL('
          mutation createNewInstance($input: createNewInstanceInput!) {
            createNewInstance(input: $input)
          }', [
            'input' => $params
        ]);

        $response->assertSee('success');

        $this->assertDatabaseHas('instances', [
            'id' => $this->instanceDTO->id,
            'token' => $this->instanceDTO->token,
            'status' => InstanceStatus::INACTIVE,
            'name' => 'test instance name',
            'user_id' => $user->getKey(),
        ]);
    }
}
