<?php

namespace Tests\Feature\GreenApiManagement;

use App\Enums\InstanceStatus;
use App\Models\Instance;
use App\Models\User;
use App\Services\GreenApi\QRCode\QRCodeApiInterface;
use App\Services\GreenApi\QRCode\QRCodeManager;
use App\Services\GreenApi\QRCode\QRCodeManagerInterface;
use App\Services\GreenApi\QRCode\QRCodeResponseDTO;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\GraphqlTestCase;

class GetInstanceQRCodeApiTest extends GraphqlTestCase
{
    use RefreshDatabase;

    protected QRCodeResponseDTO $instanceDTO;

    protected function setUp(): void
    {
        parent::setUp();

        $mockQRCodeRequester = Mockery::mock(QRCodeApiInterface::class);
        $mockQRCodeRequester->shouldReceive('getQR')
            ->once()
            ->andReturn($this->instanceDTO = new QRCodeResponseDTO('qrCode', 'test_instance_qr_code_in_base64'));

        $fakeManager = new QRCodeManager($mockQRCodeRequester);

        $this->app->instance(QRCodeManagerInterface::class, $fakeManager);
    }

    public function test_user_can_get_instance_qr_code(): void
    {
        $user = User::factory()->create();

        $instance = Instance::factory()->create([
            'user_id' => $user->getKey(),
            'status' => InstanceStatus::INACTIVE,
        ]);

        $params = [
            'id' => $instance->getKey(),
        ];

        $response = $this->graphQL('
          query getInstanceQRCode($id: ID!) {
            getInstanceQRCode(id: $id){
                type
                message
            }
          }', $params);

        $response->assertJson([
            'data' => [
                'getInstanceQRCode' => [
                    'type' => 'qrCode',
                    'message' => 'test_instance_qr_code_in_base64',
                ]
            ]
        ]);
    }
}
