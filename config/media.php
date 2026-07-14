<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Media Storage Disk
    |--------------------------------------------------------------------------
    |
    | public       -> local storage/app/public (default for local dev)
    | vercel-blob  -> Vercel Blob (forced on Vercel runtime)
    | auto         -> Vercel Blob when credentials exist, otherwise public
    |
    */
    'disk' => env('MEDIA_DISK', 'auto'),

    /*
    |--------------------------------------------------------------------------
    | Vercel Blob credentials
    |--------------------------------------------------------------------------
    |
    | Production on Vercel uses OIDC:
    | - BLOB_STORE_ID (injected when Blob store is connected)
    | - VERCEL_OIDC_TOKEN or x-vercel-oidc-token request header
    |
    | Local dev can use `vercel env pull` to fetch short-lived OIDC credentials,
    | or optionally set BLOB_READ_WRITE_TOKEN as fallback.
    |
    */
    'blob_store_id' => env('BLOB_STORE_ID'),
    'oidc_token' => env('VERCEL_OIDC_TOKEN'),
    'blob_token' => env('BLOB_READ_WRITE_TOKEN'),
];
