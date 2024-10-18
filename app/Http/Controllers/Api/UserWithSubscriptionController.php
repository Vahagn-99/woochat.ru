<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\User as UserResource;
use App\Models\User as UserModel;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserWithSubscriptionController extends Controller
{
    public function __invoke(Request $request): AnonymousResourceCollection
    {
        $filters = $request->input('filters');

        $page = $request->input('page', 1);
        $perPage = $request->input('perPage', 20);

        $users = UserModel::with([
            'whatsappInstances',
            'activeSubscription' => fn($query) => $query->select(['domain', 'expired_at']),
        ])
            ->select(
                [
                    'users.id',
                    'users.domain',
                    'max_instances_count',
                ]
            )
            ->withCount(['whatsappInstances as current_instances_count'])
            ->where(function ($query) use ($filters) {
                $query->when(isset($filters['id']), fn($query) => $query->where('id', 'LIKE', "%{$filters['id']}%"));
                $query->when(isset($filters['domain']), fn($query) => $query->where('domain', 'LIKE', "%{$filters['domain']}%"));
                $query->when(isset($filters['has_subscription']), fn($query) => $filters['has_subscription'] ? $query->whereHas("activePaidSubscription") : $query->whereDoesntHave("activePaidSubscription"));
                $query->when(isset($filters['instance']), fn($query) => $query->whereHas('whatsappInstances',function ($query) use($filters) {
                    $query->where("whatsapp_instances.id", "LIKE", "%{$filters['instance']}%");
                    $query->orWhere("whatsapp_instances.phone", "LIKE", "%{$filters['instance']}%");
                }));
            })
            ->withAggregate('activeSubscription', 'expired_at')
            ->orderByDesc(
                'active_subscription_expired_at'
            )->orderByDesc('created_at')
            ->paginate(perPage: $perPage, page: $page);

        return UserResource::collection($users);
    }
}
