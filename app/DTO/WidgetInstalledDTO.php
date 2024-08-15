<?php

namespace App\DTO;

class WidgetInstalledDTO
{
    public function __construct(
        public NewAmoUserDTO   $user,
        public AmoAccountInfoDTO $info
    )
    {
    }
}