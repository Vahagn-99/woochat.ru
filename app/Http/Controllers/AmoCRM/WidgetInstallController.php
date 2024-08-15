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

        $updateData = [
            'id' => $data->user->id,
            'api_key' => $data->user->api_key,
            'amojo_id' => $data->user->amojo_id,
            'domain' => $data->user->domain,
            'email' => $data->user->email,
            'deleted_at' => null,
        ];

        $user = User::getByDomainOrId($data->user);

        $dispatchWidgetInstalledEvent = true;

        if ($user) {

            $user->update($updateData);

            $dispatchWidgetInstalledEvent = false;
        } else {
            $user = User::query()->create($updateData);
        }

        WidgetInstalled::dispatchIf($dispatchWidgetInstalledEvent, $user, $data->info);

        UserCreated::dispatch($user);

        return response()->json([
            'user_id' => $user->getKey(),
            'access_token' => $user->createToken('amocrm')->plainTextToken,
        ], 201);
    }
}
