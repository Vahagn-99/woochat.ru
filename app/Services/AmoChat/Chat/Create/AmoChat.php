<?php

namespace App\Services\AmoChat\Chat\Create;

use Illuminate\Support\Arr;

class AmoChat
{
    public function __construct(
        public string  $id,
        public string  $user_id,
        public string  $user_name,
        public ?string $user_client_id = null,
        public ?string $user_avatar = null,
        public ?string $user_profile_phone = null,
        public ?string $user_profile_email = null
    )
    {
    }

    public static function fromArray(array $data): AmoChat
    {
        $user = $data['user'];
        return new self(
            id: Arr::get($data, 'id'),
            user_id: Arr::get($user, 'id'),
            user_name: Arr::get($user, 'name'),
            user_client_id: Arr::get($user, 'client_id'),
            user_avatar: Arr::get($user, 'avatar'),
            user_profile_phone: Arr::get($user, 'phone'),
            user_profile_email: Arr::get($user, 'email'),
        );
    }
}