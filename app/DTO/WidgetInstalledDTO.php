<?php

namespace App\DTO;

class WidgetInstalledDTO
{
    public function __construct(
        public CrateNewUserDTO   $user,
        public AmoAccountInfoDTO $info
    )
    {
    }
}