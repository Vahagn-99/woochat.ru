<?php

namespace App\Jobs;

use App\Services\Whatsapp\DTO\InstanceDTO;
use App\Services\Whatsapp\Instance\InstanceApiInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class DeleteWhatsappInstance implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private readonly InstanceDTO $instance)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(InstanceApiInterface $api): void
    {
        $api->setInstance($this->instance);

        $api->deleteInstance();
    }
}
