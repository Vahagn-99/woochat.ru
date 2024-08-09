<?php

declare(strict_types=1);

namespace App\Services\Logger;

use Illuminate\Support\Facades\Log;
use Psr\Log\LoggerInterface;

class DoLog
{
    public function file(string $file): LoggerInterface
    {
        return Log::build([
            'driver' => 'do_log',
            'path' => storage_path('logs/'.$file.'.log'),
        ]);
    }
}
