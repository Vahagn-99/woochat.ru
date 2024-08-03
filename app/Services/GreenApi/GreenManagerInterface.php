<?php

namespace App\Services\GreenApi;

use App\Services\GreenApi\ClientService\GreenApiServiceInterface;
use App\Services\GreenApi\Instance\InstanceServiceInterface;
use App\Services\GreenApi\QRCode\QRCodeServiceInterface;

interface GreenManagerInterface
{

    public function api(): GreenApiServiceInterface;

    public function qr(): QRCodeServiceInterface;

    public function instance(): InstanceServiceInterface;
}