<?php

namespace App\Http\Controllers\AmoCRM;

use App\Events\AmoCRM\UserCreated;
use App\Events\AmoCRM\WidgetInstalled;
use App\Http\Controllers\Controller;
use App\Http\Requests\AmoCRM\WidgetInstallRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class WidgetInstallController extends Controller
{
    public function __invoke(WidgetInstallRequest $request): JsonResponse
    {
        $data = $request->toDTO();

        $user = User::query()->updateOrCreate(['id' => $data->user->id], array_filter([
            'id' => $data->user->id,
            'amojo_id' => $data->user->amojo_id,
            'domain' => $data->user->domain,
            'email' => $data->user->email,
            'phone' => $data->user->phone,
        ]));

        WidgetInstalled::dispatch($data->info);

        UserCreated::dispatch($user);

        return response()->json([
            'user_id' => $user->getKey(),
            'access_token' => $user->createToken('amocrm')->plainTextToken,
        ], 201);
    }
}
