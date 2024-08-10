<?php

namespace App\Events\AmoCRM;

use App\DTO\AmoAccountInfoDTO;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WidgetInstalled
{
    use Dispatchable, SerializesModels;

    public function __construct(public AmoAccountInfoDTO $amoAccountInfoDTO)
    {
    }
}
