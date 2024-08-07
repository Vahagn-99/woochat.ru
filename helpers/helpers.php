<?php

if (!function_exists('events_path')) {
    function events_path($path = ''): string
    {
        return app_path('Events/' . $path);
    }
}

if (!function_exists('array_null_filter')) {
    function array_null_filter(array $array): array
    {
        return array_filter($array, fn($value) => !is_null($value));
    }
}