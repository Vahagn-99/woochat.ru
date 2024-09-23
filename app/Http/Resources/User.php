<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property \App\Models\User $resource
 */
class User extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'domain' => $this->resource->domain,
            'max_instances_count' => $this->resource->max_instances_count,
            'current_instances_count' => $this->resource->current_instances_count,
            'active_subscription' => Subscription::make($this->whenLoaded('activeSubscription')),
        ];
    }
}
