<?php

namespace App\Http\Requests;

use App\Base\Subscription\SubscriptionDto;
use App\Contracts\Dtoable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

class FullSubscriptionRequest extends FormRequest implements Dtoable
{
    public function rules(): array
    {
        return [
            'domain' => ['required', 'string', 'exists:users,domain'],
            'expired_at' => ['required', 'date', 'after:now'],
        ];
    }

    public function toDto(): SubscriptionDto
    {
        $data = $this->validated();

        return new SubscriptionDto(
            $data['domain'], Carbon::create($data['expired_at'])
        );
    }
}
