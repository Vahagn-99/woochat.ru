<?php

namespace App\Providers;

use App\Services\GreenApi\Instance\CreateInstanceApi;
use App\Services\GreenApi\Instance\CreateInstanceApiInterface;
use App\Services\GreenApi\Instance\InstanceService;
use App\Services\GreenApi\Instance\InstanceServiceInterface;
use App\Services\GreenApi\QRCode\QRCodeApi;
use App\Services\GreenApi\QRCode\QRCodeApiInterface;
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
