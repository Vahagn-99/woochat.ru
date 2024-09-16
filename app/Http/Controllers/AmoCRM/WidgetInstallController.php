<?php

namespace App\Http\Controllers\AmoCRM;

use App\Events\Messengers\AmoChat\ChannelRequested;
use App\Events\Widget\WidgetInstalled;
use App\Http\Controllers\Controller;
use App\Http\Requests\AmoCRM\WidgetInstallRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class WidgetInstallController extends Controller
{
    public function __invoke(WidgetInstallRequest $request): JsonResponse
    {
        $data = $request->toDTO();

        $updateData = array_filter([
            'id' => $data->user->id,
            'amojo_id' => $data->user->amojo_id,
            'domain' => $data->user->domain,
            'email' => $data->user->email,
            'phone' => $data->user->phone,
        ]);

        $user = User::getByDomainOrId($data->user);

        if ($user) {
            $user->update($updateData);
        } else {
            $user = User::query()->create($updateData);
        }

        if ($user->AdminShouldBeNotified()) {

            WidgetInstalled::dispatchIf($user->AdminShouldBeNotified(), $user, $data->info);
        } else {

            do_log("widget/installing")->notice("{$user->domain} авторизован но Админ не получил уведемленя об установке так-как уже до этого получиль его. по этому клиенту.", [
                'data' => $user->info->data,
            ]);
        }

        ChannelRequested::dispatch($user);

        return response()->json([
            'user_id' => $user->id,
            'access_token' => $user->createToken('amocrm')->plainTextToken,
        ], 201);
    }
}
