<?php

namespace App\Services\GreenApi;

use App\Services\GreenApi\ClientService\GreenApiServiceInterface;
use App\Services\GreenApi\Instance\InstanceServiceInterface;
use App\Services\GreenApi\QRCode\QRCodeServiceInterface;

class GreenManager implements GreenManagerInterface
{
    public function __construct(
        private readonly GreenApiServiceInterface $apiService,
        private readonly QRCodeServiceInterface   $qrCodeService,
        private readonly InstanceServiceInterface $instanceService,
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
}