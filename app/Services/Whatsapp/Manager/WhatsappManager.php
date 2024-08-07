<?php

namespace App\Services\Whatsapp\Manager;

use App\Enums\InstanceStatus;
use App\Services\Whatsapp\ClientService\WhatsappApiServiceInterface;
use App\Services\Whatsapp\Instance\GetInstanceStatusServiceInterface;
use App\Services\Whatsapp\Instance\InstanceServiceInterface;
use App\Services\Whatsapp\Messaging\WhatsappMessagingInterface;
use App\Services\Whatsapp\QRCode\QRCodeServiceInterface;

class WhatsappManager implements WhatsappManagerInterface
{
    public function __construct(
        private readonly WhatsappApiServiceInterface       $apiService,
        private readonly QRCodeServiceInterface            $qrCodeService,
        private readonly InstanceServiceInterface          $instanceService,
        private readonly GetInstanceStatusServiceInterface $statusService,
        private readonly WhatsappMessagingInterface        $messagingService
    )
    {
    }

    public function api(): WhatsappApiServiceInterface
    {
        return $this->apiService;
    }

    public function qr(): QRCodeServiceInterface
    {
        return $this->qrCodeService;
    }

    public function instance(): InstanceServiceInterface
    {
        return $this->instanceService;
    }

    public function status(): InstanceStatus
    {
        return $this->statusService->get();
    }

    public function massaging(): WhatsappMessagingInterface
    {
        return $this->messagingService;
    }
}