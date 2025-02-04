<?php

namespace App\Jobs;

use App\Services\Whatsapp\Instance\InstanceApiInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CreateWhatsappInstance implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private readonly array $params)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(InstanceApiInterface $api): void
    {
        $api->newInstance($this->params);
    }
}
