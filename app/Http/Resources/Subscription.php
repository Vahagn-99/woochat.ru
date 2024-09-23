<?php

namespace App\Http\Resources;

use App\Models\Subscription as SubscriptionModel;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property SubscriptionModel $resource
*/
class Subscription extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'domain' => $this->resource->domain,
            'expired_at' => $this->resource->expired_at,
        ];
    }
}
