<?php

namespace App\Console\Commands\Whatsapp;

use App\Models\WhatsappInstance;
use App\Services\Whatsapp\Facades\Whatsapp;
use Exception;
use Illuminate\Console\Command;

class DeleteUnused extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whatsapp:delete-unused-instances {--id=}';

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
        if ($this->option('id')) {
            $instances = WhatsappInstance::query()
                ->where('id', $this->option('id'))
                ->get();
        } else {
            $instances = WhatsappInstance::whereFree()
                ->get()
                ->slice(1);
        }

        foreach ($instances as $instance) {
            try {
                try {
                    Whatsapp::for($instance)->instance()->delete();
                } catch (Exception) {
                    do_log('crones/delete-unused-instances')->info("Не удалось удалить инстанс ID: {$instance->id} через API GreenAPI.");

                    continue;
                }

                $instance->delete();

                do_log('crones/delete-unused-instances')->info("Инстанс ID: {$instance->id} успешно удален.");
            } catch (Exception $e) {
                do_log('crones/delete-unused-instances')->info("Не удалось удалить инстанс ID: {$instance->id}.");

                $this->warn($e);
            }
        }
    }
}
