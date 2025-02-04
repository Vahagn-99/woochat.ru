<?php

namespace App\Services\AmoChat\Hmac;

use Exception;

class Hmac
{
    /**
     * @throws Exception
     */
    public static function make(
        string $method,
        string $secret,
        string $contentType,
        string $date,
        string $path,
        array  $body = []
    ): bool|string
    {
        $requestBody = json_encode($body);
        $checkSum = md5($requestBody);

        $str = implode("\n", [
            strtoupper($method),
            $checkSum,
            $contentType,
            $date,
            $path,
        ]);

        return hash_hmac('sha1', $str, $secret);
    }
}