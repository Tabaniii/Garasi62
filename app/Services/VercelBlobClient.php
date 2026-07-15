<?php

namespace App\Services;

use GuzzleHttp\Client as HttpClient;
use RuntimeException;

/**
 * Minimal Vercel Blob REST client with OIDC + store-id support.
 * Compatible with Vercel's current Blob API (x-vercel-blob-store-id).
 */
class VercelBlobClient
{
    private const API_URL = 'https://vercel.com/api/blob';
    private const API_VERSION = '12';

    private HttpClient $http;

    public function __construct(
        private readonly VercelBlobAuth $auth = new VercelBlobAuth(),
    ) {
        $this->http = new HttpClient([
            'http_errors' => false,
            'timeout' => 60,
        ]);
    }

    /**
     * @return array{url:string,downloadUrl:string,pathname:string,contentType?:string,contentDisposition?:string}
     */
    public function put(string $pathname, string $content, array $options = []): array
    {
        $credentials = $this->requireCredentials();
        $query = http_build_query(['pathname' => ltrim($pathname, '/')]);

        $headers = array_merge($this->authHeaders($credentials), [
            'x-api-version' => self::API_VERSION,
            'x-vercel-blob-access' => $options['access'] ?? 'public',
            'x-content-type' => $options['contentType'] ?? 'application/octet-stream',
            'x-add-random-suffix' => ($options['addRandomSuffix'] ?? false) ? '1' : '0',
            'x-allow-overwrite' => ($options['allowOverwrite'] ?? false) ? '1' : '0',
        ]);

        $response = $this->http->request('PUT', self::API_URL . '/?' . $query, [
            'headers' => $headers,
            'body' => $content,
        ]);

        return $this->decodeResponse($response->getStatusCode(), (string) $response->getBody(), 'upload blob');
    }

    /**
     * @param string[] $urls
     */
    public function delete(array $urls): void
    {
        if ($urls === []) {
            return;
        }

        $credentials = $this->requireCredentials();

        $response = $this->http->request('POST', self::API_URL . '/delete', [
            'headers' => array_merge($this->authHeaders($credentials), [
                'x-api-version' => self::API_VERSION,
                'Content-Type' => 'application/json',
            ]),
            'json' => ['urls' => array_values($urls)],
        ]);

        if ($response->getStatusCode() >= 400) {
            $this->decodeResponse($response->getStatusCode(), (string) $response->getBody(), 'delete blob');
        }
    }

    public function head(string $url): array
    {
        $credentials = $this->requireCredentials();
        $query = http_build_query(['url' => $url]);

        $response = $this->http->request('GET', self::API_URL . '/?' . $query, [
            'headers' => array_merge($this->authHeaders($credentials), [
                'x-api-version' => self::API_VERSION,
            ]),
        ]);

        return $this->decodeResponse($response->getStatusCode(), (string) $response->getBody(), 'head blob');
    }

    public function hasCredentials(): bool
    {
        $credentials = $this->auth->resolve();

        return $credentials !== null && $credentials->token !== '' && $credentials->storeId !== '';
    }

    private function requireCredentials(): VercelBlobCredentials
    {
        $credentials = $this->auth->resolve();

        if ($credentials === null || $credentials->token === '' || $credentials->storeId === '') {
            throw new RuntimeException(
                'Vercel Blob credentials tidak lengkap. Pastikan Blob store terhubung ke project '
                . '(BLOB_STORE_ID tersedia) dan OIDC aktif (header x-vercel-oidc-token / VERCEL_OIDC_TOKEN).'
            );
        }

        return $credentials;
    }

    /**
     * @return array<string, string>
     */
    private function authHeaders(VercelBlobCredentials $credentials): array
    {
        return [
            'Authorization' => 'Bearer ' . $credentials->token,
            'x-vercel-blob-store-id' => $credentials->storeId,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function decodeResponse(int $statusCode, string $body, string $action): array
    {
        if ($statusCode < 400) {
            $decoded = json_decode($body, true);

            if (!is_array($decoded)) {
                throw new RuntimeException("Gagal {$action}: respons Vercel Blob tidak valid.");
            }

            return $decoded;
        }

        $decoded = json_decode($body, true);
        $message = is_array($decoded)
            ? ($decoded['error']['message'] ?? $decoded['error']['code'] ?? null)
            : null;

        throw new RuntimeException(
            'Gagal ' . $action . ' ke Vercel Blob'
            . ($message ? ': ' . $message : " (HTTP {$statusCode}).")
        );
    }
}
