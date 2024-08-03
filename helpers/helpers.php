<?php

if (!function_exists('events_path')) {
    function events_path($path = ''): string
    {
        return app_path('Events/' . $path);
    }
}
