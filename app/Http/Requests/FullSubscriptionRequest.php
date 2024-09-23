<?php

namespace App\Http\Requests;

use App\Base\Subscription\SubscriptionDto;
use App\Contracts\Dtoable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class FullSubscriptionRequest extends FormRequest implements Dtoable
{
    public function rules(): array
    {
        return [
            'domain' => ['required', 'string', 'exists:users,domain'],
            'expired_at' => ['required', 'date', 'after:now'],
            'max_instances_count' => ['nullable', 'numeric', 'min:1', 'max:10'],
        ];
    }

    public function toDto(): SubscriptionDto
    {
        $data = $this->validated();

        return new SubscriptionDto(
            Arr::get($data, 'domain'),
            Carbon::create(Arr::get($data, 'expired_at')),
            Arr::get($data, 'max_instances_count', 1),
        );
    }
}
