<?php

namespace App\Providers;

use App\Enums\InstanceStatus;
use App\Services\Whatsapp\Instance\InstanceApi;
use App\Services\Whatsapp\Instance\InstanceApiInterface;
use App\Services\Whatsapp\Instance\InstanceService;
use App\Services\Whatsapp\Instance\InstanceServiceInterface;
use App\Services\Whatsapp\QRCode\QRCodeApi;
use App\Services\Whatsapp\QRCode\QRCodeApiInterface;
use GraphQL\Type\Definition\PhpEnumType;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\ServiceProvider;
use Nuwave\Lighthouse\Schema\TypeRegistry;

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

        $typeRegistry = app(TypeRegistry::class);
        $typeRegistry->register(new PhpEnumType(InstanceStatus::class));
    }
}
