<?php

namespace App\Services\AmoChat\Chat\Connect;

class Connetion
{
    public function __construct(
        public string $account_id,
        public string $scope_id,
        public string $title,
        public string $hook_api_version,
        public bool   $is_time_window_disabled
    )
    {
    }

    public static function fromArray(array $data): Connetion
    {
        return new self(
            account_id: $data['account_id'],
            scope_id: $data['scope_id'],
            title: $data['title'],
            hook_api_version: $data['hook_api_version'],
            is_time_window_disabled: $data['is_time_window_disabled']
        );
    }
}