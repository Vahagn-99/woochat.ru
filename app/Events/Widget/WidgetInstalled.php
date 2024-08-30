<?php

namespace App\Events\Widget;

use App\DTO\AmoAccountInfoDTO;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WidgetInstalled
{
    use Dispatchable, SerializesModels;

    public function __construct(public User $user, public AmoAccountInfoDTO $amoAccountInfoDTO)
    {
    }
}
