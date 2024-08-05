<?php

namespace App\Services\GreenApi;

use App\Enums\InstanceStatus;
use App\Services\GreenApi\ClientService\GreenApiServiceInterface;
use App\Services\GreenApi\Instance\InstanceServiceInterface;
use App\Services\GreenApi\Messaging\MessagingServiceInterface;
use App\Services\GreenApi\QRCode\QRCodeServiceInterface;

interface GreenManagerInterface
{

    public function api(): GreenApiServiceInterface;

    public function qr(): QRCodeServiceInterface;

    public function instance(): InstanceServiceInterface;

    public function status(): InstanceStatus;

    public function massaging(): MessagingServiceInterface;
}