<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserWithSubscriptionController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $users = User::with(['subscriptions' => fn($query) => $query->select(['id', 'domain', 'expired_at'])]
        )->withCount(['whatsappInstances'])->get();

        return response()->json($users);
    }
}
