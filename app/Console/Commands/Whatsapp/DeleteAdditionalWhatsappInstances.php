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
        // Get all free instances
        $instances = WhatsappInstance::whereFree()->get()->slice(1);
        dd($instances);

        foreach ($instances as $instance) {
            $deleted = Whatsapp::for($instance)->instance()->delete();

            if (! $deleted) {
                $this->warn("Не удалось удалить экземпляр WhatsApp. {$instance->id}");

                continue;
            }

            $instance->delete();

            do_log('crones/delete_instances'.now()->toDateTimeString())->info("The instance with ID: {$instance->id}  was deleted");
        }
    }
}
