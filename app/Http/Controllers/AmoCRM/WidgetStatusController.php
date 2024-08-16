<?php

namespace App\Http\Controllers\AmoCRM;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AmoCRM\Core\Facades\Amo;
use Illuminate\Http\Request;

class WidgetStatusController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, int $userId): \Illuminate\Http\JsonResponse
    {
        /** @var User $user */
        $user = User::withTrashed()->find($userId);

        $authStatus = Amo::domain($user->domain)->oauth()->status();

        return response()->json([
            'user' => $user,
            'oauth_status' => $authStatus,
        ]);
    }
}
