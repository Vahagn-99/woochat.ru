<?php

namespace Tests\Feature\GreenApiManagement;

use App\Enums\InstanceStatus;
use App\Services\Whatsapp\Instance\CreatedInstanceDTO;
use App\Services\Whatsapp\Instance\InstanceApiInterface;
use App\Services\Whatsapp\Instance\InstanceService;
use App\Services\Whatsapp\Instance\InstanceServiceInterface;
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

        $mockGreenApiCreateInstance = Mockery::mock(InstanceApiInterface::class);
        $mockGreenApiCreateInstance->shouldReceive('newInstance')->once()->andReturn($this->instanceDTO = new CreatedInstanceDTO('test_instance_id', 'test_instance_token'));

        $fakeManager = new InstanceService($mockGreenApiCreateInstance);

        $this->app->instance(InstanceServiceInterface::class, $fakeManager);
    }

    public function test_user_can_create_new_instance(): void
    {
        $user = $this->authenticateUser();

        $params = [
            'name' => 'test instance name',
        ];

        $response = $this->graphQL('
          mutation createNewInstance($input: createNewInstanceInput!) {
            createNewInstance(input: $input)
          }', [
            'input' => $params,
        ]);

        $response->assertSee('success');

        $this->assertDatabaseHas('instances', [
            'id' => $this->instanceDTO->id,
            'token' => $this->instanceDTO->token,
            'status' => InstanceStatus::NOT_AUTHORIZED,
            'name' => 'test instance name',
            'user_id' => $user->getKey(),
        ]);
    }
}
