<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Media Storage Disk
    |--------------------------------------------------------------------------
    |
    | public       -> local storage/app/public (local testing default)
    | vercel-blob  -> Vercel Blob via OIDC (forced in vercel.json for production)
    | auto         -> Blob on Vercel runtime, otherwise local unless credentials exist
    |
    */
    'disk' => env('MEDIA_DISK', 'auto'),

    /*
    |--------------------------------------------------------------------------
    | Vercel Blob credentials
    |--------------------------------------------------------------------------
    |
    | Production (Vercel connected Blob store uses OIDC — no copyable RW token):
    | - BLOB_STORE_ID              injected when store is connected
    | - x-vercel-oidc-token header injected per request (preferred)
    | - VERCEL_OIDC_TOKEN          fallback env (short-lived)
    |
    | Local optional fallback:
    | - vercel env pull            fills OIDC + store id into .env.local
    | - BLOB_READ_WRITE_TOKEN      only if your store still exposes one
    |
    */
    'blob_store_id' => env('BLOB_STORE_ID'),
    'oidc_token' => env('VERCEL_OIDC_TOKEN'),
    'blob_token' => env('BLOB_READ_WRITE_TOKEN'),
];
