<?php

namespace App\Services;

/**
 * Resolved Vercel Blob credentials (OIDC or legacy read-write token).
 */
final class VercelBlobCredentials
{
    public function __construct(
        public readonly string $kind,
        public readonly string $token,
        public readonly string $storeId,
    ) {
    }
}
