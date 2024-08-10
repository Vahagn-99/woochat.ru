<?php

namespace App\Http\Controllers\AmoCRM;

use App\Events\AmoCRM\UserDeleted;
use App\Events\AmoCRM\WidgetDeleted;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WidgetDeleteController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = User::query()->findOrFail($request->get('account_id'));
        $user->delete();

        WidgetDeleted::dispatch($user);

        UserDeleted::dispatch($user);

        do_log('amocrm/widget-delete')->info("The widget was deleted");

        return response()->json(["widget deleted"], Response::HTTP_NO_CONTENT);
    }
}
