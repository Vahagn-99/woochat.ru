<?php

namespace Tests\Feature\GreenApiManagement;

use App\Events\GreenApi\Webhooks\IncomingCall;
use App\Events\GreenApi\Webhooks\IncomingMessageReceived;
use App\Events\GreenApi\Webhooks\StateInstanceChanged;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;
use Tests\WithAuth;

class CanHandleIncomingWebhookTest extends TestCase
{
    use RefreshDatabase;
    use WithAuth;

    /**
     * Data provider for incoming webhook tests.
     *
     * @return array[]
     */
    public static function webhookDataProvider(): array
    {
        return [
            'state Instance Changed' => [
                [
                    'data' => [
                        "typeWebhook" => "stateInstanceChanged",
                        "instanceData" => [
                            "idInstance" => 1,
                            "wid" => "some_wid",
                            "typeInstance" => "whatsapp"
                        ],
                        "timestamp" => 1586700690,
                        "stateInstance" => "authorized"
                    ],
                    // assertion
                    'event' => StateInstanceChanged::class,
                ],
            ],
            'incoming Message Received' => [
                [
                    'data' => [
                        "typeWebhook" => "incomingMessageReceived",
                        "instanceData" => [
                            "idInstance" => 1234,
                            "wid" => "11001234567@c.us",
                            "typeInstance" => "whatsapp"
                        ],
                        "timestamp" => 1588091580,
                        "idMessage" => "F7AEC1B7086ECDC7E6E45923F5EDB825",
                        "senderData" => [
                            "chatId" => "79001234568@c.us",
                            "sender" => "79001234568@c.us",
                            "chatName" => "Иван",
                            "senderName" => "Иван",
                            "senderContactName" => "Иван Васильевич"
                        ],
                        "messageData" => [
                            "typeMessage" => "quotedMessage",
                            "extendedTextMessageData" => [
                                "text" => "Ответ",
                                "stanzaId" => "B4AA239D112CB2576897B3910FEDE26E",
                                "participant" => "79001230000@c.us"
                            ],
                            "quotedMessage" => [
                                "stanzaId" => "9A73322488DCB7D9689A6112F2528C9D",
                                "participant" => "79061230000@c.us",
                                "typeMessage" => "imageMessage",
                                "downloadUrl" => "",
                                "caption" => "",
                                "jpegThumbnail" => ""
                            ]
                        ]
                    ],
                    'event' => IncomingMessageReceived::class
                ]
            ],
            'incoming Call' => [
                [
                    'data' => [
                        "from" => "79001234500@c.us",
                        "typeWebhook" => "incomingCall",
                        "instanceData" => [
                            "idInstance" => 1101123456,
                            "wid" => "11001234567@c.us",
                            "typeInstance" => "whatsapp"
                        ],
                        "status" => "pickUp",
                        "timestamp" => 1617691757,
                        "idMessage" => "104179EDB7F5328988D8834107EEBE50"
                    ],
                    'event' => IncomingCall::class
                ]
            ]
        ];
    }

    #[DataProvider('webhookDataProvider')]
    public function test_can_handle_incoming_webhook_from_green_api(array $params): void
    {
        $webhookData = $params['data'];
        $event = $params['event'];

        Event::fake([$event]);

        $response = $this->post('/webhooks/greenapi', $webhookData);
        $response->assertStatus(200);

        Event::assertDispatched($event);
    }
}