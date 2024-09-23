<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserWithSubscriptionController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $filters = $request->input('filters');

        $page = $request->input('page',1);
        $perPage = $request->input('perPage',20);

        $users = User::with(['activeSubscription' => fn(Builder $query) => $query->select(['domain', 'expired_at'])])
            ->select(
                [
                    'users.id',
                    'users.domain',
                    'max_instances_count'
                ]
            )
            ->withCount(['whatsappInstances as current_instances_count'])
            ->where(function (Builder $query) use ($filters) {
                $query->when(isset($filters['domain']), fn(Builder $query) => $query->where('domain', 'LIKE', "%{$filters['domain']}%"));
                $query->when(isset($filters['id']), fn(Builder $query) => $query->where('id', 'LIKE', "%{$filters['id']}%"));
                $query->when(isset($filters['has_subscription']), fn(Builder $query) => $filters['has_subscription'] ? $query->whereHas("activePaidSubscription") : $query->whereDoesntHave("activeSubscription"));
            })
            ->paginate(perPage:  $perPage, page:  $page);

        return response()->json($users);
    }
}
