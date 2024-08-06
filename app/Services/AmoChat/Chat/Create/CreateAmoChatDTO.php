<?php

namespace App\Services\AmoChat\Chat\Create;

use App\Contracts\Arrayable;

class CreateAmoChatDTO implements Arrayable
{
    public function __construct(
        public string  $conversation_id,
        public string  $external_id,
        public string  $user_id,
        public string  $user_name,
        public ?string $user_ref_id = null,
        public ?string $user_avatar = null,
        public ?string $user_profile_phone = null,
        public ?string $user_profile_email = null,
        public ?string $user_profile_link = null,
    )
    {
    }

    public function toArray(): array
    {
        return array_filter([
            "conversation_id" => $this->conversation_id,
            "source" => [
                "external_id" => $this->external_id
            ],
            "user" => [
                "id" => $this->user_id,
                "avatar" => $this->user_avatar,
                "name" => $this->user_name,
                "profile" => [
                    "phone" => $this->user_profile_phone,
                    "email" => $this->user_profile_email
                ],
                "profile_link" => $this->user_profile_link
            ]
        ]);
    }
}