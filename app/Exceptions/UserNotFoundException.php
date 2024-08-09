<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response as ResponseCode;

class UserNotFoundException extends Exception
{
    public static function byDomain(mixed $domain): static
    {
        return new static("User with domain {$domain} not found", ResponseCode::HTTP_NOT_FOUND);
    }
}
