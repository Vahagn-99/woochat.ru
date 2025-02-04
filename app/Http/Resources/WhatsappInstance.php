<?php

namespace App\Http\Resources;

use App\Models\Subscription as SubscriptionModel;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property \App\Models\WhatsappInstance $resource
*/
class WhatsappInstance extends JsonResource
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
            'user_id' => $this->resource->user_id,
            'status' => $this->resource->status,
            'token' => $this->resource->token,
            'phone' => $this->resource->phone,
            'blocked_at' => $this->resource->blocked_at,
        ];
    }
}
