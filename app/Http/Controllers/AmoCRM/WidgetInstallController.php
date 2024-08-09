<?php

namespace App\Http\Controllers\AmoCRM;

use App\Events\AmoChat\UserCreated;
use App\Events\AmoCrm\WidgetInstalled;
use App\Http\Controllers\Controller;
use App\Http\Requests\AmoCRM\WidgetInstallRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class WidgetInstallController extends Controller
{
    public function __invoke(WidgetInstallRequest $request): JsonResponse
    {
        $data = $request->toDTO();

        $user = User::query()->updateOrCreate(['amojo_id' => $data->user->amojo_id],
            array_filter([
                'domain' => $data->user->domain,
                'email' => $data->user->email,
                'phone' => $data->user->phone,
            ])
        );

        WidgetInstalled::dispatch($data->info);
        UserCreated::dispatch($user);

        return response()->json([
            'user_id' => $user->getKey(),
            'access_token' => $user->createToken('amocrm')->plainTextToken,
        ], 201);
    }
}
