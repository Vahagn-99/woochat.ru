<?php

namespace Tests\Feature\GreenApiManagement;

use App\Enums\InstanceStatus;
use App\Models\WhatsappInstance;
use App\Models\User;
use App\Services\Whatsapp\QRCode\QRCodeApiInterface;
use App\Services\Whatsapp\QRCode\QRCodeService;
use App\Services\Whatsapp\QRCode\QRCodeServiceInterface;
use App\Services\Whatsapp\QRCode\QRCodeResponseDTO;
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

        $fakeManager = new QRCodeService($mockQRCodeRequester);

        $this->app->instance(QRCodeServiceInterface::class, $fakeManager);
    }

    public function test_user_can_get_instance_qr_code(): void
    {
        $user = User::factory()->create();

        $instance = WhatsappInstance::factory()->create([
            'user_id' => $user->getKey(),
            'status' => InstanceStatus::NOT_AUTHORIZED,
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
