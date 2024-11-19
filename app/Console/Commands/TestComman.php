<?php

namespace App\Console\Commands;

use App\Models\WhatsappInstance;
use App\Services\Whatsapp\Facades\Whatsapp;
use Illuminate\Console\Command;

class TestComman extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:service';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $facade =  Whatsapp::for(WhatsappInstance::first());
        dd($facade->instance());
    }
}
