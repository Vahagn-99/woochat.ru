<?php

namespace App\Console\Commands;

use App\Base\Messaging\Factory;
use Illuminate\Console\Command;

class TestServiceCommand extends Command
{
    protected $signature = 'test:service';

    protected $description = 'The command to test any service';

    public function handle(): void
    {

      $status  =   Factory::make()
            ->from('amochat')
            ->to('whatsapp')
            ->getAdaptedStatus("read");

      dd($status);
    }
}
