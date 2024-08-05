<?php

namespace App\Services\GreenApi;

use App\Enums\InstanceStatus;
use App\Services\GreenApi\ClientService\GreenApiServiceInterface;
use App\Services\GreenApi\Instance\GetInstanceStatusServiceInterface;
use App\Services\GreenApi\Instance\InstanceServiceInterface;
use App\Services\GreenApi\Messaging\MessagingServiceInterface;
use App\Services\GreenApi\QRCode\QRCodeServiceInterface;

class GreenManager implements GreenManagerInterface
{
    public function __construct(
        private readonly GreenApiServiceInterface          $apiService,
        private readonly QRCodeServiceInterface            $qrCodeService,
        private readonly InstanceServiceInterface          $instanceService,
        private readonly GetInstanceStatusServiceInterface $statusService,
        private readonly MessagingServiceInterface         $messagingService,
    )
    {
    }

    public function api(): GreenApiServiceInterface
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

    public function massaging(): MessagingServiceInterface
    {
        return $this->messagingService;
    }
}