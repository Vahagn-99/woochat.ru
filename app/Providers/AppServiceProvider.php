<?php

namespace App\Providers;

use App\Enums\InstanceStatus;
use App\Models\Settings;
use App\Models\WhatsappInstance;
use App\Policies\SettingsPolicy;
use App\Policies\WhatsappInstancePolicy;
use GraphQL\Type\Definition\PhpEnumType;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\ServiceProvider;
use Nuwave\Lighthouse\Schema\TypeRegistry;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected array $policies = [
        WhatsappInstance::class => WhatsappInstancePolicy::class,
        Settings::class => SettingsPolicy::class,
    ];

    public function boot(): void
    {
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });

        $typeRegistry = app(TypeRegistry::class);
        $typeRegistry->register(new PhpEnumType(InstanceStatus::class));
    }
}
