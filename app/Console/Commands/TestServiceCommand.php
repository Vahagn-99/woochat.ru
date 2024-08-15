<?php

namespace App\Console\Commands;

use AmoCRM\Client\AmoCRMApiClient;
use App\DTO\dct\AmoDctDTO;
use App\Services\AmoCRM\Core\Facades\Amo;
use Illuminate\Console\Command;

class TestServiceCommand extends Command
{
    protected $signature = 'test:service';

    protected $description = 'The command to test services';

    public function handle(): void
    {
        dd(Amo::main());
    }
}
