<?php

namespace App\Services;

final class VercelBlobAuth
{
    public function __construct(
        public readonly string $kind,
        public readonly string $token,
        public readonly string $storeId,
    ) {
    }

    public function resolve(): ?self
    {
        $oidcToken = $this->oidcToken();
        $storeId = $this->storeId();

        if ($oidcToken !== null && $storeId !== null) {
            return new self('oidc', $oidcToken, $storeId);
        }

        $readWriteToken = $this->readWriteToken();

        if ($readWriteToken !== null) {
            return new self('readWrite', $readWriteToken, $this->parseStoreIdFromReadWriteToken($readWriteToken));
        }

        return null;
    }

    public static function isVercelRuntime(): bool
    {
        if (filter_var(env('VERCEL', false), FILTER_VALIDATE_BOOL)) {
            return true;
        }

        foreach (['VERCEL_ENV', 'VERCEL_URL', 'VERCEL_REGION'] as $key) {
            if (!empty(env($key))) {
                return true;
            }
        }

        return false;
    }

    private function oidcToken(): ?string
    {
        $candidates = [];

        if (function_exists('request') && app()->bound('request')) {
            $candidates[] = request()->header('x-vercel-oidc-token');
        }

        $candidates[] = $_SERVER['HTTP_X_VERCEL_OIDC_TOKEN'] ?? null;
        $candidates[] = config('media.oidc_token');
        $candidates[] = env('VERCEL_OIDC_TOKEN');

        foreach ($candidates as $candidate) {
            $token = trim((string) $candidate);

            if ($token !== '') {
                return $token;
            }
        }

        return null;
    }

    private function storeId(): ?string
    {
        $candidates = [
            config('media.blob_store_id'),
            env('BLOB_STORE_ID'),
        ];

        foreach ($candidates as $candidate) {
            $storeId = trim((string) $candidate);

            if ($storeId !== '') {
                return self::normalizeStoreId($storeId);
            }
        }

        return null;
    }

    private function readWriteToken(): ?string
    {
        $candidates = [
            config('media.blob_token'),
            env('BLOB_READ_WRITE_TOKEN'),
        ];

        foreach ($candidates as $candidate) {
            $token = trim((string) $candidate);

            if ($token !== '') {
                return $token;
            }
        }

        return null;
    }

    private function parseStoreIdFromReadWriteToken(string $token): string
    {
        $parts = explode('_', $token);

        return self::normalizeStoreId($parts[3] ?? '');
    }

    public static function normalizeStoreId(string $storeId): string
    {
        return str_starts_with($storeId, 'store_')
            ? substr($storeId, strlen('store_'))
            : $storeId;
    }
}
