<?php

namespace App\Console\Commands\Whatsapp;

use App\Models\WhatsappInstance;
use App\Services\Whatsapp\Facades\Whatsapp;
use Illuminate\Console\Command;

class DeleteAdditionalWhatsappInstances extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:instances {--id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete whatsapp instances';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        // Get all free instances
        if ($this->option('id')) {
            $instances = WhatsappInstance::query()->where('id', $this->option('id'))->get();
        } else {
            $instances = WhatsappInstance::whereFree()->get()->slice(1);
        }

        foreach ($instances as $instance) {
            try {
                Whatsapp::for($instance)->instance()->delete();

                $instance->delete();

                do_log('crones/delete_instances')->info("экземпляр ID: {$instance->id}  удален.");
            } catch (\Exception $e) {
                $this->warn($e);
            }
        }
    }
}
