<?php

namespace App\Console\Commands\GreenApi;

use App\Models\Instance;
use App\Services\GreenApi\Facades\GreenApi;
use Illuminate\Console\Command;

class SyncInstanceStatus extends Command
{
    protected $signature = 'sync:instance {instance}';

    protected $description = 'The command to sync instance status';

    public function handle(): void
    {
        $instance = Instance::query()->find($this->argument('instance'));

        do {
            $status = GreenApi::fromModel($instance)->status();
            sleep(2);
        } while ($status->isStarting());

        $instance->update(['status' => $status]);

        $this->info("The instance status was changed to: " . $status->value);
    }
}
