<?php

namespace App\Services\GreenApi\Facades;

use App\Models\Instance;
use App\Services\GreenApi\DTO\InstanceDTO;
use GreenApi\RestApi\GreenApiClient;
use Illuminate\Support\Facades\Facade;

class Greenapi extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'greenapi';
    }

    public static function for(InstanceDTO $instance): void
    {
        $client = new GreenApiClient(
            $instance->id,
            $instance->token
        );

        app()->instance(GreenApiClient::class, $client);
    }
}