<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Media Storage Disk
    |--------------------------------------------------------------------------
    |
    | "public"       -> local storage/app/public (default for local dev)
    | "vercel-blob"  -> Vercel Blob via BLOB_READ_WRITE_TOKEN
    | "auto"         -> use Vercel Blob when token is available
    |
    */
    'disk' => env('MEDIA_DISK', 'auto'),

    'blob_token' => env('BLOB_READ_WRITE_TOKEN'),
];
