<?php

namespace App\Events\Messengers\Whatsapp;

use App\Models\Settings;
use App\Models\WhatsappInstance;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SettingsSaved
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public WhatsappInstance $instance,
        public Settings $settings
    ) {
        //
    }
}
