<?php

declare(strict_types=1);

namespace App\Base\Subscription\Subscribe;

enum ResultStatus: string
{
    case SUCCESS = 'success';
    case FAIL = 'fail';
}
