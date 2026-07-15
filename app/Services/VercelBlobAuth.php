<?php

namespace App\Services;

/**
 * Resolves Vercel Blob auth without relying on a copyable BLOB_READ_WRITE_TOKEN.
 *
 * On Vercel production:
 * - OIDC token arrives via x-vercel-oidc-token (or VERCEL_OIDC_TOKEN)
 * - Store id is injected as BLOB_STORE_ID when the Blob store is connected
 *
 * Locally:
 * - Uses local disk unless OIDC credentials or a read-write token are available
 */
final class VercelBlobAuth
{
    public function resolve(): ?VercelBlobCredentials
    {
        $oidcToken = $this->oidcToken();
        $storeId = $this->storeId();

        if ($oidcToken !== null && $storeId !== null) {
            return new VercelBlobCredentials('oidc', $oidcToken, $storeId);
        }

        // On Vercel we must use Blob; still return partial OIDC if store id exists
        // so callers can surface a clear credential error instead of writing to disk.
        $readWriteToken = $this->readWriteToken();

        if ($readWriteToken !== null) {
            $parsedStoreId = $this->parseStoreIdFromReadWriteToken($readWriteToken);

            return new VercelBlobCredentials(
                'readWrite',
                $readWriteToken,
                $parsedStoreId !== '' ? $parsedStoreId : ($storeId ?? '')
            );
        }

        return null;
    }

    public function hasStoreBinding(): bool
    {
        return $this->storeId() !== null;
    }

    /**
     * Detect Vercel serverless runtime without Laravel env()/config cache quirks.
     */
    public static function isVercelRuntime(): bool
    {
        $vercel = self::runtimeValue('VERCEL');

        if ($vercel !== null && $vercel !== '' && $vercel !== '0' && strtolower($vercel) !== 'false') {
            return true;
        }

        foreach (['VERCEL_ENV', 'VERCEL_URL', 'VERCEL_REGION', 'VERCEL_DEPLOYMENT_ID'] as $key) {
            $value = self::runtimeValue($key);

            if ($value !== null && $value !== '') {
                return true;
            }
        }

        // vercel-php Lambda path seen in production errors
        if (isset($_SERVER['LAMBDA_TASK_ROOT']) || isset($_ENV['AWS_LAMBDA_FUNCTION_NAME'])) {
            $cwd = getcwd() ?: '';

            if (str_contains($cwd, '/var/task') || is_dir('/var/task')) {
                return true;
            }
        }

        return false;
    }

    public static function shouldForceBlob(): bool
    {
        $disk = (string) (config('media.disk') ?: self::runtimeValue('MEDIA_DISK') ?: 'auto');

        if ($disk === 'vercel-blob') {
            return true;
        }

        if ($disk === 'public') {
            return false;
        }

        // auto on Vercel: always Blob — local disk is read-only in serverless
        if (self::isVercelRuntime()) {
            return true;
        }

        // auto on local: Blob only when full credentials are present
        $credentials = (new self())->resolve();

        return $credentials !== null
            && $credentials->token !== ''
            && $credentials->storeId !== '';
    }

    private function oidcToken(): ?string
    {
        $candidates = [];

        // Prefer per-request OIDC header (rotated by Vercel on each invocation)
        if (function_exists('request')) {
            try {
                if (app()->bound('request')) {
                    $candidates[] = request()->header('x-vercel-oidc-token');
                }
            } catch (\Throwable) {
                // ignore during early boot
            }
        }

        $candidates[] = $_SERVER['HTTP_X_VERCEL_OIDC_TOKEN'] ?? null;
        $candidates[] = self::runtimeValue('HTTP_X_VERCEL_OIDC_TOKEN');
        $candidates[] = config('media.oidc_token');
        $candidates[] = self::runtimeValue('VERCEL_OIDC_TOKEN');

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
            self::runtimeValue('BLOB_STORE_ID'),
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
            self::runtimeValue('BLOB_READ_WRITE_TOKEN'),
        ];

        foreach ($candidates as $candidate) {
            $token = trim((string) $candidate);

            // Locked/placeholder OIDC dashboard values must not count as credentials
            if ($token === '' || str_contains($token, '****') || str_starts_with($token, '@')) {
                continue;
            }

            return $token;
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

    private static function runtimeValue(string $key): ?string
    {
        foreach ([$_ENV[$key] ?? null, $_SERVER[$key] ?? null, getenv($key) ?: null] as $value) {
            if ($value === false || $value === null) {
                continue;
            }

            $string = trim((string) $value);

            if ($string !== '') {
                return $string;
            }
        }

        return null;
    }
}
