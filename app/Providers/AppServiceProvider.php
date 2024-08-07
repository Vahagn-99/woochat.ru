<?php

namespace App\Providers;

use App\Services\Whatsapp\Instance\CreateInstanceApi;
use App\Services\Whatsapp\Instance\CreateInstanceApiInterface;
use App\Services\Whatsapp\Instance\InstanceService;
use App\Services\Whatsapp\Instance\InstanceServiceInterface;
use App\Services\Whatsapp\QRCode\QRCodeApi;
use App\Services\Whatsapp\QRCode\QRCodeApiInterface;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {

    }

    public function boot(): void
    {
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url') . "/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });
    }
}
