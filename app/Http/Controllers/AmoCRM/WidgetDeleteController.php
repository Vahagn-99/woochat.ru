<?php

namespace App\Http\Controllers\AmoCRM;

use App\Events\Widget\WidgetDeleted;
use App\Events\Messaging\UserDeleted;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WidgetDeleteController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = User::query()->where('id', $request->get('account_id'))->firstOrFail();
        $user->delete();

        WidgetDeleted::dispatch($user);

        UserDeleted::dispatch($user);

        do_log('amocrm/widget')->info("The widget was deleted");

        return response()->json(["widget" => "deleted"]);
    }
}
