<?php

namespace App\Events\AmoCrm;

use App\DTO\AmoAccountInfoDTO;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WidgetInstalled
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public AmoAccountInfoDTO $amoAccountInfoDTO)
    {
    }
}
