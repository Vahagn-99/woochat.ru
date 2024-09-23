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
        $users = User::with(['activeSubscription' => fn($query) => $query->select(['domain', 'expired_at'])])
            ->select(
                [
                    'users.id',
                    'users.domain',
                    'max_instances_count'
                ]
            )->withCount(['whatsappInstances as current_instances_count'])->get();

        return response()->json($users);
    }
}
