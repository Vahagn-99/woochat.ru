<?php

namespace App\Console\Commands;

use App\Services\Whatsapp\DTO\InstanceDTO;
use App\Services\Whatsapp\Facades\Whatsapp;
use App\Services\Whatsapp\Messaging\Types\Contact;
use App\Services\Whatsapp\Messaging\Types\Text;
use Illuminate\Console\Command;

class TestServiceCommand extends Command
{
    protected $signature = 'test:service';


    protected $description = 'The command to test services';

    public function handle(): void
    {
        Whatsapp::for(new InstanceDTO("5700100556", "9f088bd7a2cc4bcf80d8ab0fab36177945be34bb15f946e0a1"));

        $message = new Contact("37493270709@c.us", [
            'name' => 'John Doe',
            'email' => 'john@doe.com',
            'phone' => '555-555-5555',
        ]);

        $response = Whatsapp::massaging()->send($message);
        dd($response);
    }
}
