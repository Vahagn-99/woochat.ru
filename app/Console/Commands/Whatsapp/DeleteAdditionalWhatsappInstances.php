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
    protected $signature = 'delete:instances';

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
        $instances = WhatsappInstance::whereFree()->skip(1)->get();

        foreach ($instances as $instance) {
            Whatsapp::for($instance)->instance()->delete();
            $instance->delete();

            do_log('crones/delete_instances'.now()->toDateTimeString())->info("The instance with ID: {$instance->id}  was deleted");
        }
    }
}
