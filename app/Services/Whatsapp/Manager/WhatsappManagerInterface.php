<?php

namespace App\Services\Whatsapp\Manager;

use App\Enums\InstanceStatus;
use App\Services\Whatsapp\ClientService\WhatsappApiServiceInterface;
use App\Services\Whatsapp\Instance\InstanceServiceInterface;
use App\Services\Whatsapp\Messaging\WhatsappMessagingInterface;
use App\Services\Whatsapp\QRCode\QRCodeServiceInterface;

interface WhatsappManagerInterface
{

    public function api(): WhatsappApiServiceInterface;

    public function qr(): QRCodeServiceInterface;

    public function instance(): InstanceServiceInterface;

    public function status(): InstanceStatus;

    public function messaging(): WhatsappMessagingInterface;
}