<?php

use App\Services\MediaStorage;

if (!function_exists('media')) {
    function media(): MediaStorage
    {
        return app(MediaStorage::class);
    }
}

if (!function_exists('media_url')) {
    function media_url(?string $path): ?string
    {
        return media()->url($path);
    }
}
